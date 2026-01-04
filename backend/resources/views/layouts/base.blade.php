<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Save My 12 Weeks')</title>
    <meta name="description" content="@yield('description', '私の12週間を取り戻せ')">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Noto+Serif+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])

    <style>
        html, body {
            height: auto;
            overflow: auto;
            overscroll-behavior: auto;
        }

        .form-container {
            max-width: 640px;
            margin: 0 auto;
            padding: 40px 24px;
        }

        .form-heading {
            font-family: var(--font-serif);
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--foreground);
            margin-bottom: 8px;
        }

        .form-subheading {
            color: var(--foreground-light);
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--foreground);
        }

        .form-label .required {
            color: var(--color-orange);
            font-size: 0.85em;
            margin-left: 4px;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.6);
            font-family: var(--font-serif);
            font-size: 1rem;
            color: var(--foreground);
            transition: border-color 0.3s ease;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--color-teal);
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-radio-group {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .form-radio-label {
            display: flex;
            align-items: center;
            padding: 10px 16px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .form-radio-label:hover {
            border-color: var(--color-teal);
        }

        .form-radio-label input {
            margin-right: 8px;
        }

        .form-radio-label.selected {
            background: var(--color-teal);
            border-color: var(--color-teal);
            color: #fff;
        }

        .form-error {
            color: var(--color-orange);
            font-size: 0.85rem;
            margin-top: 4px;
        }

        .form-submit {
            width: 100%;
            padding: 18px 40px;
            background: var(--color-orange);
            color: #fff;
            font-family: var(--font-serif);
            font-size: 1rem;
            font-weight: 500;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
            margin-top: 32px;
        }

        .form-submit:hover {
            background: var(--color-orange-dark);
            transform: translateY(-1px);
        }

        .form-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .conditional-field {
            display: none;
            margin-top: 12px;
            padding-left: 16px;
            border-left: 2px solid var(--color-teal);
        }

        .conditional-field.show {
            display: block;
        }

        .alert-error {
            background: rgba(255, 107, 53, 0.1);
            border: 1px solid var(--color-orange);
            padding: 16px;
            margin-bottom: 24px;
            border-radius: 8px;
        }

        .alert-error ul {
            margin: 0;
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <div class="mesh-bg"></div>
    <div class="noise-overlay"></div>

    <main style="position: relative; z-index: 2;">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
