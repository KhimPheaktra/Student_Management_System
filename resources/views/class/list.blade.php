@extends('layouts.admin')
@section('title','Class')
@section('content')

    
<h1>Class</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Class</li>
    </ol>
        
{{-- Search --}}
<div class="row">
    <div class="col-md-10">
        <div class="form-group">
          <form method="get" action="{{ url('/class') }}">
            <div class="input-group">
              <input type="text" class="form-controll" name="search" placeholder="Search..." value="{{isset($search) ? $search: ''}}">
              <button type="submit" class="btn btn-primary">Search</button>
            </div>
          </form>
        </div>
      </div>

      <div class="col-md-2">
        <a href="{{url('/class/add')}}" type="button" class="btn btn-primary mb-2">Add New</a>
      </div>
</div>


<div class="table-container">

<table class="table text-center align-middle mt-2">
    <thead class="table-dark">
      <tr>
        <th scope="col">Id</th>
        <th scope="col">Class</th>
        <th scope="col">Status</th>
        <th scope="col">Active</th>
      </tr>
    </thead>
    <tbody>
        @forelse ($classes as $class)
        <tr>
            <th scope="row">{{$class->id}}</th>
            <td>{{$class->name}}</td>
            <td>
              @if ($class->status == 'ACT')
                <span class="badge text-bg-success">Active</span>                  
              @endif
            </td>
            <td>
                <a href="{{route('view',$class->id)}}" type="button" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-pen-to-square"></i>
                    View
                  </a>

                <a href="{{route('class.edit',$class->id)}}" type="button" class="btn btn-warning btn-sm">
                  <i class="fa-solid fa-pen-to-square"></i>
                  Edit
                </a>
                <a href="{{route('class.show',$class->id)}}" type="button" class="btn btn-danger btn-sm">
                  <i class="fa-solid fa-trash"></i>
                  Delete
                </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="12" class="text-center">
              <h3 class="text-danger">No Class found</h3>
            </td>
          </tr>
        @endforelse
    </tbody>
  </table>
</div>



   {{-- Pagination --}}
   {{$classes->Links()}}

@endsection