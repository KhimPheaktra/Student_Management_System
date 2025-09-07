@extends('layouts.admin')
@section('title','Add Generation')
@section('content')

<h1>Generation</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Add Generation</li>
    </ol>
        
<form method="post" action="{{route('generation.update',$generations->id)}}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="gen" class="form-label">Generation</label>
        <input type="text" value="{{$generations->gen}}" class="form-control" id="gen" name="gen" placeholder="Generation" required>
      </div>
        <div class="mb-3">
        <label for="year" class="form-label">Year</label>
      <input type="date" value="{{ $generations->year ? $generations->year . '-01-01' : '' }}" class="form-control" id="year" name="year" placeholder="Year" required>
      </div>
      
  <button class="btn btn-warning" type="submit">Edit</button>    
</form>
@endsection