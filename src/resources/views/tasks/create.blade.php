@extends('layouts.app')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">タスク新規登録</h1>
    <form method="POST" action="{{ route('tasks.store') }}" class="space-y-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @csrf
        <div>
            <label>案件</label>
            <select name="project_id" class="border rounded px-2 py-1 w-full" required>
                <option value="">選択してください</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>タスク名</label>
            <input type="text" name="title" class="border rounded px-2 py-1 w-full" required placeholder="例：要件定義書作成">
        </div>
        <div>
            <label>説明</label>
            <textarea name="description" class="border rounded px-2 py-1 w-full" placeholder="作業内容や注意事項"></textarea>
        </div>
        <div>
            <label>ステータス</label>
            <select name="status" class="border rounded px-2 py-1 w-full">
                <option value="not_started">未着手</option>
                <option value="in_progress">進行中</option>
                <option value="in_review">レビュー待ち</option>
                <option value="completed">完了</option>
                <option value="on_hold">保留</option>
            </select>
        </div>
        <div>
            <label>優先度</label>
            <select name="priority" class="border rounded px-2 py-1 w-full">
                <option value="low">低</option>
                <option value="medium">中</option>
                <option value="high">高</option>
            </select>
        </div>
        <div>
            <label>見積工数（時間）</label>
            <input type="number" name="estimated_hours" class="border rounded px-2 py-1 w-full" step="0.01" placeholder="例：8">
        </div>
        <div>
            <label>開始日</label>
            <input type="date" name="start_date" class="border rounded px-2 py-1 w-full">
        </div>
        <div>
            <label>期限</label>
            <input type="date" name="due_date" class="border rounded px-2 py-1 w-full">
        </div>
        <div>
            <label>表示順</label>
            <input type="number" name="sort_order" class="border rounded px-2 py-1 w-full">
        </div>
        <div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">登録</button>
            <a href="{{ route('tasks.index') }}" class="ml-4 text-gray-600 underline">戻る</a>
        </div>
    </form>
</div>
@endsection
