@extends('layouts.admin')
@section('title','Edit Position')
@section('content')

<h1>Term</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Edit Position</li>
    </ol>
    
        
<form method="post" action="{{route('position.update',$positions->id)}}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Position Name</label>
        <input type="text" value="{{$positions->name}}" class="form-control" id="name" name="name" placeholder="Position Name">
      </div>
  <button class="btn btn-warning" type="submit">Edit</button>    
</form>
@endsection