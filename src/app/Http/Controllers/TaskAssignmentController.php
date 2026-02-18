<?php


namespace App\Http\Controllers;

use App\Http\Requests\AssignTaskRequest;
use App\Models\TaskAssignment;
use Illuminate\Http\RedirectResponse;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskAssignmentController extends Controller
{
    use AuthorizesRequests;
    public function store(AssignTaskRequest $request): RedirectResponse
    {
        $this->authorize('assign', TaskAssignment::class);
        $validated = $request->validated();
        TaskAssignment::create($validated);
        return back()->with('success', '担当者をアサインしました。');
    }

    public function destroy(TaskAssignment $taskAssignment): RedirectResponse
    {
        $this->authorize('delete', $taskAssignment);
        $taskAssignment->delete();
        return back()->with('success', '担当者アサインを解除しました。');
    }
}
