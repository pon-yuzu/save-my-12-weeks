<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '管理画面') | Save My 12 Weeks</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Noto+Serif+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --color-bg: #faf8f5;
            --color-teal: #0d7377;
            --color-teal-light: #14919b;
            --color-orange: #ff6b35;
            --color-orange-dark: #e85a28;
            --foreground: #2d2d2d;
            --foreground-light: #6b6b6b;
            --foreground-muted: #9a9a9a;
            --font-serif: "Noto Serif JP", serif;
            --font-display: "Cormorant Garamond", serif;
            --admin-border: rgba(0, 0, 0, 0.08);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-serif);
            background: var(--color-bg);
            color: var(--foreground);
            line-height: 1.6;
        }

        /* Background effects */
        .mesh-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            background: linear-gradient(180deg, var(--color-bg) 0%, #f5f2ed 100%);
            pointer-events: none;
        }

        .noise-overlay {
            position: fixed;
            inset: 0;
            z-index: 1;
            opacity: 0.02;
            pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
        }

        .admin-layout {
            display: flex;
            min-height: 100vh;
            position: relative;
            z-index: 2;
        }

        .admin-sidebar {
            width: 260px;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-right: 1px solid var(--admin-border);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .admin-sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid var(--admin-border);
        }

        .admin-sidebar-header h1 {
            font-family: var(--font-display);
            font-size: 1.1rem;
            font-weight: 500;
            letter-spacing: 0.05em;
            color: var(--color-orange);
        }

        .admin-sidebar-header span {
            font-size: 0.75rem;
            color: var(--foreground-muted);
            display: block;
            margin-top: 4px;
        }

        .admin-nav {
            padding: 16px 0;
        }

        .admin-nav a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--foreground-light);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .admin-nav a:hover {
            background: rgba(255, 107, 53, 0.05);
            color: var(--color-orange);
        }

        .admin-nav a.active {
            background: rgba(255, 107, 53, 0.1);
            color: var(--color-orange);
            border-right: 3px solid var(--color-orange);
        }

        .admin-nav svg {
            width: 20px;
            height: 20px;
            margin-right: 12px;
        }

        .admin-main {
            flex: 1;
            margin-left: 260px;
            padding: 32px;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }

        .admin-header h2 {
            font-family: var(--font-serif);
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--foreground);
        }

        .admin-card {
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid var(--admin-border);
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table th,
        .admin-table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid var(--admin-border);
        }

        .admin-table th {
            font-weight: 600;
            color: var(--foreground-muted);
            font-size: 0.85rem;
            background: rgba(0, 0, 0, 0.02);
        }

        .admin-table tr:hover {
            background: rgba(255, 107, 53, 0.02);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            font-family: var(--font-serif);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            border: none;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--color-orange);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--color-orange-dark);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: transparent;
            color: var(--foreground-light);
            border: 1px solid var(--admin-border);
        }

        .btn-secondary:hover {
            border-color: var(--color-orange);
            color: var(--color-orange);
            transform: translateY(-1px);
        }

        .btn-danger {
            background: var(--color-orange);
            color: #fff;
        }

        .btn-danger:hover {
            background: var(--color-orange-dark);
            transform: translateY(-1px);
        }

        .btn-sm {
            padding: 6px 14px;
            font-size: 0.8rem;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 8px;
        }

        .badge-success {
            background: rgba(255, 107, 53, 0.1);
            color: var(--color-orange);
        }

        .badge-danger {
            background: rgba(255, 107, 53, 0.1);
            color: var(--color-orange);
        }

        .badge-warning {
            background: rgba(255, 193, 7, 0.15);
            color: #b38600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--foreground);
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--admin-border);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.6);
            font-family: var(--font-serif);
            font-size: 0.9rem;
            color: var(--foreground);
            transition: border-color 0.2s;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--color-orange);
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .alert {
            padding: 14px 18px;
            margin-bottom: 20px;
            border: 1px solid;
            border-radius: 8px;
        }

        .alert-success {
            background: rgba(255, 107, 53, 0.08);
            color: var(--color-orange);
            border-color: rgba(255, 107, 53, 0.2);
        }

        .alert-error {
            background: rgba(255, 107, 53, 0.08);
            color: var(--color-orange-dark);
            border-color: rgba(255, 107, 53, 0.2);
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid var(--admin-border);
            border-radius: 8px;
            padding: 24px;
        }

        .stat-card h3 {
            font-size: 0.85rem;
            color: var(--foreground-muted);
            font-weight: 500;
            margin-bottom: 8px;
        }

        .stat-card .value {
            font-family: var(--font-display);
            font-size: 2.5rem;
            font-weight: 500;
            color: var(--color-orange);
        }

        .pagination {
            display: flex;
            gap: 4px;
            margin-top: 20px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid var(--admin-border);
            border-radius: 8px;
            text-decoration: none;
            color: var(--foreground-light);
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        .pagination a:hover {
            border-color: var(--color-orange);
            color: var(--color-orange);
            transform: translateY(-1px);
        }

        .pagination .active {
            background: var(--color-orange);
            color: #fff;
            border-color: var(--color-orange);
        }

        .search-form {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        .search-form input {
            flex: 1;
            max-width: 300px;
        }

        .logout-btn {
            width: calc(100% - 40px);
            margin: 20px;
            background: transparent;
            border: 1px solid var(--admin-border);
            border-radius: 8px;
            color: var(--foreground-muted);
            padding: 12px;
            cursor: pointer;
            font-family: var(--font-serif);
            font-size: 0.9rem;
            text-align: left;
            transition: all 0.2s;
        }

        .logout-btn:hover {
            border-color: var(--color-orange);
            color: var(--color-orange);
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .admin-main {
                margin-left: 0;
            }

            .admin-layout {
                flex-direction: column;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="mesh-bg"></div>
    <div class="noise-overlay"></div>

    <div class="admin-layout">
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header">
                <h1>SAVE MY 12 WEEKS</h1>
                <span>管理画面</span>
                @if(auth('admin')->check())
                <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--admin-border);">
                    <a href="{{ route('admin.profile.edit') }}" style="font-size: 0.8rem; color: var(--foreground-muted); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--color-orange)'" onmouseout="this.style.color='var(--foreground-muted)'">{{ auth('admin')->user()->name }}</a>
                    <span style="display: inline-block; padding: 2px 8px; font-size: 0.7rem; background: rgba(255,107,53,0.1); color: var(--color-orange); margin-left: 4px; border-radius: 4px;">
                        {{ auth('admin')->user()->role_label }}
                    </span>
                </div>
                @endif
            </div>
            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    ダッシュボード
                </a>
                <a href="{{ route('admin.subscribers.index') }}" class="{{ request()->routeIs('admin.subscribers.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    登録者管理
                </a>
                <a href="{{ route('admin.diagnosis.index') }}" class="{{ request()->routeIs('admin.diagnosis.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    診断結果
                </a>
                <a href="{{ route('admin.mail-templates.index') }}" class="{{ request()->routeIs('admin.mail-templates.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    メールテンプレート
                </a>
                <a href="{{ route('admin.broadcasts.index') }}" class="{{ request()->routeIs('admin.broadcasts.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    一斉配信
                </a>
                <a href="{{ route('admin.seminars.index') }}" class="{{ request()->routeIs('admin.seminars.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    セミナー日程
                </a>
                <a href="{{ route('admin.seminar-applications.index') }}" class="{{ request()->routeIs('admin.seminar-applications.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    セミナー申込
                </a>
                <a href="{{ route('admin.unsubscribe-reasons.index') }}" class="{{ request()->routeIs('admin.unsubscribe-reasons.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                    配信停止理由
                </a>
                @if(auth('admin')->check() && auth('admin')->user()->isAdmin())
                <a href="{{ route('admin.invitations.index') }}" class="{{ request()->routeIs('admin.invitations.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    招待管理
                </a>
                @endif
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">ログアウト</button>
                </form>
            </nav>
        </aside>

        <main class="admin-main">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>

    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>
</html>
