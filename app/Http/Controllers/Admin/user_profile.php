<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class user_profile extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = user_profile::all();
        return view('admin.types.index', compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user_profile.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(user_profile $user_profile)
    {
        return view('admin.user_profile.show', compact('user_profile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(user_profile $user_profile)
    {
        return view('admin.user_profile.show', compact('user_profile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Type $type)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);
        $form_data = $request->all();
        if ($type->name !== $form_data['name']) {
            $form_data['slug'] = Type::generateSlug($form_data['name']);
        }
        $type->update($form_data);
        return redirect()->route('admin.types.show', $type->slug);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(user_profile $user_profile)
    {
        $user_profile->delete();
        return redirect()->route('admin.types.index')->with('message', "The type $Type->name has been deleted");
    }
}
