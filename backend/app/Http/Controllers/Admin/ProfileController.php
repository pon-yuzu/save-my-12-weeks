<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * プロフィール編集画面
     */
    public function edit()
    {
        return view('admin.profile.edit', [
            'admin' => auth('admin')->user(),
        ]);
    }

    /**
     * プロフィール更新
     */
    public function update(Request $request)
    {
        $admin = auth('admin')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('admins')->ignore($admin->id),
            ],
            'current_password' => ['nullable', 'required_with:password', 'current_password:admin'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ], [
            'name.required' => '名前は必須です。',
            'email.required' => 'メールアドレスは必須です。',
            'email.email' => '有効なメールアドレスを入力してください。',
            'email.unique' => 'このメールアドレスは既に使用されています。',
            'current_password.required_with' => '新しいパスワードを設定する場合は現在のパスワードを入力してください。',
            'current_password.current_password' => '現在のパスワードが正しくありません。',
            'password.confirmed' => 'パスワード確認が一致しません。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
        ]);

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];

        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return redirect()->route('admin.profile.edit')
            ->with('success', 'プロフィールを更新しました。');
    }
}
