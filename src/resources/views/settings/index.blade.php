@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-bold mb-4">設定メニュー</h1>
<ul class="list-disc pl-6 space-y-2">
    <li><a href="/users" class="text-blue-600 hover:underline">ユーザー管理</a></li>
    <li><a href="/clients" class="text-blue-600 hover:underline">クライアント管理</a></li>
    <li><a href="/profile" class="text-blue-600 hover:underline">プロフィール設定</a></li>
</ul>
@endsection
