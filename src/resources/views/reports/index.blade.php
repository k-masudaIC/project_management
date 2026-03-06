@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-bold mb-4">レポート機能一覧</h1>
<ul class="list-disc pl-6 space-y-2">
    <li><a href="/reports/monthly" class="text-blue-600 hover:underline">月次レポート</a></li>
    <li><a href="/reports/project" class="text-blue-600 hover:underline">案件別レポート</a></li>
    <li><a href="/reports/member" class="text-blue-600 hover:underline">メンバー別レポート</a></li>
    <li><a href="/reports/export" class="text-blue-600 hover:underline">CSV/PDFエクスポート</a></li>
</ul>
@endsection
