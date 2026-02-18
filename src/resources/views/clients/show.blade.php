@extends('layouts.app')

@section('title', 'クライアント詳細')

@section('content')
<h1 class="text-2xl font-bold mb-6">クライアント詳細</h1>
<div class="bg-white rounded shadow p-6 max-w-lg">
    <div class="mb-4"><span class="font-semibold">会社名:</span> {{ $client->company_name }}</div>
    <div class="mb-4"><span class="font-semibold">担当者名:</span> {{ $client->name }}</div>
    <div class="mb-4"><span class="font-semibold">メール:</span> {{ $client->email }}</div>
    <div class="mb-4"><span class="font-semibold">電話:</span> {{ $client->phone }}</div>
    <div class="mb-4"><span class="font-semibold">住所:</span> {{ $client->address }}</div>
    <div class="mb-4"><span class="font-semibold">メモ:</span> {{ $client->notes }}</div>
    <div class="mb-4"><span class="font-semibold">状態:</span> {{ $client->is_active ? '有効' : '無効' }}</div>
    <div class="flex space-x-4 mt-6">
        @can('update', $client)
            <a href="{{ route('clients.edit', $client) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">編集</a>
        @endcan
        <a href="{{ route('clients.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">一覧へ戻る</a>
    </div>
</div>
@endsection
