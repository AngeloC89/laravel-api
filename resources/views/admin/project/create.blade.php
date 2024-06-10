@extends('layouts.admin')

@section('content')

<form class="p-4" action="{{ route('admin.project.store') }}" method="POST" enctype="multipart/form-data">
  @csrf
  <div class="mb-3">
    <label for="title" class="form-label">Title</label>
    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
      value="{{ old('title') }}">

    @error('title')
    <div class="alert alert-danger">{{ $message }}</div>
  @enderror
  </div>
  <div class="mb-3">
    <label for="content" class="form-label">Content</label>
    <textarea type="text" class="form-control @error('title') is-invalid @enderror" id="content" name="content"
      rows="3">{{ old('content') }}</textarea>

    @error('content')
    <div class="alert alert-danger">{{ $message }}</div>
  @enderror
  </div>

  <div class="mb-3">
    <img id="upload_preview" width="100" src="/images/placeholder.jpeg" alt="" class="mb-2">
    <input type="file" accept="image/*" class="form-control @error('image') is-invalid @enderror" id="uploadImage"
      name="image" value="{{ old('image') }}" required>

    @error('image')
    <div class="alert alert-danger">{{ $message }}</div>
  @enderror
  </div>
  <div class="mb-3">
    <label for="tpye_id" class="form-label">Select type</label>
    <select name="type_id" id="type_id" class="form-control @error('type_id') is-invalid @enderror">
      <option value="">Select type</option>
      @foreach ($types as $type)
      <option value="{{$type->id}}" {{ $type->id == old('type_id') ? 'selected' : '' }}>{{$type->name}}</option>
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
      <input class="form-check-input" type="checkbox" name="technologies[]" value="{{$technology->id}}" {{ in_array($technology->id, old('tags', [])) ? 'checked' : '' }}>
      <label class="form-check-label" for="flexCheckDefault"></label>
      {{$technology->name}}
      </label>

    </div>
  @endforeach
  </div>
  <button class="btn btn-primary" type="submit">Crea</button>
</form>

@endsection