<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '案件管理システム')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- ヘッダー -->
    <header>
        @include('components.header')
    </header>
    <div class="flex flex-1">
        <!-- サイドバー -->
        <aside class="w-64 bg-white shadow-md">
            @include('components.sidebar')
        </aside>
        <!-- メイン -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>
    <!-- フッター -->
    <footer class="bg-white border-t py-4 text-center text-sm text-gray-500">
        @include('components.footer')
    </footer>
</body>
@stack('scripts')
</html>
