@extends('layouts.admin')
@section('title','Add Term')
@section('content')

<h1>Term</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Add Term</li>
    </ol>
        
<form method="post" action="{{route('term.create')}}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Term Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Term Name">
      </div>
      <div class="mb-3">
        <label for="start_date" class="form-label">Start Date</label>
        <input type="date" class="form-control" id="start_date" name="start_date">
      </div>

      <div class="mb-3">
        <label for="end_date" class="form-label">End Date</label>
        <input type="date" class="form-control" id="end_date" name="end_date">
      </div>
      
  <button class="btn btn-primary" type="submit">Add</button>    
</form>
@endsection