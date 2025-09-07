@extends('layouts.admin')
@section('title','Add Class Teacher')
@section('content')

{{-- Add Class Teacher --}}
<h1>Class</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Add Class</li>
    </ol>

    @if ($classViews->isNotEmpty())
    <div class="col-md-12">
      Class Name: <span>{{ $classViews[0]->name }}</span>
    </div>
    <div class="col-md-12">
      Total Teacher: <span>{{ $classViews[0]->total_teacher }}</span>
    </div>
    @endif
    <hr>
        
<form method="post" action="{{route('createClassTeacher')}}" enctype="multipart/form-data">
    @csrf
        
    <div class="mb-3">
    <input type="hidden" name="class_id" value="{{ $classes->id }}">
    </div>
    
    <div class="mb-3">
        <label for="class_id" class="form-label">Class Name</label>
        <input disabled value="{{$classes->name}}" type="text" class="form-control" id="class_id" name="class_id" placeholder="Class Name">
      </div>

      <div class="mb-3">
        <label class="form-label">Teacher</label>
        <select name="teacher_id" class="form-select" required>
            <option value="" selected disabled>Select Teacher</option>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}">
                    {{ $teacher->first_name . ' ' . $teacher->last_name }}
                </option>
            @endforeach
        </select>
    </div>
    
    {{-- Subject dropdown --}}
    <div class="mb-3">
        <label class="form-label">Subject</label>
        <select name="subject_id" class="form-select" required>
            <option value="" selected disabled>Select Subject</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}">
                    {{ $subject->name }}
                </option>
            @endforeach
        </select>
    </div>
  <button class="btn btn-primary" type="submit">Add</button>    
</form>
@endsection