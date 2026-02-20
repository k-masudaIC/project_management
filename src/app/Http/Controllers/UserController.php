<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(20);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);
            User::create($data);
            return redirect()->route('users.index')->with('success', 'ユーザーを作成しました');
        } catch (\Exception $e) {
            Log::error('User create failed: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withErrors(['error' => 'ユーザーの作成に失敗しました。']);
        }
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $data = $request->validated();
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
            $user->update($data);
            return redirect()->route('users.index')->with('success', 'ユーザー情報を更新しました');
        } catch (\Exception $e) {
            Log::error('User update failed: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withErrors(['error' => 'ユーザー情報の更新に失敗しました。']);
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('users.index')->with('success', 'ユーザーを削除しました');
        } catch (\Exception $e) {
            Log::error('User delete failed: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withErrors(['error' => 'ユーザーの削除に失敗しました。']);
        }
    }
}
