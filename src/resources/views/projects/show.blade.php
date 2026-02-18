@extends('layouts.app')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">案件詳細</h1>
    <div class="bg-white p-4 rounded shadow">
        <dl class="grid grid-cols-2 gap-4">
            <dt>案件コード</dt><dd>{{ $project->code }}</dd>
            <dt>案件名</dt><dd>{{ $project->name }}</dd>
            <dt>クライアント</dt><dd>{{ $project->client->company_name ?? '' }}</dd>
            <dt>ステータス</dt><dd>{{ $project->status }}</dd>
            <dt>予算</dt><dd>{{ number_format($project->budget) }}</dd>
            <dt>見積工数</dt><dd>{{ $project->estimated_hours }}</dd>
            <dt>開始日</dt><dd>{{ $project->start_date }}</dd>
            <dt>納期</dt><dd>{{ $project->end_date }}</dd>
            <dt>説明</dt><dd class="col-span-2">{{ $project->description }}</dd>
        </dl>
        <div class="mt-4">
            <a href="{{ route('projects.edit', $project) }}" class="bg-green-500 text-white px-4 py-1 rounded">編集</a>
            <a href="{{ route('projects.index') }}" class="ml-4 text-gray-600 underline">一覧へ戻る</a>
        </div>
    </div>
</div>
@endsection
