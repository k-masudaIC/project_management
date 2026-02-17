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
                <option value="admin" @if($user->role=='admin') selected @endif>管理者</option>
                <option value="pm" @if($user->role=='pm') selected @endif>PM</option>
                <option value="member" @if($user->role=='member') selected @endif>メンバー</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block">時間単価</label>
            <input type="number" name="hourly_rate" class="border rounded w-full p-2" value="{{ old('hourly_rate', $user->hourly_rate) }}">
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
