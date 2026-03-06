@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">工数記録一覧</h1>
    <div class="flex items-center mb-4 gap-2">
        <a href="{{ route('time-entries.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded inline-block">新規登録</a>
        <a href="{{ route('time-entries.daily') }}" class="bg-yellow-500 text-white px-4 py-2 rounded inline-block">日次入力</a>
        <a href="{{ route('time-entries.timer') }}" class="bg-green-500 text-white px-4 py-2 rounded inline-block">タイマー入力</a>
        <form method="GET" action="" class="flex gap-2 items-center ml-auto">
            <input type="date" name="work_date" value="{{ request('work_date') }}" class="border rounded px-2 py-1" placeholder="日付">
            @php
                $users = \App\Models\User::where('is_active', true)->get();
                $userName = '';
                if(request('user_id')) {
                    $u = $users->firstWhere('id', request('user_id'));
                    if($u) $userName = $u->name;
                }
            @endphp
            <input type="text" id="user_name_input" name="user_name" value="{{ request('user_name', $userName) }}" class="border rounded px-2 py-1" placeholder="ユーザー名" list="user_name_list">
            <datalist id="user_name_list">
                @foreach($users as $user)
                    <option value="{{ $user->name }}">
                @endforeach
            </datalist>
            <input type="hidden" name="user_id" id="user_id_hidden" value="{{ request('user_id') }}">
            <input type="text" name="task_id" value="{{ request('task_id') }}" class="border rounded px-2 py-1" placeholder="タスクID">
            <button type="submit" class="bg-gray-400 text-white px-2 py-1 rounded">絞り込み</button>
            <script src="/resources/js/user_suggest.js"></script>
            <script>
            // ユーザー名→ID変換
            document.addEventListener('DOMContentLoaded', function() {
                const userInput = document.getElementById('user_name_input');
                const userIdHidden = document.getElementById('user_id_hidden');
                const userList = [
                    @foreach($users as $user)
                        {id: {{ $user->id }}, name: "{{ $user->name }}"},
                    @endforeach
                ];
                userInput && userInput.addEventListener('change', function() {
                    const found = userList.find(u => u.name === userInput.value);
                    userIdHidden.value = found ? found.id : '';
                });
            });
            </script>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="px-4 py-2">日付</th>
                    <th class="px-4 py-2">タスク</th>
                    <th class="px-4 py-2">ユーザー</th>
                    <th class="px-4 py-2">作業時間</th>
                    <th class="px-4 py-2">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($timeEntries as $entry)
                <tr>
                    <td class="border px-4 py-2">{{ $entry->work_date }}</td>
                <td class="border px-4 py-2">{{ $entry->task->title ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $entry->user->name ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $entry->hours }} h</td>
                <td class="border px-4 py-2">
                    <a href="{{ route('time-entries.show', $entry) }}" class="text-blue-600">詳細</a>
                    <a href="{{ route('time-entries.edit', $entry) }}" class="text-green-600 ml-2">編集</a>
                    <form action="{{ route('time-entries.destroy', $entry) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 ml-2" onclick="return confirm('削除しますか？')">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $timeEntries->links() }}</div>
</div>
@endsection
