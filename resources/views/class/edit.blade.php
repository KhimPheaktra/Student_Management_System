@extends('layouts.admin')
@section('title','Edit Class')
@section('content')

<h1>Class</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Edit Class</li>
    </ol>
        
<form method="post" action="{{route('class.update',$classes->id)}}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Class Id</label>
        <input disabled type="text" value="{{$classes->id}}" class="form-control" id="name" name="name" placeholder="Class Name">
      </div>

    <div class="mb-3">
        <label for="name" class="form-label">Class Name</label>
        <input type="text" value="{{$classes->name}}" class="form-control" id="name" name="name" placeholder="Class Name">
      </div>
  <button class="btn btn-warning" type="submit">Edit</button>    
</form>
@endsection