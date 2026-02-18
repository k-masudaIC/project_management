@extends('layouts.app')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">タスク詳細</h1>
    <div class="bg-white p-4 rounded shadow">
        <dl class="grid grid-cols-2 gap-4">
            <dt>案件</dt><dd>{{ $task->project->name ?? '' }}</dd>
            <dt>タスク名</dt><dd>{{ $task->title }}</dd>
            <dt>ステータス</dt><dd>{{ $task->status }}</dd>
            <dt>優先度</dt><dd>{{ $task->priority }}</dd>
            <dt>見積工数</dt><dd>{{ $task->estimated_hours }}</dd>
            <dt>開始日</dt><dd>{{ $task->start_date }}</dd>
            <dt>期限</dt><dd>{{ $task->due_date }}</dd>
            <dt>説明</dt><dd class="col-span-2">{{ $task->description }}</dd>
        </dl>
        <x-task-assignees :task="$task" :users="$users" />
        <div class="mt-4">
            @auth
            <a href="{{ route('tasks.edit', $task) }}" class="bg-green-500 text-white px-4 py-1 rounded">編集</a>
            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                @csrf @method('DELETE')
                <button type="submit" class="text-red-600 underline ml-2" onclick="return confirm('削除しますか？')">削除</button>
            </form>
            @endauth
            <a href="{{ route('tasks.index') }}" class="ml-4 text-gray-600 underline">一覧へ戻る</a>
        </div>
    </div>
</div>
@endsection
