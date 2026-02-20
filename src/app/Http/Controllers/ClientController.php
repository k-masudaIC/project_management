<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
    use AuthorizesRequests;
    public function __construct()
    {
        $this->authorizeResource(Client::class, 'client');
    }

    public function index(Request $request)
    {
        // 必要なカラムのみ取得し、関連プロジェクトをEager Load
        $clients = Client::with(['projects' => function($query) {
            $query->select('id', 'client_id', 'name', 'status');
        }])
        ->select('id', 'name', 'company_name', 'email', 'is_active', 'created_at')
        ->orderBy('created_at', 'desc')
        ->paginate(20);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(StoreClientRequest $request)
    {
        try {
            Client::create($request->validated());
            return redirect()->route('clients.index')->with('success', 'クライアントを登録しました');
        } catch (\Exception $e) {
            Log::error('Client create failed: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withErrors(['error' => 'クライアントの登録に失敗しました。']);
        }
    }

    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        try {
            $client->update($request->validated());
            return redirect()->route('clients.index')->with('success', 'クライアント情報を更新しました');
        } catch (\Exception $e) {
            Log::error('Client update failed: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withErrors(['error' => 'クライアント情報の更新に失敗しました。']);
        }
    }

    public function destroy(Client $client)
    {
        try {
            $client->delete();
            return redirect()->route('clients.index')->with('success', 'クライアントを削除しました');
        } catch (\Exception $e) {
            Log::error('Client delete failed: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withErrors(['error' => 'クライアントの削除に失敗しました。']);
        }
    }
}
