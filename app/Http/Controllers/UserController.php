<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\UserRequest;
use App\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only('search', 'sort_by', 'sort_direction', 'is_activate', 'role');

        $users = User::with('role')->when(
            isset($filters['sort_by']) && isset($filters['sort_direction']),
            fn($query) => $query->orderBy($filters['sort_by'], $filters['sort_direction'])
        )
            ->when(
                isset($filters['is_activate']),
                fn($query) => $query->where('is_activate', (bool)$filters['is_activate'])
            )
            ->when(
                isset($filters['role']),
                fn($query) => $query->where('role_id', $filters['role'])
            )
            ->when(
                isset($filters['search']),
                fn($query) => $query->where('name', 'LIKE', '%' . $filters['search'] . '%')
                    ->orWhere('email', 'LIKE', '%' . $filters['search'] . '%')->orWhere('email', 'LIKE', '%' . $filters['search'] . '%')
            )
            ->latest()->paginate();

        $roles = Role::all();

        return Inertia::render('User/Index', [
            'users' => $users,
            'filters' => $filters,
            'roles' => $roles,
        ]);
    }

    public function toggle_status(User $user)
    {
        $user->is_activate = !$user->is_activate;
        $user->save();

        return redirect()->back()->with('success', 'status atualizado com sucesso');
    }

    public function store(UserRequest $request)
    {
        $user = User::create($request->all());

        if (!$user)
            return redirect()->back()->with('error', 'Falha na tentativa de cadastrar usuário');

        return redirect()->back()->with('success', 'Usuário criado com sucesso!');
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();

        if ($data['password'] == null)
            unset($data['password']);

        $user_updated = $user->update($data);

        if (!$user_updated)
            return redirect()->back()->with('error', 'Falha na tentativa de atualizar usuário');

        return redirect()->back()->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        $user_deleted = $user->delete();

        if (!$user_deleted)
            return redirect()->back()->with('error', 'Falha na tentativa de deletar usuário');

        return redirect()->back()->with('success', 'Usuário deletado com sucesso!');
    }
}
