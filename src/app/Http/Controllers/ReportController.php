<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportFilterRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;


class ReportController extends Controller
{
    use AuthorizesRequests;

    public function monthly(ReportFilterRequest $request)
    {
        $this->authorize('view-report');

        $month = $request->input('month', now()->format('Y-m'));
        $userId = $request->input('user_id');
        $clientId = $request->input('client_id');

        $from = $month.'-01';
        $to = date('Y-m-t', strtotime($from));

        $clients = $this->availableClients();
        $query = \App\Models\TimeEntry::query()
            ->with(['user', 'task.project.client'])
            ->whereBetween('work_date', [$from, $to]);

        $authUser = Auth::user();
        if ($authUser && $authUser->role === 'member') {
            $query->whereHas('task.project', function ($q) use ($clients) {
                $q->whereIn('client_id', $clients->pluck('id'));
            });
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }
        if ($clientId) {
            $query->whereHas('task.project', function ($q) use ($clientId) {
                $q->where('client_id', $clientId);
            });
        }

        $entries = $query->get();

        $rows = $entries
            ->groupBy(function($entry) {
                return $entry->user_id.'-'.$entry->task->project_id;
            })
            ->map(function($group) {
                $first = $group->first();
                $totalCost = $group->sum(function ($entry) {
                    return $this->entryCost($entry);
                });

                return [
                    'user_name' => $first->user->name ?? '',
                    'client_name' => $first->task->project->client->company_name ?? '',
                    'project_code' => $first->task->project->code ?? '',
                    'project_name' => $first->task->project->name ?? '',
                    'total_hours' => $group->sum('hours'),
                    'total_cost' => $totalCost,
                ];
            })->values();

        $projectTotals = $rows->groupBy('project_name')->map(function ($group) {
            return [
                'project_name' => $group->first()['project_name'],
                'project_code' => $group->first()['project_code'],
                'total_hours' => $group->sum('total_hours'),
                'total_cost' => $group->sum('total_cost'),
            ];
        })->values();

        if ($request->input('export') === 'csv') {
            $this->authorize('export-report');
            return $this->exportMonthlyCsv($rows, $month);
        }

        $users = \App\Models\User::orderBy('name')->get();

        return view('reports.monthly', [
            'reportRows' => $rows,
            'projectTotals' => $projectTotals,
            'users' => $users,
            'clients' => $clients,
            'selectedMonth' => $month,
            'selectedUserId' => $userId,
            'selectedClientId' => $clientId,
        ]);
    }

    public function project(ReportFilterRequest $request)
    {
        $this->authorize('view-report');

        $month = $request->input('month');
        $projectCode = $request->input('project_code');

        $query = \App\Models\Project::query()->with(['client', 'tasks.timeEntries.user']);

        if ($projectCode) {
            $query->where('code', 'like', '%' . $projectCode . '%');
        }

        $rows = $query->get()->map(function ($project) use ($month) {
            $entries = $project->tasks->flatMap->timeEntries;
            if ($month) {
                $from = $month . '-01';
                $to = date('Y-m-t', strtotime($from));
                $entries = $entries->filter(fn ($entry) => $entry->work_date >= $from && $entry->work_date <= $to);
            }

            $hours = (float) $entries->sum('hours');
            $cost = $entries->sum(fn ($entry) => $this->entryCost($entry));
            $budget = (float) ($project->budget ?? 0);
            $balance = $budget - $cost;

            return [
                'project_code' => $project->code,
                'project_name' => $project->name,
                'budget' => $budget,
                'actual_hours' => round($hours, 2),
                'cost' => round($cost, 2),
                'balance' => round($balance, 2),
                'burn_rate' => $budget > 0 ? round(($cost / $budget) * 100, 1) : 0,
            ];
        });

        if ($request->input('export') === 'csv') {
            $this->authorize('export-report');
            return $this->exportProjectCsv($rows, $month);
        }

        return view('reports.project', [
            'reportRows' => $rows,
            'selectedMonth' => $month,
            'selectedProjectCode' => $projectCode,
        ]);
    }

    public function member(ReportFilterRequest $request)
    {
        $this->authorize('view-report');

        $month = $request->input('month', now()->format('Y-m'));
        $from = $month . '-01';
        $to = date('Y-m-t', strtotime($from));
        $businessDays = now()->createFromFormat('Y-m', $month)->daysInMonth;
        $standardHours = $businessDays * 8;

        $users = \App\Models\User::where('is_active', true)->orderBy('name')->get();

        $rows = $users->map(function ($user) use ($from, $to, $standardHours) {
            $hours = (float) \App\Models\TimeEntry::where('user_id', $user->id)
                ->whereBetween('work_date', [$from, $to])
                ->sum('hours');

            return [
                'user_name' => $user->name,
                'worked_hours' => round($hours, 2),
                'utilization_rate' => $standardHours > 0 ? round(($hours / $standardHours) * 100, 1) : 0,
            ];
        });

        if ($request->input('export') === 'csv') {
            $this->authorize('export-report');
            return $this->exportMemberCsv($rows, $month);
        }

        return view('reports.member', [
            'reportRows' => $rows,
            'selectedMonth' => $month,
        ]);
    }

    public function export(ReportFilterRequest $request)
    {
        $this->authorize('export-report');
        return redirect()->route('reports.monthly', ['export' => 'csv'] + $request->all());
    }

    private function entryCost($entry): float
    {
        $hours = (float) ($entry->hours ?? 0);
        $user = $entry->user;

        if (!$user) {
            return 0.0;
        }

        if (($user->rate_type ?? 'hourly') === 'daily') {
            $dailyRate = (float) ($user->daily_rate ?? 0);
            return round(($dailyRate / 8) * $hours, 2);
        }

        $hourlyRate = (float) ($user->hourly_rate ?? 0);
        return round($hourlyRate * $hours, 2);
    }

    private function availableClients()
    {
        $user = Auth::user();
        if ($user && $user->role === 'member') {
            return $user->clients()->where('is_active', true)->orderBy('company_name')->get();
        }

        return \App\Models\Client::where('is_active', true)->orderBy('company_name')->get();
    }

    private function exportMonthlyCsv($rows, string $month)
    {
        $filename = 'monthly_cost_report_' . $month . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['メンバー', 'クライアント', '案件コード', '案件名', '合計工数', '原価(円)']);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row['user_name'],
                    $row['client_name'],
                    $row['project_code'],
                    $row['project_name'],
                    number_format((float)$row['total_hours'], 2, '.', ''),
                    number_format((float)$row['total_cost'], 2, '.', ''),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function exportProjectCsv($rows, ?string $month)
    {
        $filename = 'project_report_' . ($month ?: now()->format('Y-m')) . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['案件コード', '案件名', '予算', '実績工数', '原価', '消化率(%)', '収支']);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row['project_code'],
                    $row['project_name'],
                    number_format((float)$row['budget'], 2, '.', ''),
                    number_format((float)$row['actual_hours'], 2, '.', ''),
                    number_format((float)$row['cost'], 2, '.', ''),
                    number_format((float)$row['burn_rate'], 1, '.', ''),
                    number_format((float)$row['balance'], 2, '.', ''),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function exportMemberCsv($rows, string $month)
    {
        $filename = 'member_report_' . $month . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['メンバー', '稼働時間', '稼働率(%)']);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row['user_name'],
                    number_format((float)$row['worked_hours'], 2, '.', ''),
                    number_format((float)$row['utilization_rate'], 1, '.', ''),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
