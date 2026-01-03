<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * 管理者権限のみアクセス可能
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admin = auth('admin')->user();

        if (!$admin || !$admin->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => '権限がありません。'], 403);
            }

            return redirect()->route('admin.dashboard')
                ->with('error', 'この機能には管理者権限が必要です。');
        }

        return $next($request);
    }
}
