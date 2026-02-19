                        @extends('layouts.app')

                        @section('content')
                        <div class="mb-6">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                {{ __('ダッシュボード') }}
                            </h2>
                        </div>
                        <div class="py-12">
                            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                                <!-- サマリー -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="bg-white p-6 rounded shadow">
                                        <div class="text-gray-500">今週の工数</div>
                                        <div class="text-2xl font-bold">{{ $weeklyHours ?? 0 }} 時間</div>
                                    </div>
                                    <div class="bg-white p-6 rounded shadow">
                                        <div class="text-gray-500">担当タスク数</div>
                                        <div class="text-2xl font-bold">{{ $tasks->count() ?? 0 }} 件</div>
                                    </div>
                                    <div class="bg-white p-6 rounded shadow">
                                        <div class="text-gray-500">期限が迫っているタスク</div>
                                        <div class="text-2xl font-bold">{{ $upcomingTasks->count() ?? 0 }} 件</div>
                                    </div>
                                </div>

                                <!-- 期限アラート（3日以内） -->
                                <div class="bg-red-50 p-6 rounded shadow border border-red-200">
                                    <h3 class="text-lg font-semibold mb-4 text-red-700">期限アラート（3日以内のタスク）</h3>
                                    <table class="min-w-full text-sm">
                                        <thead>
                                            <tr>
                                                <th class="px-2 py-1">案件名</th>
                                                <th class="px-2 py-1">タスク名</th>
                                                <th class="px-2 py-1">期限</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($alertTasks as $task)
                                            <tr>
                                                <td class="border px-2 py-1">{{ $task->project->name ?? '-' }}</td>
                                                <td class="border px-2 py-1">{{ $task->title }}</td>
                                                <td class="border px-2 py-1 text-red-600 font-bold">{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '-' }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-gray-400">期限が迫っているタスクはありません</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- メンバー別稼働グラフ -->
                                <div class="bg-white p-6 rounded shadow">
                                    <h3 class="text-lg font-semibold mb-4">メンバー別稼働グラフ（今週）</h3>
                                    <canvas id="memberWorkloadChart" height="100"></canvas>
                                    @push('scripts')
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const ctxMember = document.getElementById('memberWorkloadChart');
                                            if (ctxMember && window.Chart) {
                                                const data = {
                                                    labels: @json($memberWorkload['labels']),
                                                    datasets: [{
                                                        label: '工数（時間）',
                                                        data: @json($memberWorkload['data']),
                                                        backgroundColor: 'rgba(16, 185, 129, 0.5)',
                                                        borderColor: 'rgba(16, 185, 129, 1)',
                                                        borderWidth: 1
                                                    }]
                                                };
                                                new window.Chart(ctxMember, {
                                                    type: 'bar',
                                                    data: data,
                                                    options: {
                                                        responsive: true,
                                                        plugins: {
                                                            legend: {
                                                                display: false
                                                            },
                                                            title: {
                                                                display: false
                                                            }
                                                        },
                                                        scales: {
                                                            y: {
                                                                beginAtZero: true,
                                                                max: 40
                                                            }
                                                        }
                                                    }
                                                });
                                            }
                                        });
                                    </script>
                                    @endpush
                                </div>

                                <!-- 担当タスク一覧 -->
                                <div class="bg-white p-6 rounded shadow">
                                    <h3 class="text-lg font-semibold mb-4">担当タスク一覧</h3>
                                    <table class="min-w-full text-sm">
                                        <thead>
                                            <tr>
                                                <th class="px-2 py-1">案件名</th>
                                                <th class="px-2 py-1">タスク名</th>
                                                <th class="px-2 py-1">ステータス</th>
                                                <th class="px-2 py-1">期限</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($tasks as $task)
                                            <tr>
                                                <td class="border px-2 py-1">{{ $task->project->name ?? '-' }}</td>
                                                <td class="border px-2 py-1">{{ $task->title }}</td>
                                                <td class="border px-2 py-1">{{ __(ucfirst(str_replace('_', ' ', $task->status))) }}</td>
                                                <td class="border px-2 py-1">{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '-' }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-gray-400">タスクはありません</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- 期限が迫っているタスク -->
                                <div class="bg-white p-6 rounded shadow">
                                    <h3 class="text-lg font-semibold mb-4">期限が迫っているタスク</h3>
                                    <table class="min-w-full text-sm">
                                        <thead>
                                            <tr>
                                                <th class="px-2 py-1">案件名</th>
                                                <th class="px-2 py-1">タスク名</th>
                                                <th class="px-2 py-1">期限</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($upcomingTasks as $task)
                                            <tr>
                                                <td class="border px-2 py-1">{{ $task->project->name ?? '-' }}</td>
                                                <td class="border px-2 py-1">{{ $task->title }}</td>
                                                <td class="border px-2 py-1">{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '-' }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-gray-400">期限が迫っているタスクはありません</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- プロジェクト進捗率 -->
                                <div class="bg-white p-6 rounded shadow">
                                    <h3 class="text-lg font-semibold mb-4">プロジェクト進捗率</h3>
                                    <div class="mb-6">
                                        <canvas id="projectProgressChart" height="100"></canvas>
                                    </div>
                                    <table class="min-w-full text-sm">
                                        <thead>
                                            <tr>
                                                <th class="px-2 py-1">案件コード</th>
                                                <th class="px-2 py-1">案件名</th>
                                                <th class="px-2 py-1">ステータス</th>
                                                <th class="px-2 py-1">納期</th>
                                                <th class="px-2 py-1">進捗率</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($projectProgress as $project)
                                            <tr>
                                                <td class="border px-2 py-1">{{ $project['code'] }}</td>
                                                <td class="border px-2 py-1">{{ $project['name'] }}</td>
                                                <td class="border px-2 py-1">{{ __(ucfirst(str_replace('_', ' ', $project['status']))) }}</td>
                                                <td class="border px-2 py-1">{{ $project['end_date'] ? \Carbon\Carbon::parse($project['end_date'])->format('Y-m-d') : '-' }}</td>
                                                <td class="border px-2 py-1">{{ $project['progress'] }}%</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-gray-400">プロジェクトはありません</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    @push('scripts')
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const ctx = document.getElementById('projectProgressChart');
                                            if (ctx && window.Chart) {
                                                const data = {
                                                    labels: @json($projectProgress->pluck('name')),
                                                    datasets: [{
                                                        label: '進捗率(%)',
                                                        data: @json($projectProgress->pluck('progress')),
                                                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                                                        borderColor: 'rgba(59, 130, 246, 1)',
                                                        borderWidth: 1
                                                    }]
                                                };
                                                new window.Chart(ctx, {
                                                    type: 'bar',
                                                    data: data,
                                                    options: {
                                                        responsive: true,
                                                        plugins: {
                                                            legend: {
                                                                display: false
                                                            },
                                                            title: {
                                                                display: false
                                                            }
                                                        },
                                                        scales: {
                                                            y: {
                                                                beginAtZero: true,
                                                                max: 100
                                                            }
                                                        }
                                                    }
                                                });
                                            }
                                        });
                                    </script>
                                    @endpush
                                </div>
                            </div>
                        </div>
                        @endsection