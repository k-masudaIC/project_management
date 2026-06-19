<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Http\Requests\StoreProjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class ProjectController extends Controller
{
    use AuthorizesRequests;
    
    public function __construct()
    {
        if (auth()->check()) {
            $this->authorizeResource(Project::class, 'project');
        }
    }

    public function index(Request $request)
    {
        $query = Project::with(['client', 'creator']);
        $clients = $this->availableClients();

        $user = Auth::user();
        if ($user && in_array($user->role, ['member', 'contractor'], true)) {
            $allowedClientIds = $clients->pluck('id');
            $query->whereIn('client_id', $allowedClientIds);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        $projects = $query->orderByDesc('created_at')->paginate(20);
        return view('projects.index', compact('projects', 'clients'));
    }

    public function create()
    {
        $clients = $this->availableClients();
        return view('projects.create', compact('clients'));
    }

    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();
        if (!$this->canUseClient((int) $validated['client_id'])) {
            return back()->withInput()->withErrors(['client_id' => '担当クライアントのみ選択できます。']);
        }

        try {
            DB::transaction(function () use ($validated) {
                $data = $validated;
                $data['created_by'] = auth()->id();
                Project::create($data);
            });
            return redirect()->route('projects.index')->with('success', '案件を登録しました');
        } catch (\Exception $e) {
            Log::error('Project create failed: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withErrors(['error' => '案件の作成に失敗しました。']);
        }
    }

    public function show(Project $project)
    {
        $project->load(['client', 'creator']);
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $clients = $this->availableClients();

        if (!$clients->pluck('id')->contains($project->client_id)) {
            $clients->push($project->client);
        }

        return view('projects.edit', compact('project', 'clients'));
    }

    public function update(StoreProjectRequest $request, Project $project)
    {
        $validated = $request->validated();
        if (!$this->canUseClient((int) $validated['client_id'])) {
            return back()->withInput()->withErrors(['client_id' => '担当クライアントのみ選択できます。']);
        }

        try {
            DB::transaction(function () use ($validated, $project) {
                $data = $validated;
                $project->update($data);
            });
            return redirect()->route('projects.index')->with('success', '案件情報を更新しました');
        } catch (\Exception $e) {
            Log::error('Project update failed: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withErrors(['error' => '案件の更新に失敗しました。']);
        }
    }

    private function availableClients()
    {
        $user = Auth::user();
        if ($user && in_array($user->role, ['member', 'contractor'], true)) {
            return $user->clients()->where('is_active', true)->orderBy('company_name')->get();
        }

        return Client::where('is_active', true)->orderBy('company_name')->get();
    }

    private function canUseClient(int $clientId): bool
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['member', 'contractor'], true)) {
            return true;
        }

        return $user->clients()->where('clients.id', $clientId)->exists();
    }

    public function destroy(Project $project)
    {
        try {
            $project->delete();
            return redirect()->route('projects.index')->with('success', '案件を削除しました');
        } catch (\Exception $e) {
            Log::error('Project delete failed: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withErrors(['error' => '案件の削除に失敗しました。']);
        }
    }
}
