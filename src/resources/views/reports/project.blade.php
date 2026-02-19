@extends('layouts.app')
@section('title', '案件別収支レポート')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">案件別収支レポート</h1>
    <form method="GET" class="mb-4 flex gap-2">
        <input type="text" name="project_code" value="{{ request('project_code') }}" class="border rounded px-2 py-1" placeholder="案件コード">
        <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded">検索</button>
    </form>
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border px-2 py-1">案件コード</th>
                <th class="border px-2 py-1">案件名</th>
                <th class="border px-2 py-1">予算</th>
                <th class="border px-2 py-1">実績工数</th>
                <th class="border px-2 py-1">消化率</th>
                <th class="border px-2 py-1">収支</th>
            </tr>
        </thead>
        <tbody>
            {{-- @foreach($reportRows as $row) --}}
            <tr>
                <td class="border px-2 py-1">PRJ-2024-001</td>
                <td class="border px-2 py-1">Webサイト制作</td>
                <td class="border px-2 py-1 text-right">1,000,000</td>
                <td class="border px-2 py-1 text-right">120.5</td>
                <td class="border px-2 py-1 text-right">80%</td>
                <td class="border px-2 py-1 text-right">+200,000</td>
            </tr>
            {{-- @endforeach --}}
        </tbody>
    </table>
    <div class="mt-4">
        <a href="?export=csv" class="bg-green-500 text-white px-4 py-1 rounded">CSVエクスポート</a>
        <a href="?export=pdf" class="bg-gray-700 text-white px-4 py-1 rounded ml-2">PDFエクスポート</a>
    </div>
</div>
@endsection
