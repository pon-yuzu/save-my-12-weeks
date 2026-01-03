<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MailTemplate;
use Illuminate\Http\Request;

class MailTemplateController extends Controller
{
    public function index()
    {
        $templates = MailTemplate::orderBy('day_number')->get();

        return view('admin.mail-templates.index', compact('templates'));
    }

    public function create()
    {
        $usedDays = MailTemplate::pluck('day_number')->toArray();
        $availableDays = array_diff(range(1, 30), $usedDays);

        return view('admin.mail-templates.create', compact('availableDays'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'day_number' => ['required', 'integer', 'min:1', 'max:30', 'unique:mail_templates,day_number'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        MailTemplate::create($validated);

        return redirect()->route('admin.mail-templates.index')
            ->with('success', 'メールテンプレートを作成しました。');
    }

    public function edit(MailTemplate $mailTemplate)
    {
        return view('admin.mail-templates.edit', compact('mailTemplate'));
    }

    public function update(Request $request, MailTemplate $mailTemplate)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $mailTemplate->update($validated);

        return redirect()->route('admin.mail-templates.index')
            ->with('success', 'メールテンプレートを更新しました。');
    }

    public function destroy(MailTemplate $mailTemplate)
    {
        $mailTemplate->delete();

        return redirect()->route('admin.mail-templates.index')
            ->with('success', 'メールテンプレートを削除しました。');
    }
}
