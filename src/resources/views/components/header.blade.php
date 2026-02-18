<div class="flex items-center justify-between px-6 py-4 bg-white border-b">
    <div class="text-xl font-bold text-gray-800">案件管理システム</div>
    <div class="flex items-center space-x-4">
        <span class="text-gray-700">{{ Auth::user()->name ?? 'ゲスト' }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-blue-600 hover:underline">ログアウト</button>
        </form>
    </div>
</div>
