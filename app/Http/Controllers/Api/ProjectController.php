<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(){
        $projects = Project::all();
        //dd($projects);
        return response()->json([
            'success' => true,
            'results' => $projects
        ]);
    }

    public function show($slug){
        $project = Project::where('slug', $slug)->with('type', 'technologies')->first();
        return response()->json([
            'success' => true,
            'results' => $project
        ]);
    }
}
