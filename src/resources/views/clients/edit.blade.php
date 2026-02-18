@extends('layouts.app')

@section('title', 'クライアント編集')

@section('content')
<h1 class="text-2xl font-bold mb-6">クライアント編集</h1>
<form action="{{ route('clients.update', $client) }}" method="POST" class="space-y-4 max-w-lg">
    @csrf
    @method('PUT')
    <div>
        <label class="block font-semibold">会社名 <span class="text-red-500">*</span></label>
        <input type="text" name="company_name" value="{{ old('company_name', $client->company_name) }}" class="w-full border rounded px-3 py-2" required>
        @error('company_name')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block font-semibold">担当者名 <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $client->name) }}" class="w-full border rounded px-3 py-2" required>
        @error('name')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block font-semibold">メール</label>
        <input type="email" name="email" value="{{ old('email', $client->email) }}" class="w-full border rounded px-3 py-2">
        @error('email')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block font-semibold">電話</label>
        <input type="text" name="phone" value="{{ old('phone', $client->phone) }}" class="w-full border rounded px-3 py-2">
        @error('phone')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block font-semibold">住所</label>
        <textarea name="address" class="w-full border rounded px-3 py-2">{{ old('address', $client->address) }}</textarea>
        @error('address')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block font-semibold">メモ</label>
        <textarea name="notes" class="w-full border rounded px-3 py-2">{{ old('notes', $client->notes) }}</textarea>
        @error('notes')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="inline-flex items-center">
            <input type="checkbox" name="is_active" value="1" class="mr-2" {{ old('is_active', $client->is_active) ? 'checked' : '' }}>
            有効
        </label>
    </div>
    <div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">更新</button>
        <a href="{{ route('clients.index') }}" class="ml-4 text-gray-600 hover:underline">戻る</a>
    </div>
</form>
@endsection
