<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Technology;

use Illuminate\Http\Request;

class TechnologyController extends Controller
{
    public function index()
    {
        //dd("ciao");
        $technologies = Technology::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Ok',
            'results' => $technologies
        ], 200);
    }
}
