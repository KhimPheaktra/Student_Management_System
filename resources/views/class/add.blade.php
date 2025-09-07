@extends('layouts.admin')
@section('title','Add Class')
@section('content')

{{-- Add Class --}}
<h1>Class</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Add Class</li>
    </ol>
        
<form method="post" action="{{route('class.create')}}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Class Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Class Name">
      </div>
  <button class="btn btn-primary" type="submit">Add</button>    
</form>
@endsection