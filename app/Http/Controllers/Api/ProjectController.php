<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;

use App\Http\Controllers\Controller;
use GrahamCampbell\ResultType\Success;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;



class ProjectController extends Controller
{
    public function index(Request $request)
    {

        $projects = Project::with(['technologies', 'images']);

        if ($request->query('technologies')) {
            $projects->whereHas('technologies', function($query) use ($request) {
                $query->where('technology_id', $request->query('technologies'));
            });
        }

        if ($request->query('images')) {
            $projects->whereHas('images', function($query) use ($request) {
                $query->where('image_id', $request->query('images'));  // Aggiusta questo filtro secondo le tue necessità
            });
        }

        $projects = $projects->paginate(10);
        return response()->json([
            'status' => 'success',
            'results' => $projects
        ], 200);
    }

    public function show($slug)
    {
        $project = Project::with(['technologies','type', 'images'])->where('slug', $slug)->first();

        if ($project) {
            return response()->json([
                'status' => 'success',
                'message' => 'ok',
                'results' => $project
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Error',
            ], 404);
        }
    }
}
