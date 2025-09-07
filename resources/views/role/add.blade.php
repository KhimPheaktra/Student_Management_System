@extends('layouts.admin')
@section('title','Add Role')
@section('content')

<h1>Role</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Add Role</li>
    </ol>
        
<form method="post" action="{{route('role.create')}}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Role Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Major Name">
      </div>
      
  <button class="btn btn-primary" type="submit">Add</button>    
</form>
@endsection