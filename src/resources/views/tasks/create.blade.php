@extends('layouts.app')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">タスク新規登録</h1>
    <form method="POST" action="{{ route('tasks.store') }}" class="space-y-4">
        <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-2 rounded">
            必須項目: 案件 / タスク名 / ステータス / 優先度
        </div>
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
            <label>案件 <span class="text-red-600">*</span></label>
            <select name="project_id" class="border rounded px-2 py-1 w-full" required>
                <option value="">選択してください</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>{{ $project->name }}</option>
                @endforeach
            </select>
            @error('project_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label>タスク名 <span class="text-red-600">*</span></label>
            <input type="text" name="title" class="border rounded px-2 py-1 w-full" required placeholder="例：要件定義書作成" value="{{ old('title') }}">
            @error('title')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label>説明</label>
            <textarea name="description" class="border rounded px-2 py-1 w-full" placeholder="作業内容や注意事項">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label>ステータス <span class="text-red-600">*</span></label>
            <select name="status" class="border rounded px-2 py-1 w-full">
                <option value="not_started" @selected(old('status', 'not_started') === 'not_started')>未着手</option>
                <option value="in_progress" @selected(old('status') === 'in_progress')>進行中</option>
                <option value="in_review" @selected(old('status') === 'in_review')>レビュー待ち</option>
                <option value="completed" @selected(old('status') === 'completed')>完了</option>
                <option value="on_hold" @selected(old('status') === 'on_hold')>保留</option>
            </select>
            @error('status')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label>優先度 <span class="text-red-600">*</span></label>
            <select name="priority" class="border rounded px-2 py-1 w-full">
                <option value="low" @selected(old('priority') === 'low')>低</option>
                <option value="medium" @selected(old('priority', 'medium') === 'medium')>中</option>
                <option value="high" @selected(old('priority') === 'high')>高</option>
            </select>
            @error('priority')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label>見積工数（時間）</label>
            <input type="number" name="estimated_hours" class="border rounded px-2 py-1 w-full" step="0.01" placeholder="例：8" value="{{ old('estimated_hours') }}">
            @error('estimated_hours')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label>開始日</label>
            <input type="date" name="start_date" class="border rounded px-2 py-1 w-full" value="{{ old('start_date') }}">
            @error('start_date')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label>期限</label>
            <input type="date" name="due_date" class="border rounded px-2 py-1 w-full" value="{{ old('due_date') }}">
            @error('due_date')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label>表示順</label>
            <input type="number" name="sort_order" class="border rounded px-2 py-1 w-full" value="{{ old('sort_order', 0) }}">
            @error('sort_order')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">登録</button>
            <a href="{{ route('tasks.index') }}" class="ml-4 text-gray-600 underline">戻る</a>
        </div>
    </form>
</div>
@endsection
