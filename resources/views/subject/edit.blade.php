@extends('layouts.admin')
@section('title','Edit Subject')
@section('content')

<h1>Subject</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Edit Subject</li>
    </ol>
        
<form method="post" action="{{route('subject.update' , $subjects->id)}}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="id" class="form-label">Id</label>
        <input type="text" value="{{$subjects->id}}" class="form-control" id="id" name="id" disabled>
      </div>
    <div class="mb-3">
        <label for="name" class="form-label">Subject Name</label>
        <input type="text" value="{{$subjects->name}}" class="form-control" id="name" name="name" placeholder="Subject Name">
      </div>
      <div class="mb-3" hidden>
        <label for="created_at" class="form-label">Subject Name</label>
        <input type="datetime" value="{{$subjects->created_at}}" class="form-control" id="created_at" name="created_at" placeholder="created_at">
      </div>

      <div class="mb-3">
        <label for="full_score" class="form-label">Full Score</label>
        <input type="text" value="{{$subjects->full_score}}" class="form-control" id="score" name="full_score" placeholder="Score">
      </div>

      <div class="mb-3">
        <label for="term_id" class="form-label">Select year and term</label>
        <select class="form-select" aria-label="Default select example" name="term_id">
            <option selected>Select Year And Term</option>
            @foreach($terms as $term)
            <option value="{{$term->id}}" {{$subjects->term_id == $term->id ? 'selected' : ''}}>{{$term->name}}</option>
            @endforeach
          </select>
      </div>
      <div class="mb-3">
        <label for="major_id" class="form-label">Major</label>
        <select class="form-select" aria-label="Default select example" name="major_id">
            <option selected>Select Major</option>
            @foreach($majors as $major)
            <option value="{{$major->id}}" {{$subjects->major_id == $major->id ? 'selected' : ''}}>{{$major->name}}</option>
            @endforeach
          </select>
        </div>
  <button class="btn btn-warning" type="submit">Edit</button>    
</form>
@endsection