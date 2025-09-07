@extends('layouts.admin')
@section('title','Edit Major')
@section('content')

<h1>Major</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Edit Major</li>
    </ol>
        
<form method="post" action="{{route('major.update',$majors->id)}}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Major Name</label>
        <input type="text" value="{{$majors->name}}" class="form-control" id="name" name="name" placeholder="Major Name">
      </div>
      
  <button class="btn btn-warning" type="submit">Edit</button>    
</form>
@endsection