<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Project;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Type;
use App\Models\Technology;
use App\Models\Image;
use App\Models\User;

//use Illuminate\Support\Facades\DB;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        $projects = Project::where('user_id', auth()->id())->get();
        return view('admin.project.index ', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = Type::all();
        $technologies = Technology::all();
        return view('admin.project.create', compact('types', 'technologies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        //Valido i dati
        $form_data = $request->validated();
        $form_data['user_id'] = auth()->id();
        $originalTitle = $form_data['title'];
        $title = $originalTitle;  // Inizialmente, il titolo è uguale all'originale
        $counter = 1;

        // Ciclo per trovare un titolo unico
        while (Project::where('title', $title)->exists()) {
            // Aggiungi un suffisso progressivo al titolo
            $title = $originalTitle . '-' . $counter;
            $counter++;
        }

        // Usa il titolo unico trovato
        $form_data['title'] = $title;
        //creo lo slug dal titolo
        $form_data['slug'] = Project::generateSlug($form_data['title']);
        //creo il nuovo progetto ( al momemnto senza immagini qual'ora non ci siano ) 
        $new_project = Project::create($form_data);

        //verifico se ci sono immagini
        // if ($request->hasFile('image')) {
        //     $image = $request->file('image');
        //     $name = $image->getClientOriginalName();
        //     $path = Storage::disk('google')->putFileAs('images', $image, $name);
        //     $form_data['image'] = $path;
        // }


        // Image::create([
        //     'project_id' => $new_project->id,  // Collega l'immagine al progetto
        //     //'path' => $path                   // Salva il percorso dell'immagine
        // ]);




        if ($request->has('technologies')) {
            $new_project->technologies()->attach($request->technologies);
        }

        return redirect()->route('admin.project.show', $new_project->slug)->with("message", "Il progetto $new_project->title e stato creato correttamente");
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $project = Project::where('slug', $slug)->with('images', 'type', 'technologies')->firstOrFail();
        if ($project->user_id !== auth()->id()) {
            abort(403, 'Accesso non autorizzato');
        };
        return view('admin.project.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $types = Type::all();
        $technologies = Technology::all();
        return view('admin.project.edit', compact('project', 'types', 'technologies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        if ($project->user_id !== auth()->id()) {
            abort(403, 'Accesso non autorizzato');
        }

        $form_data = $request->validated();
        if ($project->title !== $form_data['title']) {
            $form_data['slug'] = Project::generateSlug($form_data['title']);

        }

        $form_data['link'] = $request->link;


        // Se c'è una nuova immagine caricata
        if ($request->hasFile('image')) {
            $currentImage = $project->images()->first(); // Recupera la prima immagine associata al progetto (assumendo un solo record)

            if ($currentImage && $currentImage->path) {
                Storage::disk('google')->delete($currentImage->path);// Elimina l'immagine fisica dal filesystem
                $currentImage->delete();// Elimina il record nella tabella `images`
            }

            // Carica la nuova immagine
            $name = $request->image->getClientOriginalName();

            // Salva il file nella cartella `images` all'interno di `storage/app/s3`
            $path = $request->file('image')->storeAs('images', $name, 'google');


            // Crea un nuovo record nella tabella `images` e associa l'immagine al progetto
            $project->images()->create([
                'path' => $path,           // Percorso del file caricato
                'project_id' => $project->id // Associa l'immagine al progetto
            ]);
        }

        if (isset($form_data['type_id'])) {
            $project->type_id = $form_data['type_id']; // Associa il nuovo tipo
        }

        //     DB::enableQueryLog();
        $project->update($form_data);
        //     $query = DB::getQueryLog();
        //     dd($query);



        if ($request->has('technologies')) {
            $project->technologies()->sync($request->technologies);
        } else {
            $project->technologies()->sync([]);
        }

        return redirect()->route('admin.project.show', $project->slug)->with('message', "The project $project->title has been updated");

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if ($project->user_id !== auth()->id()) {
            abort(403, 'Accesso non autorizzato');
        };
        $project->delete();
        return redirect()->route("admin.project.index")->with('message', "The project $project->title has been deleted");

    }
}
