@extends('layouts.admin')
@section('title','Edit Class Teacher')
@section('content')

{{-- Add Class Teacher --}}
<h1>Class</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Edit Class</li>
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
        
<form method="post" action="{{route('updateClassTeacher',$classTeachers->id)}}" enctype="multipart/form-data">
    @csrf
    @method('PUT') 

    <div class="mb-3">
        <label for="class_id" class="form-label">Class Teacher Id</label>
        <input disabled class="form-control" type="text" name="id" value="{{ $classTeachers->id }}">
    </div>

    <div class="mb-3">
    <input class="form-control" type="hidden" name="class_id" value="{{ $classes->id }}">
    </div>
    
    <div class="mb-3">
        <label for="class_id" class="form-label">Class Name</label>
        <input disabled value="{{$classes->name}}" type="text" class="form-control" placeholder="Class Name">
      </div>

      <div class="mb-3">
        <label class="form-label">Teacher</label>
        <select name="teacher_id" class="form-select" required>
            <option value="" selected disabled>Select Teacher</option>
            @foreach($teachers as $teacher)
            <option value="{{ $teacher->id }}" {{$classTeachers->teacher_id == $teacher->id ? 'selected' : ''}}>
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
                <option value="{{ $subject->id }}" {{$classTeachers->subject_id == $subject->id ? 'selected' : ''}}>
                    {{ $subject->name }}
                </option>
            @endforeach
        </select>
    </div>
  <button class="btn btn-warning" type="submit">Edit</button>    
</form>
@endsection