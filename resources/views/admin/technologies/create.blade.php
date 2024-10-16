@extends('layouts.admin')
@section('content')

<form class="p-4" action="{{ route('admin.technologies.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label " >Name</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="name" name="name" placeholder="Enter the name of the technology" > 
            @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
      
        <button class="btn btn-primary" type="submit">Crea</button>
    </form>

@endsection