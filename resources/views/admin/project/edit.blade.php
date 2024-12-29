@extends('layouts.admin')
@section('content')



<section class="container p-5">

    <form action="{{ route('admin.project.update', $project->slug) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title" class="form-label @error('title') is-invalid @enderror">Title</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Titolo"
                value="{{ old('title', $project->title) }}" required>

            @error('title')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea type="text" class="form-control @error('content') is-invalid @enderror" id="description"
                name="content" placeholder="Descrizone" value="{{old('content', $project->content)}}"
                required>{{ old('content', $project->content) }}</textarea>
        </div>

        <!-- Link -->
        <div class="mb-3">
            <label for="link" class="form-label">Link of project</label>
            <input type="text" class="form-control @error('link') is-invalid @enderror" id="link" name="link"
                value="{{ old('link', $project->link) }}">

            @error('link')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        {{--image--}}
        <div class="mb-3">
            @if ($project->images->isNotEmpty())
                <span class="m-3 text-muted">Current Images:</span>
                @foreach ($project->images as $image)
                    <img id="upload_preview" class="w-25 my-3"
                        src="{{ Storage::disk('s3')->url($image->path ?? '/images/placeholder.jpeg') }}" alt="Project Image">
                @endforeach
            @else
                <span class="m-3 text-muted">No images available. <img id="upload_preview" class="w-25 my-3"
                        src="{{ asset('/images/placeholder.jpeg') }}" alt="Placeholder"></span>
            @endif
            <div>
                <input type="file" accept="image/*" class="form-control @error('image') is-invalid @enderror w-100"
                    id="uploadImage" name="image">
            </div>
            @error('image')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        {{--type--}}
        <div class="mb-3">
            <label for="tpye_id" class="form-label">Select type</label>
            <select name="type_id" id="type_id" class="form-control @error('type_id') is-invalid @enderror">

                @foreach ($types as $type)
                    <option value="{{$type->id}}" {{ $project->type_id == $type->id ? 'selected' : '' }}>{{$type->name}}
                    </option>
                @endforeach
            </select>


            @error('type_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <p>Seleziona Tecnologie:</p>
            @foreach ($technologies as $technology)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="technologies[]" value="{{$technology->id}}" {{ $project->technologies->contains($technology->id) ? 'checked' : ''}}>
                    <label class="form-check-label" for="flexCheckDefault"></label>
                    {{$technology->name}}
                    </label>

                </div>
            @endforeach
        </div>

        <button class="btn btn-primary" type="submit">Modifica</button>
        <a href="{{ route('admin.project.index') }}" class="btn btn-danger">Annulla</a>

    </form>
</section>
@endsection