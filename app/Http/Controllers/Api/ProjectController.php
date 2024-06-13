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
        if ($request->query('technologies')) {
            $projects = Project::with('technologies')->where('technologies_id', $request->query('technologies'))->paginate(4);
        } else {
            $projects = Project::with('technologies')->paginate(4);
        }
        return response()->json([
            'status' => 'success',
            'results' => $projects
        ], 200);
    }

    public function show($slug)
    {
        $project = Project::where('slug', $slug)->with('technologies', 'type')->first();

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
