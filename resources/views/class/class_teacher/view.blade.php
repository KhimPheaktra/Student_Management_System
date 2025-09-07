@extends('layouts.admin')
@section('title','Class View')
@section('content')

    
<h1>Class View</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Class View</li>
    </ol>
        
    <div class="row">
{{-- Search --}}
<div class="col-md-10">
  <div class="form-group">
    <form method="get" action="{{ url('class/class_teacher/view/' . $classes->id)  }}">
      <div class="input-group">
        <input type="text" class="form-controll" name="search" placeholder="Search..." value="{{isset($search) ? $search: ''}}">
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </form>
  </div>
</div>
    
  <div class="col-md-2">
    <a href="{{ url('/class/class_teacher/addClassTeacher/' . $classes->id) }}" type="button" class="btn btn-primary mb-2">Add New</a>
  </div>
</div>
  


@if ($classViews->isNotEmpty())
  <div class="col-md-12">
      Class Name: <span>{{ $classViews[0]->name }}</span>
  </div>
  <div class="col-md-12">
      Total Teacher: <span>{{ $classViews[0]->total_teacher }}</span>
  </div>
@endif

<hr>



<div class="table-container">

<table class="table text-center align-middle mt-2 ">
    <thead class="table-dark">
      <tr>
        <th scope="col">Id</th>
        <th scope="col">Teacher Name</th>
        <th scope="col">Subject</th>
        <th scope="col">Active</th>
      </tr>
    </thead>
    <tbody>
        @forelse ($classTeachers as $classTeacher)
        <tr>
            <th scope="row">{{$classTeacher->id}}</th>
            <td>
                @php
                $teacher = App\Models\TeacherModel::find($classTeacher->teacher_id);
                echo $teacher ? $teacher->first_name. ' '. $teacher->last_name : 'Not found';
            @endphp
            </td>
            <td>
              @php
                  $subject = App\Models\SubjectModel::find($classTeacher->subject_id);
                  echo $subject ? $subject->name : '';
              @endphp
            </td>
            <td>
              <a href="{{route('editClassTeacher',$classTeacher->id)}}" type="button" class="btn btn-warning btn-sm">
                <i class="fa-solid fa-pen-to-square"></i>
                Edit
              </a>
              <a href="{{route('showClassTeacher',$classTeacher->id)}}" type="button" class="btn btn-danger btn-sm">
                <i class="fa-solid fa-trash"></i>
                Delete
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="12" class="text-center"> 
              <h3 class="text-danger">No Teacher found</h3>
            </td>
          </tr>
        @endforelse
    </tbody>
  </table>
</div>


   {{-- Pagination --}}
   {{$classTeachers->Links()}}

@endsection