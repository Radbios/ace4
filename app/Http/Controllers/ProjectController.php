<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only('search', 'coordinator_id', 'category');

        $projects = Project::with(['coordinator', 'collaborators'])
            ->when(
                isset($filters['category']),
                fn($query) => $query->where('category', $filters['category'])
            )->when(
                isset($filters['coordinator_id']),
                fn($query) => $query->where('coordinator_id', $filters['coordinator_id'] == 'my' ? Auth::id() : $filters['coordinator_id'])
            )->when(
                isset($filters['search']),
                fn($query) => $query->where("name", 'LIKE', '%' . $filters['search'] . '%')
            )
            ->latest()->paginate();

        $projects->appends($request->query());

        $coordinators = User::where('role_id', Role::COORDINATOR)
            ->when(
                Auth::user()->has_role(Role::COORDINATOR),
                fn($query) => $query->where('role_id', '<>', Auth::id())
            )
            ->orderBy('name')
            ->get(['id', 'name']);

        $categories = Project::orderBy('category')
            ->pluck("category")
            ->unique()
            ->values()
            ->toArray();;

        return Inertia::render("Project/Index", [
            'projects' => $projects,
            'categories' => $categories,
            'coordinators' => $coordinators,
            'filters' => $filters,
        ]);
    }
}
