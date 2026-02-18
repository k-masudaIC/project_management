<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Http\Requests\StoreProjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class ProjectController extends Controller
{
    use AuthorizesRequests;
    
    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }

    public function index(Request $request)
    {
        $query = Project::with(['client', 'creator']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        $projects = $query->orderByDesc('created_at')->paginate(20);
        $clients = Client::all();
        return view('projects.index', compact('projects', 'clients'));
    }

    public function create()
    {
        $clients = Client::all();
        return view('projects.create', compact('clients'));
    }

    public function store(StoreProjectRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $data = $request->validated();
                $data['created_by'] = auth()->id() ?? 1; // 認証なし時は仮で1
                Project::create($data);
            });
        } catch (\Exception $e) {
            return back()->withErrors(['error' => '案件の作成に失敗しました。']);
        }
        return redirect()->route('projects.index')->with('success', '案件を登録しました');
    }

    public function show(Project $project)
    {
        $project->load(['client', 'creator']);
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $clients = Client::all();
        return view('projects.edit', compact('project', 'clients'));
    }

    public function update(StoreProjectRequest $request, Project $project)
    {
        try {
            DB::transaction(function () use ($request, $project) {
                $project->update($request->validated());
            });
        } catch (\Exception $e) {
            return back()->withErrors(['error' => '案件の更新に失敗しました。']);
        }
        return redirect()->route('projects.index')->with('success', '案件情報を更新しました');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', '案件を削除しました');
    }
}
