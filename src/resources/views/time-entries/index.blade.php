@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">工数記録一覧</h1>
    <div class="flex items-center mb-4 gap-2">
        <a href="{{ route('time-entries.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded inline-block">新規登録</a>
        <a href="{{ route('time-entries.daily') }}" class="bg-yellow-500 text-white px-4 py-2 rounded inline-block">日次入力</a>
        <form method="GET" action="" class="flex gap-2 items-center ml-auto">
            <input type="date" name="work_date" value="{{ request('work_date') }}" class="border rounded px-2 py-1" placeholder="日付">
            <input type="text" name="user_id" value="{{ request('user_id') }}" class="border rounded px-2 py-1" placeholder="ユーザーID">
            <input type="text" name="task_id" value="{{ request('task_id') }}" class="border rounded px-2 py-1" placeholder="タスクID">
            <button type="submit" class="bg-gray-400 text-white px-2 py-1 rounded">絞り込み</button>
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
