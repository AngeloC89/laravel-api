@extends('layouts.admin')
@section('content')

<form class="p-4" action="{{ route('admin.types.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label " >Title</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{old('title')}}" > 
            @error('title')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea type="text" class="form-control @error('title') is-invalid @enderror" id="content" name="content" rows="3" >{{old('content')}}</textarea>
            @error('content')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
     
        <div class="mb-3">
            <img id="upload_preview" width="100" src="/images/placeholder.jpeg" alt="" class="mb-2">
            <input type="file" accept="image/*" class="form-control @error('image') is-invalid @enderror" id="uploadImage" name="image" value="{{old('image')}}" required>
       
            @error('image')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
      
      
        <button class="btn btn-primary" type="submit">Crea</button>
    </form>

@endsection