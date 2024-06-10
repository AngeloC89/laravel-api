@extends('layouts.admin')
@section('content')



<section class="container p-5">

    <form action="{{ route('admin.technologies.update', $technology->slug) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title" class="form-label @error('title') is-invalid @enderror">Title</label>
            <input type="text" class="form-control" id="title" name="title" 
                value="{{ old('name', $technology->name) }}" required>

            @error('title')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea type="text" class="form-control @error('content') is-invalid @enderror" id="description"
                name="content" placeholder="Descrizone" value="{{old('content', $technology->content)}}"
                required>{{ old('content', $technology->content) }}</textarea>
        </div>


        {{-- <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <div class="">
                <input type="file" accept="image/*" class="form-control @error('image') is-invalid @enderror w-100"
                    id="uploadImage" name="image" value="{{ old('image', $technology->image) }}">

                @if ($technologies->image)
                    <span class="m-3 text-muted">Current Image: <img id="upload_preview" class="w-25 my-3"
                            src="/images/placeholder.jpeg" alt=""></span>
                @endif
            </div>

            @error('image')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div> --}}

        <button class="btn btn-primary" technology="submit">Modifica</button>
    </form>
</section>
@endsection