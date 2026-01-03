<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminInvitation;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    /**
     * 招待一覧
     */
    public function index()
    {
        $invitations = AdminInvitation::with('inviter')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.invitations.index', compact('invitations'));
    }

    /**
     * 招待作成フォーム
     */
    public function create()
    {
        $roles = Admin::ROLES;
        return view('admin.invitations.create', compact('roles'));
    }

    /**
     * 招待を作成
     */
    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|in:admin,editor',
            'email' => 'nullable|email',
            'expires_in_days' => 'required|integer|min:1|max:30',
        ]);

        $invitation = AdminInvitation::createInvitation(
            auth('admin')->id(),
            $request->role,
            $request->email,
            $request->expires_in_days
        );

        return redirect()
            ->route('admin.invitations.show', $invitation)
            ->with('success', '招待リンクを作成しました。');
    }

    /**
     * 招待詳細（リンク表示）
     */
    public function show(AdminInvitation $invitation)
    {
        return view('admin.invitations.show', compact('invitation'));
    }

    /**
     * 招待を削除
     */
    public function destroy(AdminInvitation $invitation)
    {
        $invitation->delete();

        return redirect()
            ->route('admin.invitations.index')
            ->with('success', '招待を削除しました。');
    }
}
