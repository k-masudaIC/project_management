@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">工数記録一覧</h1>
    <a href="{{ route('time-entries.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">新規登録</a>
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
