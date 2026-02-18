@extends('layouts.app')

@section('title', 'クライアント一覧')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">クライアント一覧</h1>
    @can('create', App\Models\Client::class)
        <a href="{{ route('clients.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">新規登録</a>
    @endcan
</div>
@if(session('success'))
    <div class="mb-4 text-green-600">{{ session('success') }}</div>
@endif
<table class="min-w-full bg-white rounded shadow">
    <thead>
        <tr>
            <th class="px-4 py-2">会社名</th>
            <th class="px-4 py-2">担当者名</th>
            <th class="px-4 py-2">メール</th>
            <th class="px-4 py-2">電話</th>
            <th class="px-4 py-2">状態</th>
            <th class="px-4 py-2">操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clients as $client)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $client->company_name }}</td>
                <td class="px-4 py-2">{{ $client->name }}</td>
                <td class="px-4 py-2">{{ $client->email }}</td>
                <td class="px-4 py-2">{{ $client->phone }}</td>
                <td class="px-4 py-2">{{ $client->is_active ? '有効' : '無効' }}</td>
                <td class="px-4 py-2 space-x-2">
                    @can('view', $client)
                        <a href="{{ route('clients.show', $client) }}" class="text-blue-600 hover:underline">詳細</a>
                    @endcan
                    @can('update', $client)
                        <a href="{{ route('clients.edit', $client) }}" class="text-yellow-600 hover:underline">編集</a>
                    @endcan
                    @can('delete', $client)
                        <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline" onsubmit="return confirm('本当に削除しますか？');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">削除</button>
                        </form>
                    @endcan
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="mt-4">{{ $clients->links() }}</div>
@endsection
