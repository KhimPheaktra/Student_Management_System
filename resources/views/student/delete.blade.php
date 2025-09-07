@extends('layouts.admin')
@section('title','Delete Student')
@section('content')


<h1>Delete Student</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Delete Student</li>
    </ol>
        
<form id="deleteForm" class="row g-2" method="post" action="{{route('student.delete',$students->id)}}" enctype="multipart/form-data">
    @method('DELETE')
    <div class="col-md-6">
        <label for="student_code" class="form-label">Id</label>
        <input disabled type="text" value="{{$students->id}}" class="form-control" id="student_code" name="student_code" placeholder="Student Code">
      </div>
    <div class="col-md-6">
        <label for="student_code" class="form-label">Student Code</label>
        <input type="text" value="{{$students->student_code}}" class="form-control" id="student_code" name="student_code" placeholder="Student Code">
      </div>

    <div class="col-md-6">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" value="{{$students->first_name}}" class="form-control" id="first_name" name="first_name" placeholder="Student First Name">
      </div>
      <div class="col-md-6">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" value="{{$students->last_name}}" class="form-control" id="last_name" name="last_name" placeholder="Student Last Name">
      </div>
      <div class="col-md-6">
        <label for="dob" class="form-label">Date Of Birth</label>
        <input type="date" value="{{$students->dob}}" class="form-control" id="dob" name="dob" placeholder="dob">
      </div>

      <div class="col-md-6">
        <label for="pob" class="form-label">Place Of Birth</label>
        <select class="form-select" aria-label="Default select example" name="pob">
            <option selected>Select Province</option>
            @foreach($provinces as $province)
            <option value="{{$province->id}}" {{$students->pob == $province->id ? 'selected' : '' }}>{{$province->name}}</option>
            @endforeach

          </select>
      </div>

      <div class="col-md-6">
        <label for="major_id" class="form-label">Major</label>
        <select class="form-select" aria-label="Default select example" name="major_id">
            <option selected>Select Major</option>
            @foreach($majors as $major)
            <option value="{{$major->id}}" {{$students->major_id == $major->id ? 'selected' : ''}}>{{$major->name}}</option>
            @endforeach

          </select>
      </div>

      <div class="col-md-6">
        <label for="gen_id" class="form-label">Generation</label>
        <select class="form-select" aria-label="Default select example" name="gen_id">
            <option selected>Select Generation</option>
            @foreach($gens as $gen)
            <option value="{{$gen->id}}" {{$students->gen_id == $gen->id ?  'selected' : '' }}>{{$gen->gen}}</option>
            @endforeach

          </select>
      </div>

      <div class="col-md-6">
        <label for="gender_id" class="form-label">Gender</label>
        <select class="form-select" aria-label="Default select example" name="gender_id">
            <option selected>Select Gender</option>
            @foreach($genders as $gender)
            <option value="{{$gender->id}}" {{$students->gender_id == $gender->id ? 'selected' : '' }}>{{$gender->name}}</option>
            @endforeach

          </select>
      </div>

      <div class="col-md-6">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" value="{{$students->phone}}" class="form-control" id="phone" name="phone" placeholder="phone">
      </div>

      <div class="col-md-6">
        <label for="parent_phone" class="form-label">Parent Phone</label>
        <input type="text" value="{{$students->parent_phone}}" class="form-control" id="parent_phone" name="parent_phone" placeholder="Parent Phone">
      </div>

      <div class="col-md-6">
        <label for="enroll_at" class="form-label">Enroll At</label>
        <input type="date"  value="{{$students->enroll_at}}" class="form-control" id="enroll_at" name="enroll_at" placeholder="Enroll">
      </div>

      <div class="col-md-6">
        <label for="image" class="form-label">Old Image</label>
        <input hidden type="file" id="image" name="image" class="form-control">
        <img src="{{$students->image}}" class="mt-3" width="60"/>

        
    </div>

    <div class="col-md-6">
        <label for="image" class="form-label">New Image</label>
        <input type="file" id="image" name="image" class="form-control" onchange="previewImage(event)">
        <img id="imagePreview" class="mt-3" width="60"/>
        <button type="button" id="cancelImage" class="btn btn-danger btn-sm mt-2" style="display:none;" onclick="cancelPreview()">Cancel</button>
    </div>
    <button class="btn btn-danger col-md-1" onclick="confirmDelete()" type="button" data-confirm-delete="true">Delete</button>    
</form>

@endsection


<script>
    function confirmDelete() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm').submit();
            }
        });
    }
  </script>