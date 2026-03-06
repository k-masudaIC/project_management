@extends('layouts.app')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">タスク詳細</h1>
    <div class="bg-white p-4 rounded shadow">
        <form class="space-y-4">
            <div>
                <label>タスクID</label>
                <input type="text" class="border rounded px-2 py-1 w-full bg-gray-100" value="{{ $task->id }}" readonly>
            </div>
            <div>
                <label>案件</label>
                <input type="text" class="border rounded px-2 py-1 w-full bg-gray-100" value="{{ $task->project->name ?? '' }}" readonly>
            </div>
            <div>
                <label>タスク名</label>
                <input type="text" class="border rounded px-2 py-1 w-full bg-gray-100" value="{{ $task->title }}" readonly>
            </div>
            <div>
                <label>説明</label>
                <textarea class="border rounded px-2 py-1 w-full bg-gray-100" readonly>{{ $task->description }}</textarea>
            </div>
            <div>
                <label>ステータス</label>
                <input type="text" class="border rounded px-2 py-1 w-full bg-gray-100" value="{{ $task->status_label }}" readonly>
            </div>
            <div>
                <label>優先度</label>
                <input type="text" class="border rounded px-2 py-1 w-full bg-gray-100" value="{{ $task->priority_label }}" readonly>
            </div>
            <div>
                <label>見積工数（時間）</label>
                <input type="number" class="border rounded px-2 py-1 w-full bg-gray-100" value="{{ $task->estimated_hours }}" readonly>
            </div>
            <div>
                <label>開始日</label>
                <input type="date" class="border rounded px-2 py-1 w-full bg-gray-100" value="{{ $task->start_date }}" readonly>
            </div>
            <div>
                <label>期限</label>
                <input type="date" class="border rounded px-2 py-1 w-full bg-gray-100" value="{{ $task->due_date }}" readonly>
            </div>
            <div>
                <label>担当者</label>
                <ul class="list-disc pl-5">
                    @foreach($task->assignments as $assignment)
                        <li>{{ $assignment->user->name }}</li>
                    @endforeach
                </ul>
            </div>
            <div>
                <a href="{{ route('tasks.edit', $task) }}" class="bg-green-500 text-white px-4 py-1 rounded">編集</a>
                <a href="{{ route('tasks.index') }}" class="ml-4 text-gray-600 underline">一覧へ戻る</a>
            </div>
        </form>
    </div>
</div>
@endsection
