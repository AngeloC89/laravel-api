<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;

use App\Http\Controllers\Controller;
use GrahamCampbell\ResultType\Success;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;



class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::paginate(4);
        return response()->json([
            'status' => 'success',
            'message' => 'ok',
            'results' => $projects
        ], 200);
    }

    public function show($slug)
    {
        $project = Project::where('slug', $slug)->with('type', 'technologies')->first();

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
