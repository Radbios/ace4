<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CollaboratorController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only('search', 'project_id', 'sort_by', 'sort_direction');

        $users = User::whereHas('collaborators')
            ->with(['collaborators.project.coordinator'])
            ->withCount('collaborators')
            ->withSum('collaborators', 'scholarship')
            ->when(
                isset($filters['project_id']),
                fn($query) => $query->whereHas('collaborators', fn($s_query) => $s_query->where('project_id', $filters['project_id']))
            )
            ->when(
                isset($filters['search']),
                fn($query) => $query->where('name', 'LIKE', '%' . $filters['search'] . '%')
                    ->orWhere('email', 'LIKE', '%' . $filters['search'] . '%')
            )
            ->when(
                isset($filters['sort_by']) && isset($filters['sort_direction']),
                fn($query) => $query->orderBy($filters['sort_by'], $filters['sort_direction'])
            )
            ->orderBy('name')
            ->latest()->paginate();

        $users->appends($request->query());
        $projects = Project::orderBy('name')->get(['id', 'name']);

        return Inertia::render("Collaborator/Index", [
            'users' => $users,
            'projects' => $projects,
            'filters' => $filters,
        ]);
    }

    public function heatmap()
    {
        $data = User::whereHas('collaborators')
            ->select(DB::raw('count(*) as count, country'))->groupBy('country')->get();

        return Inertia::render('Collaborator/Heatmap', [
            'data' => $data,
        ]);
    }
}
