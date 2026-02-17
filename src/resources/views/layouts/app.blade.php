<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow mb-6">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="/" class="text-xl font-bold">案件管理システム</a>
            <div>
                <a href="{{ route('users.index') }}" class="text-gray-700 hover:text-blue-500 ml-4">ユーザー管理</a>
            </div>
        </div>
    </nav>
    <main>
        @yield('content')
    </main>
</body>
</html>
