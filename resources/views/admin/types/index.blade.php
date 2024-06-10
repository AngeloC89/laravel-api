@extends('layouts.admin')


@section('content')

<div class="d-flex justify-content-between align-items-center p-2 container">
  <h2>Table of types</h2>
  <a class="btn btn-primary" href="{{route('admin.types.create')}}">Aggiungi</a>
</div>
<div id="tableIndex">
  <div class="container p-2">
    @if(session()->has('message'))
    <div class="alert alert-success">
      {{ session()->get('message') }}
    </div>
  @endif
    <table class="table table-success table-striped">
      <thead>
        <tr>
          <th scope="col">Id</th>
          <th scope="col">Title</th>
          <th scope="col">Image</th>
          <th scope="col">Create Date</th>
          <th scope="col">Update Date</th>
          <th scope="col">Actions</th>

        </tr>
      </thead>

      <tbody class="table-group-divider">
        @foreach ($types as $type)
      <tr>
        <th scope="row">{{$type->id}}</th>
        <td>{{$type->title}}</td>
        <td>{{$type->image}}</td>
        <td>{{$type->created_at}}</td>
        <td>{{$type->updated_at}}</td>
        <td>
        <div class="d-flex gap-2 p-4"> <a href="{{ route('admin.types.show', $type->slug) }}"
          class="btn btn-primary"> <i class="fa-solid fa-eye"></i></a>

          <a href="{{ route('admin.types.edit', $type->slug) }}" class="btn btn-warning"> <i
            class="fa-solid fa-pen-to-square"></i></a>

          <form action="{{ route('admin.types.destroy', $type->slug) }}" method="POST">
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