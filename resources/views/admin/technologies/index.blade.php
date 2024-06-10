@extends('layouts.admin')


@section('content')

<nav class="d-flex justify-content-between align-items-center p-2 container ">
  <h2>Table of technologies</h2>
  <a class="btn btn-primary" href="{{route('admin.technologies.create')}}">Aggiungi</a>
</nav>
<div id="tableIndex">
  <div class="container p-2">
    @if(session()->has('message'))
    <div class="alert alert-success">
      {{ session()->get('message') }}
    </div>
  @endif

    <table class="table table-success table-striped">
      <thead>
        <tr class="mb-3">
          <th scope="col">Id</th>
          <th scope="col">Name</th>
          <th scope="col">Create Date</th>
          <th scope="col">Update Date</th>
          <th scope="col">Actions</th>

        </tr>
      </thead>

      <tbody>
        @foreach ($technologies as $technology)
      <tr>
        <th scope="row">{{$technology->id}}</th>
        <td>{{$technology->name}}</td>
        <td>{{$technology->created_at}}</td>
        <td>{{$technology->updated_at}}</td>
        <td>
        <div class="d-flex gap-2 p-4"> <a href="{{ route('admin.technologies.show', $technology->slug) }}"
          class="btn btn-primary"> <i class="fa-solid fa-eye"></i></a>

          <a href="{{ route('admin.technologies.edit', $technology->slug) }}" class="btn btn-warning"> <i
            class="fa-solid fa-pen-to-square"></i></a>

          <form action="{{ route('admin.technologies.destroy', $technology->slug) }}" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" value="&#10060 " class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>

          </form>

        </td>
      </tr>
    @endforeach
      </tbody>




    </table>
  </div>

</div>

@endsection