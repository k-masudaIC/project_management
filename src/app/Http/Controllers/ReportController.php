<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportFilterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class ReportController extends Controller
{
    use AuthorizesRequests;
    public function monthly(ReportFilterRequest $request)
    {
        $this->authorize('view-report');

        $month = $request->input('month', now()->format('Y-m'));
        $userId = $request->input('user_id');

        $from = $month.'-01';
        $to = date('Y-m-t', strtotime($from));

        $query = \App\Models\TimeEntry::query()
            ->with(['user', 'task.project'])
            ->whereBetween('work_date', [$from, $to]);
        if ($userId) {
            $query->where('user_id', $userId);
        }

        $rows = $query->get()
            ->groupBy(function($entry) {
                return $entry->user_id.'-'.$entry->task->project_id;
            })
            ->map(function($group) {
                $first = $group->first();
                return [
                    'user_name' => $first->user->name ?? '',
                    'project_code' => $first->task->project->code ?? '',
                    'project_name' => $first->task->project->name ?? '',
                    'total_hours' => $group->sum('hours'),
                ];
            })->values();

        $users = \App\Models\User::orderBy('name')->get();

        return view('reports.monthly', [
            'reportRows' => $rows,
            'users' => $users,
            'selectedMonth' => $month,
            'selectedUserId' => $userId,
        ]);
    }

    public function project(ReportFilterRequest $request)
    {
        $this->authorize('view-report');
        // 案件別収支レポートロジック
        return view('reports.project');
    }

    public function member(ReportFilterRequest $request)
    {
        $this->authorize('view-report');
        // メンバー別稼働率レポートロジック
        return view('reports.member');
    }

    public function export(ReportFilterRequest $request)
    {
        $this->authorize('export-report');
        // CSV/PDFエクスポートロジック
        // return Excel::download(new ReportExport($request->all()), 'report.csv');
        return response()->json(['message' => 'エクスポート機能は未実装です']);
    }
}
