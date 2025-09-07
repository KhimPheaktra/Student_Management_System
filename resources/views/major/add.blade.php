@extends('layouts.admin')
@section('title','Add Major')
@section('content')

<h1>Major</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Add Major</li>
    </ol>
        
<form method="post" action="{{route('major.create')}}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Major Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Major Name" required>
      </div>
      
  <button class="btn btn-primary" type="submit">Add</button>    
</form>
@endsection