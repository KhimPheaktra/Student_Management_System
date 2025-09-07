@extends('layouts.admin')
@section('title','Add Subject')
@section('content')

<h1>Subject</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Add Subject</li>
    </ol>
        
<form method="post" action="{{route('subject.create')}}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Subject Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Subject Name">
      </div>
      <div class="mb-3">
        <label for="score" class="form-label">Full Score</label>
        <input type="text" class="form-control" id="score" name="full_score" placeholder="Score">
      </div>

      <div class="mb-3">
        <label for="term_id" class="form-label">Select year and term</label>
        <select class="form-select" aria-label="Default select example" name="term_id">
            <option selected>Select Year And Term</option>
            @foreach($terms as $term)
            <option value="{{$term->id}}">{{$term->name}}</option>
            @endforeach

          </select>
      </div>
      <div class="mb-3">
        <label for="major_id" class="form-label">Major</label>
        <select class="form-select" aria-label="Default select example" name="major_id">
            <option selected>Select Major</option>
            @foreach($majors as $major)
            <option value="{{$major->id}}">{{$major->name}}</option>
            @endforeach

          </select>
      </div>
  <button class="btn btn-primary" type="submit">Add</button>    
</form>
@endsection