@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">ユーザー編集</h1>
    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block">名前</label>
            <input type="text" name="name" class="border rounded w-full p-2" value="{{ old('name', $user->name) }}">
        </div>
        <div class="mb-4">
            <label class="block">メール</label>
            <input type="email" name="email" class="border rounded w-full p-2" value="{{ old('email', $user->email) }}">
        </div>
        <div class="mb-4">
            <label class="block">パスワード（変更時のみ入力）</label>
            <input type="password" name="password" class="border rounded w-full p-2">
        </div>
        <div class="mb-4">
            <label class="block">パスワード（確認）</label>
            <input type="password" name="password_confirmation" class="border rounded w-full p-2">
        </div>
        <div class="mb-4">
            <label class="block">役割</label>
            <select name="role" class="border rounded w-full p-2">
                <option value="admin" @selected(old('role', $user->role) === 'admin')>管理者</option>
                <option value="pm" @selected(old('role', $user->role) === 'pm')>PM</option>
                <option value="member" @selected(old('role', $user->role) === 'member')>メンバー</option>
                <option value="contractor" @selected(old('role', $user->role) === 'contractor')>業務委託</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block">単価種別</label>
            <select name="rate_type" class="border rounded w-full p-2">
                <option value="hourly" @selected(old('rate_type', $user->rate_type ?? 'hourly') === 'hourly')>時給</option>
                <option value="daily" @selected(old('rate_type', $user->rate_type) === 'daily')>日給</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block">時給（円）</label>
            <input type="number" step="0.01" name="hourly_rate" class="border rounded w-full p-2" value="{{ old('hourly_rate', $user->hourly_rate) }}">
        </div>
        <div class="mb-4">
            <label class="block">日給（円）</label>
            <input type="number" step="0.01" name="daily_rate" class="border rounded w-full p-2" value="{{ old('daily_rate', $user->daily_rate) }}">
        </div>
        <div class="mb-4">
            <label class="block">担当クライアント</label>
            @php
                $selectedClientIds = collect(old('client_ids', $user->clients->pluck('id')->all()));
            @endphp
            <select name="client_ids[]" class="border rounded w-full p-2" multiple size="6">
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" @selected($selectedClientIds->contains($client->id))>{{ $client->company_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block">有効</label>
            <input type="checkbox" name="is_active" value="1" @if($user->is_active) checked @endif>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">更新</button>
        <a href="{{ route('users.index') }}" class="ml-4 text-gray-600">戻る</a>
    </form>
</div>
@endsection
