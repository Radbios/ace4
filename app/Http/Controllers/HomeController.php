<?php

namespace App\Http\Controllers;

use App\Models\Collaborator;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __invoke()
    {
        $projects = Project::select('category', DB::raw('COUNT(*) as total'), DB::raw('SUM(price) as price'))
            ->groupBy('category')
            ->get();

        $collaborators = User::where('role_id', Role::STUDENT)->whereHas('collaborators')->count();
        $coordinators = User::where('role_id', Role::COORDINATOR)->whereHas('coordinator_projects')->count();

        $data = [
            'count_projects' => $projects->sum('total'),
            'price_projects' => $projects->sum('price'),
            'count_collaborators' => $collaborators,
            'count_coordinators' => $coordinators,
            'projects' => $projects,
        ];

        return Inertia::render('Home', [
            'data' => $data,
        ]);
    }
}
