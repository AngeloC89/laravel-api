<?php

namespace App\Http\Controllers\Admin;

use App\Models\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = Type::all();
        return view('admin.types.index', compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:types|max:255',
        ]);
        $form_data = $request->all();
        $form_data['slug'] = Type::generateSlug($form_data['name']);
        $new_type = Type::create($form_data);
        return redirect()->route('admin.types.show', $new_type->slug)->with("message", "Il tipo $new_type->name e stato creato correttamente");
    }

    /**
     * Display the specified resource.
     */
    public function show(Type $type)
    {
        return view('admin.types.show', compact('type'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Type $type)
    {
        return view('admin.types.edit', compact('type'));
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
    public function destroy(Type $Type)
    {
        $Type->delete();
        return redirect()->route('admin.types.index')->with('message', "The type $Type->name has been deleted");
    }
}
