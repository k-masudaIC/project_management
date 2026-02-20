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
        <!-- モバイル用ハンバーガーメニュー -->
        <button id="sidebar-toggle" class="md:hidden absolute top-4 left-4 z-50 p-2 bg-white border rounded shadow" aria-label="メニュー">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </header>
    <div class="flex flex-1">
        <!-- サイドバー -->
        <aside id="sidebar" class="w-64 bg-white shadow-md md:static fixed top-0 left-0 h-full z-40 transition-transform duration-200 md:translate-x-0 -translate-x-full md:block hidden">
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
    <script>
        // サイドバー開閉スクリプト
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebar-toggle');
        if (sidebar && toggle) {
            toggle.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                sidebar.classList.toggle('hidden');
            });
        }
        // 画面幅変更時のサイドバー表示制御
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('-translate-x-full', 'hidden');
            } else {
                sidebar.classList.add('-translate-x-full', 'hidden');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
