@extends('layouts.admin')
@section('title','Add Position')
@section('content')

<h1>Term</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Add Position</li>
    </ol>
        
<form method="post" action="{{route('position.create')}}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Position Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Position Name">
      </div>
  <button class="btn btn-primary" type="submit">Add</button>    
</form>
@endsection