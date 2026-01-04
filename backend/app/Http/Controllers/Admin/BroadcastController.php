<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendBroadcastEmail;
use App\Models\Broadcast;
use App\Models\BroadcastRecipient;
use App\Models\MailSubscription;
use Illuminate\Http\Request;

class BroadcastController extends Controller
{
    public function index()
    {
        $broadcasts = Broadcast::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.broadcasts.index', compact('broadcasts'));
    }

    public function create()
    {
        $subscribers = MailSubscription::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.broadcasts.create', compact('subscribers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'target_type' => ['required', 'in:all,individual,filtered'],
            'recipient_ids' => ['nullable', 'array'],
            'recipient_ids.*' => ['exists:mail_subscriptions,id'],
            'day_min' => ['nullable', 'integer', 'min:0'],
            'day_max' => ['nullable', 'integer', 'min:0'],
            'scheduled_at' => ['nullable', 'date', 'after:now'],
            'send_now' => ['nullable', 'boolean'],
        ]);

        $broadcast = new Broadcast();
        $broadcast->subject = $validated['subject'];
        $broadcast->body = $validated['body'];
        $broadcast->target_type = $validated['target_type'];

        // 個別送信の場合
        if ($validated['target_type'] === 'individual' && !empty($validated['recipient_ids'])) {
            $broadcast->recipient_ids = $validated['recipient_ids'];
        }

        // フィルター送信の場合
        if ($validated['target_type'] === 'filtered') {
            $filter = [];
            if (!empty($validated['day_min'])) {
                $filter['day_min'] = $validated['day_min'];
            }
            if (!empty($validated['day_max'])) {
                $filter['day_max'] = $validated['day_max'];
            }
            $broadcast->target_filter = $filter;
        }

        // 予約配信 or 今すぐ送信 or 下書き
        if ($request->boolean('send_now')) {
            $broadcast->status = 'sending';
        } elseif (!empty($validated['scheduled_at'])) {
            $broadcast->scheduled_at = $validated['scheduled_at'];
            $broadcast->status = 'scheduled';
        } else {
            $broadcast->status = 'draft';
        }

        $broadcast->save();

        // 今すぐ送信の場合
        if ($request->boolean('send_now')) {
            $this->dispatchBroadcast($broadcast);
            return redirect()->route('admin.broadcasts.show', $broadcast)
                ->with('success', 'メールの送信を開始しました。');
        }

        return redirect()->route('admin.broadcasts.index')
            ->with('success', $broadcast->status === 'scheduled'
                ? '予約配信を設定しました。'
                : '下書きを保存しました。');
    }

    public function show(Broadcast $broadcast)
    {
        $broadcast->load('recipients.subscription');

        $stats = [
            'total' => $broadcast->recipients->count(),
            'sent' => $broadcast->recipients->where('status', 'sent')->count(),
            'opened' => $broadcast->recipients->whereNotNull('opened_at')->count(),
            'failed' => $broadcast->recipients->where('status', 'failed')->count(),
        ];

        return view('admin.broadcasts.show', compact('broadcast', 'stats'));
    }

    public function edit(Broadcast $broadcast)
    {
        if ($broadcast->status !== 'draft') {
            return redirect()->route('admin.broadcasts.show', $broadcast)
                ->with('error', '送信済み・予約済みのメールは編集できません。');
        }

        $subscribers = MailSubscription::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.broadcasts.edit', compact('broadcast', 'subscribers'));
    }

    public function update(Request $request, Broadcast $broadcast)
    {
        if ($broadcast->status !== 'draft') {
            return redirect()->route('admin.broadcasts.show', $broadcast)
                ->with('error', '送信済み・予約済みのメールは編集できません。');
        }

        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'target_type' => ['required', 'in:all,individual,filtered'],
            'recipient_ids' => ['nullable', 'array'],
            'recipient_ids.*' => ['exists:mail_subscriptions,id'],
            'day_min' => ['nullable', 'integer', 'min:0'],
            'day_max' => ['nullable', 'integer', 'min:0'],
            'scheduled_at' => ['nullable', 'date', 'after:now'],
            'send_now' => ['nullable', 'boolean'],
        ]);

        $broadcast->subject = $validated['subject'];
        $broadcast->body = $validated['body'];
        $broadcast->target_type = $validated['target_type'];
        $broadcast->recipient_ids = $validated['target_type'] === 'individual'
            ? ($validated['recipient_ids'] ?? null)
            : null;

        if ($validated['target_type'] === 'filtered') {
            $filter = [];
            if (!empty($validated['day_min'])) {
                $filter['day_min'] = $validated['day_min'];
            }
            if (!empty($validated['day_max'])) {
                $filter['day_max'] = $validated['day_max'];
            }
            $broadcast->target_filter = $filter;
        } else {
            $broadcast->target_filter = null;
        }

        if ($request->boolean('send_now')) {
            $broadcast->status = 'sending';
            $broadcast->save();
            $this->dispatchBroadcast($broadcast);
            return redirect()->route('admin.broadcasts.show', $broadcast)
                ->with('success', 'メールの送信を開始しました。');
        }

        if (!empty($validated['scheduled_at'])) {
            $broadcast->scheduled_at = $validated['scheduled_at'];
            $broadcast->status = 'scheduled';
        }

        $broadcast->save();

        return redirect()->route('admin.broadcasts.index')
            ->with('success', '更新しました。');
    }

    public function destroy(Broadcast $broadcast)
    {
        if ($broadcast->status === 'sending') {
            return redirect()->route('admin.broadcasts.index')
                ->with('error', '送信中のメールは削除できません。');
        }

        $broadcast->delete();

        return redirect()->route('admin.broadcasts.index')
            ->with('success', '削除しました。');
    }

    /**
     * プレビュー（対象者数の確認）
     */
    public function preview(Request $request)
    {
        $targetType = $request->input('target_type', 'all');
        $recipientIds = $request->input('recipient_ids', []);
        $dayMin = $request->input('day_min');
        $dayMax = $request->input('day_max');

        $query = MailSubscription::where('is_active', true);

        if ($targetType === 'individual' && !empty($recipientIds)) {
            $query->whereIn('id', $recipientIds);
        } elseif ($targetType === 'filtered') {
            if ($dayMin !== null) {
                $query->where('current_day', '>=', $dayMin);
            }
            if ($dayMax !== null) {
                $query->where('current_day', '<=', $dayMax);
            }
        }

        return response()->json([
            'count' => $query->count(),
        ]);
    }

    /**
     * ブロードキャストの送信をディスパッチ
     */
    private function dispatchBroadcast(Broadcast $broadcast)
    {
        $subscriptions = $broadcast->getTargetSubscriptions()->get();

        foreach ($subscriptions as $subscription) {
            // 受信者レコードを作成
            $recipient = BroadcastRecipient::create([
                'broadcast_id' => $broadcast->id,
                'subscription_id' => $subscription->id,
                'status' => 'pending',
            ]);

            // キューに追加
            SendBroadcastEmail::dispatch($broadcast, $subscription, $recipient);
        }

        $broadcast->update(['sent_count' => $subscriptions->count()]);
    }
}
