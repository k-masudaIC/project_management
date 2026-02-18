@extends('layouts.app')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">タスク編集</h1>
    <form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label>案件</label>
            <select name="project_id" class="border rounded px-2 py-1 w-full" required>
                <option value="">選択してください</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" @if($task->project_id == $project->id) selected @endif>{{ $project->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>タスク名</label>
            <input type="text" name="title" class="border rounded px-2 py-1 w-full" value="{{ old('title', $task->title) }}" required>
        </div>
        <div>
            <label>説明</label>
            <textarea name="description" class="border rounded px-2 py-1 w-full">{{ old('description', $task->description) }}</textarea>
        </div>
        <div>
            <label>ステータス</label>
            <select name="status" class="border rounded px-2 py-1 w-full">
                <option value="not_started" @if($task->status=='not_started') selected @endif>未着手</option>
                <option value="in_progress" @if($task->status=='in_progress') selected @endif>進行中</option>
                <option value="in_review" @if($task->status=='in_review') selected @endif>レビュー待ち</option>
                <option value="completed" @if($task->status=='completed') selected @endif>完了</option>
                <option value="on_hold" @if($task->status=='on_hold') selected @endif>保留</option>
            </select>
        </div>
        <div>
            <label>優先度</label>
            <select name="priority" class="border rounded px-2 py-1 w-full">
                <option value="low" @if($task->priority=='low') selected @endif>低</option>
                <option value="medium" @if($task->priority=='medium') selected @endif>中</option>
                <option value="high" @if($task->priority=='high') selected @endif>高</option>
            </select>
        </div>
        <div>
            <label>見積工数（時間）</label>
            <input type="number" name="estimated_hours" class="border rounded px-2 py-1 w-full" step="0.01" value="{{ old('estimated_hours', $task->estimated_hours) }}">
        </div>
        <div>
            <label>開始日</label>
            <input type="date" name="start_date" class="border rounded px-2 py-1 w-full" value="{{ old('start_date', $task->start_date) }}">
        </div>
        <div>
            <label>期限</label>
            <input type="date" name="due_date" class="border rounded px-2 py-1 w-full" value="{{ old('due_date', $task->due_date) }}">
        </div>
        <div>
            <label>表示順</label>
            <input type="number" name="sort_order" class="border rounded px-2 py-1 w-full" value="{{ old('sort_order', $task->sort_order) }}">
        </div>
        <div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">更新</button>
            <a href="{{ route('tasks.index') }}" class="ml-4 text-gray-600 underline">戻る</a>
        </div>
    </form>
</div>
@endsection
