@extends('layouts.admin')
@section('content')

<div id="showProject" class="container">
  <div>
    @if(session()->has('message'))
    <div class="alert alert-success">
      {{ session()->get('message') }}
    </div>
  @endif
  </div>


  <h1 class=" text-uppercase py-3 ">{{ $project->title }}</h1>

  <img class="w-25" src="{{asset('storage/' . $project->image ?? '') }}" alt="">

  <div class=" fs-3 py-4">Descrizione: {{ $project->content }}</div>
  <div>
    @if ($project->type)
    <span class="badge text-bg-secondary p-2 fs-5"> {{ $project->type->name }}</span>
  @endif

  </div>

<div class="my-3 ">
  @if ($project->technologies)
      @foreach ($project->technologies as $technology)
        <span class="badge text-bg-success p-2 fs-5">{{ $technology->name }}</span>
      @endforeach
  @endif
</div>
  


  <div class="d-flex justify-content-start py-2">
    <button class="btn btn-primary h-25"><a class="text-decoration-none text-white"
        href="{{route('admin.project.edit', $project->slug)}}">Modifica</a></button>

    <form action="{{ route('admin.project.destroy', $project->slug) }}" method="POST">
      @csrf
      @method('DELETE')
      <input data-item-title="{{ $project->title }}" type="submit" value="Elimina" class="btn btn-danger ms-3">
    </form>

  </div>

</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Stai eliminando {{ $project->title }}</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Sicuro/a di voler eliminare questo elemento?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <button type="button" class="btn btn-danger">Elimina</button>
      </div>
    </div>
  </div>
</div>


@endsection