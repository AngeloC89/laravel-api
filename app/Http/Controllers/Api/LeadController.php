<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Mail\NewContact;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request)
    {

        $data = $request->all();
      $validator = Validator::make($data,[
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $lead = new Lead();
        $lead->fill($data);
        $lead->save();  
        Mail::to('KqoQe@example.com')->send(new NewContact($lead));
    }
};
