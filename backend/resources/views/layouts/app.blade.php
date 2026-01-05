<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'ライフバランス診断 | Save My 12 Weeks')</title>
    <meta name="description" content="@yield('description', '今の自分を8つの視点で見える化する。世界のライフコーチングの現場で使われている診断ツール。')">

    <!-- OGP -->
    <meta property="og:title" content="@yield('og_title', 'ライフバランス診断 | Save My 12 Weeks')">
    <meta property="og:description" content="@yield('og_description', '今の自分を8つの視点で見える化する')">
    <meta property="og:type" content="website">
    <meta property="og:image" content="@yield('og_image', '')">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'ライフバランス診断 | Save My 12 Weeks')">
    <meta name="twitter:description" content="@yield('twitter_description', '今の自分を8つの視点で見える化する')">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Noto+Sans+JP:wght@400;500;600;700&family=Noto+Serif+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
</head>
<body>
    <div id="app">
        @yield('content')
    </div>
</body>
</html>
