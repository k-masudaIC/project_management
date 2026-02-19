<?php
// app/Services/DashboardService.php
namespace App\Services;

use App\Models\User;
use App\Models\TimeEntry;

class DashboardService
{
    /**
     * メンバー別稼働グラフ用データ取得
     * @return array
     */
    public function getMemberWorkloadSummary(): array
    {
        // 今週の範囲
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        // ユーザーごとの工数合計
        $users = User::select('id', 'name')
            ->with(['timeEntries' => function($q) use ($startOfWeek, $endOfWeek) {
                $q->whereBetween('work_date', [$startOfWeek, $endOfWeek]);
            }])
            ->get();

        $labels = [];
        $data = [];
        foreach ($users as $user) {
            $labels[] = $user->name;
            $data[] = $user->timeEntries->sum('hours');
        }
        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}
