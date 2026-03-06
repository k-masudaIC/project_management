@extends('layouts.app')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">タスク一覧</h1>
    <form method="GET" class="mb-4 flex gap-4">
        <select name="status" class="border rounded px-2 py-1">
            <option value="">ステータス</option>
            <option value="not_started">未着手</option>
            <option value="in_progress">進行中</option>
            <option value="in_review">レビュー待ち</option>
            <option value="completed">完了</option>
            <option value="on_hold">保留</option>
        </select>
        <select name="project_id" class="border rounded px-2 py-1">
            <option value="">案件</option>
            @foreach($projects as $project)
                <option value="{{ $project->id }}">{{ $project->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded">絞り込み</button>
        @auth
        <a href="{{ route('tasks.create') }}" class="ml-auto bg-green-500 text-white px-4 py-1 rounded">新規登録</a>
        @endauth
    </form>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="border px-2 py-1">ID</th>
                    <th class="border px-2 py-1">案件</th>
                    <th class="border px-2 py-1">タスク名</th>
                    <th class="border px-2 py-1">ステータス</th>
                    <th class="border px-2 py-1">優先度</th>
                    <th class="border px-2 py-1">見積工数</th>
                    <th class="border px-2 py-1">期限</th>
                    <th class="border px-2 py-1">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                <tr>
                    <td class="border px-2 py-1 text-right">{{ $task->id }}</td>
                    <td class="border px-2 py-1">{{ $task->project->name ?? '' }}</td>
                    <td class="border px-2 py-1">{{ $task->title }}</td>
                    <td class="border px-2 py-1">{{ $task->status_label }}</td>
                    <td class="border px-2 py-1">{{ $task->priority_label }}</td>
                    <td class="border px-2 py-1 text-right">{{ $task->estimated_hours }}</td>
                    <td class="border px-2 py-1">{{ $task->due_date }}</td>
                    <td class="border px-2 py-1">
                        <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 underline">詳細</a>
                        @auth
                        <a href="{{ route('tasks.edit', $task) }}" class="text-green-600 underline ml-2">編集</a>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 underline ml-2" onclick="return confirm('削除しますか？')">削除</button>
                        </form>
                        @endauth
                    </td>
                </tr>
                @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $tasks->links() }}</div>
</div>
@endsection
