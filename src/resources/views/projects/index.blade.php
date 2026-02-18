@extends('layouts.app')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">案件一覧</h1>
    <form method="GET" class="mb-4 flex gap-4">
        <select name="status" class="border rounded px-2 py-1">
            <option value="">ステータス</option>
            <option value="proposal">提案中</option>
            <option value="in_progress">進行中</option>
            <option value="on_hold">保留</option>
            <option value="completed">完了</option>
            <option value="cancelled">キャンセル</option>
        </select>
        <select name="client_id" class="border rounded px-2 py-1">
            <option value="">クライアント</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}">{{ $client->company_name }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded">絞り込み</button>
        <a href="{{ route('projects.create') }}" class="ml-auto bg-green-500 text-white px-4 py-1 rounded">新規登録</a>
    </form>
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border px-2 py-1">案件コード</th>
                <th class="border px-2 py-1">案件名</th>
                <th class="border px-2 py-1">クライアント名</th>
                <th class="border px-2 py-1">ステータス</th>
                <th class="border px-2 py-1">予算</th>
                <th class="border px-2 py-1">納期</th>
                <th class="border px-2 py-1">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $project)
            <tr>
                <td class="border px-2 py-1">{{ $project->code }}</td>
                <td class="border px-2 py-1">{{ $project->name }}</td>
                <td class="border px-2 py-1">{{ $project->client->company_name ?? '' }}</td>
                <td class="border px-2 py-1">{{ $project->status }}</td>
                <td class="border px-2 py-1 text-right">{{ number_format($project->budget) }}</td>
                <td class="border px-2 py-1">{{ $project->end_date }}</td>
                <td class="border px-2 py-1">
                    <a href="{{ route('projects.show', $project) }}" class="text-blue-600 underline">詳細</a>
                    <a href="{{ route('projects.edit', $project) }}" class="text-green-600 underline ml-2">編集</a>
                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 underline ml-2" onclick="return confirm('削除しますか？')">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $projects->links() }}</div>
</div>
@endsection
