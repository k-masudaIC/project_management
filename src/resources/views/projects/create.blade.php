@extends('layouts.app')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">案件新規登録</h1>
    <form method="POST" action="{{ route('projects.store') }}" class="space-y-4">
        @csrf
        <div>
            <label>案件名</label>
            <input type="text" name="name" class="border rounded px-2 py-1 w-full" required>
        </div>
        <div>
            <label>案件コード</label>
            <input type="text" name="code" class="border rounded px-2 py-1 w-full">
        </div>
        <div>
            <label>クライアント</label>
            <select name="client_id" class="border rounded px-2 py-1 w-full" required>
                <option value="">選択してください</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->company_name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>ステータス</label>
            <select name="status" class="border rounded px-2 py-1 w-full">
                <option value="proposal">提案中</option>
                <option value="in_progress">進行中</option>
                <option value="on_hold">保留</option>
                <option value="completed">完了</option>
                <option value="cancelled">キャンセル</option>
            </select>
        </div>
        <div>
            <label>予算（円）</label>
            <input type="number" name="budget" class="border rounded px-2 py-1 w-full" step="0.01">
        </div>
        <div>
            <label>見積工数（時間）</label>
            <input type="number" name="estimated_hours" class="border rounded px-2 py-1 w-full" step="0.01">
        </div>
        <div>
            <label>開始日</label>
            <input type="date" name="start_date" class="border rounded px-2 py-1 w-full">
        </div>
        <div>
            <label>納期</label>
            <input type="date" name="end_date" class="border rounded px-2 py-1 w-full">
        </div>
        <div>
            <label>説明</label>
            <textarea name="description" class="border rounded px-2 py-1 w-full"></textarea>
        </div>
        <div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">登録</button>
            <a href="{{ route('projects.index') }}" class="ml-4 text-gray-600 underline">戻る</a>
        </div>
    </form>
</div>
@endsection
