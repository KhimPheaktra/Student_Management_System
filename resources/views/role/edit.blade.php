@extends('layouts.admin')
@section('title','Edit Role')
@section('content')

<h1>Role</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Edit Role</li>
    </ol>
        
<form method="post" action="{{route('role.update',$roles->id)}}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
        <div class="mb-3">
        <label class="form-label">Role Name</label>
        <input type="text" disabled value="{{$roles->id}}" class="form-control" >
      </div>

    <div class="mb-3">
        <label for="name" class="form-label">Role Name</label>
        <input type="text" value="{{$roles->name}}" class="form-control" id="name" name="name" >
      </div>
      
  <button class="btn btn-warning" type="submit">Edit</button>    
</form>
@endsection