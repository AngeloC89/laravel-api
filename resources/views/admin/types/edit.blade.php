@extends('layouts.admin')
@section('content')



<section class="container p-5">

    <form action="{{ route('admin.types.update', $type->slug) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title" class="form-label @error('title') is-invalid @enderror">Title</label>
            <input type="text" class="form-control" id="title" name="name" placeholder="Name"
                value="{{ old('title', $type->name) }}" required>

            @error('title')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <!-- <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea type="text" class="form-control @error('content') is-invalid @enderror" id="description"
                name="content" placeholder="Descrizone" value="{{old('content', $type->content)}}"
                required>{{ old('content', $type->content) }}</textarea>
        </div> -->

        <button class="btn btn-primary" type="submit">Modifica</button>
    </form>
</section>
@endsection