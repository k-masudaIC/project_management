<?php

namespace App\Http\Controllers;

use App\Models\TimeEntry;
use App\Http\Requests\StartTimerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TimerController extends Controller
{
    public function start(StartTimerRequest $request)
    {
        $entry = TimeEntry::create([
            'task_id' => $request->task_id,
            'user_id' => Auth::id(),
            'work_date' => Carbon::now()->toDateString(),
            'hours' => 0,
            'started_at' => Carbon::now(),
        ]);
        return response()->json(['id' => $entry->id, 'started_at' => $entry->started_at]);
    }

    public function stop(Request $request, TimeEntry $timeEntry)
    {
        $this->authorize('update', $timeEntry);
        if (!$timeEntry->started_at) {
            return response()->json(['error' => 'タイマーが開始されていません'], 400);
        }
        $timeEntry->ended_at = Carbon::now();
        $diff = Carbon::parse($timeEntry->started_at)->diffInMinutes($timeEntry->ended_at) / 60;
        $timeEntry->hours = round($diff, 2);
        $timeEntry->save();
        return response()->json(['id' => $timeEntry->id, 'ended_at' => $timeEntry->ended_at, 'hours' => $timeEntry->hours]);
    }
}
