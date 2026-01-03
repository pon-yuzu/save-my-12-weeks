<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン | 管理画面</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Noto+Serif+JP:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-bg: #faf8f5;
            --color-teal: #0d7377;
            --color-teal-light: #14919b;
            --color-orange: #ff6b35;
            --foreground: #2d2d2d;
            --foreground-light: #6b6b6b;
            --foreground-muted: #9a9a9a;
            --font-serif: "Noto Serif JP", serif;
            --font-display: "Cormorant Garamond", serif;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: var(--font-serif);
            background: var(--color-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
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
        .login-card {
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(0, 0, 0, 0.08);
            padding: 48px 40px;
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 2;
        }
        .login-header {
            text-align: center;
            margin-bottom: 36px;
        }
        .login-header h1 {
            font-family: var(--font-display);
            font-size: 1.5rem;
            font-weight: 500;
            letter-spacing: 0.08em;
            color: var(--color-orange);
            margin-bottom: 8px;
        }
        .login-header p {
            color: var(--foreground-muted);
            font-size: 0.9rem;
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
        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.6);
            font-family: var(--font-serif);
            font-size: 1rem;
            color: var(--foreground);
            transition: border-color 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--color-orange);
        }
        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--foreground-light);
        }
        .form-checkbox input {
            width: 16px;
            height: 16px;
            accent-color: var(--color-orange);
        }
        .btn-login {
            width: 100%;
            padding: 16px;
            background: var(--color-orange);
            color: #fff;
            border: none;
            font-family: var(--font-serif);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-login:hover {
            background: var(--color-orange-dark);
        }
        .error-message {
            background: rgba(255, 107, 53, 0.1);
            color: var(--color-orange);
            padding: 14px 16px;
            border: 1px solid rgba(255, 107, 53, 0.2);
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="mesh-bg"></div>
    <div class="noise-overlay"></div>
    <div class="login-card">
        <div class="login-header">
            <h1>Save My 12 Weeks</h1>
            <p>管理画面にログイン</p>
        </div>

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">メールアドレス</label>
                <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label">パスワード</label>
                <input type="password" name="password" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="remember">
                    <span>ログイン状態を保持</span>
                </label>
            </div>
            <button type="submit" class="btn-login">ログイン</button>
        </form>
    </div>
</body>
</html>
