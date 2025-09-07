@extends('layouts.admin')
@section('title','Add Student')
@section('content')


<h1>Student</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Add Student</li>
    </ol>
        
<form class="row g-2" method="post" action="{{route('student.create')}}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="student_code" class="form-label">Student Code</label>
        <input type="text" class="form-control" id="student_code" name="student_code" placeholder="Student Code">
      </div>

    <div class="col-md-6">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Student First Name">
      </div>
      <div class="col-md-6">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Student Last Name">
      </div>
      <div class="col-md-6">
        <label for="dob" class="form-label">Date Of Birth</label>
        <input type="date" class="form-control" id="dob" name="dob" placeholder="dob">
      </div>
      <div class="col-md-6">
        <label for="pob" class="form-label">Place Of Birth</label>
        <select class="form-select" aria-label="Default select example" name="pob">
            <option selected>Select Province</option>
            @foreach($provinces as $province)
            <option value="{{$province->id}}">{{$province->name}}</option>
            @endforeach

          </select>
      </div>
      <div class="col-md-6">
        <label for="major_id" class="form-label">Major</label>
        <select class="form-select" aria-label="Default select example" name="major_id">
            <option selected>Select Major</option>
            @foreach($majors as $major)
            <option value="{{$major->id}}">{{$major->name}}</option>
            @endforeach

          </select>
      </div>

      <div class="col-md-6">
        <label for="gen_id" class="form-label">Generation</label>
        <select class="form-select" aria-label="Default select example" name="gen_id">
            <option selected>Select Generation</option>
            @foreach($gens as $gen)
            <option value="{{$gen->id}}">{{$gen->gen}}</option>
            @endforeach

          </select>
      </div>

      <div class="col-md-6">
        <label for="gender_id" class="form-label">Gender</label>
        <select class="form-select" aria-label="Default select example" name="gender_id">
            <option selected>Select Gender</option>
            @foreach($genders as $gender)
            <option value="{{$gender->id}}">{{$gender->name}}</option>
            @endforeach

          </select>
      </div>

      <div class="col-md-6">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" class="form-control" id="phone" name="phone" placeholder="phone">
      </div>

      <div class="col-md-6">
        <label for="parent_phone" class="form-label">Parent Phone</label>
        <input type="text" class="form-control" id="parent_phone" name="parent_phone" placeholder="Parent Phone">
      </div>

      <div class="col-md-6">
        <label for="enroll_at" class="form-label">Enroll At</label>
        <input type="date" class="form-control" id="enroll_at" name="enroll_at" placeholder="Enroll">
      </div>
    <div class="md-3">
        <label for="image" class="form-label">Image</label>
        <input type="file" id="image" name="image" class="form-control" onchange="previewImage(event)">
        <img id="imagePreview" class="mt-3" width="60"/>
        <button type="button" id="cancelImage" class="btn btn-danger btn-sm mt-2" style="display:none;" onclick="cancelPreview()">Cancel</button>
    </div>
    <button class="btn btn-primary col-md-1" type="submit">Add</button>    
</form>

@endsection


<script>
    function previewImage(event) {
        var input = event.target;
        var reader = new FileReader();
        var preview = document.getElementById('imagePreview');
        var cancelButton = document.getElementById('cancelImage');

        reader.onload = function() {
            preview.src = reader.result;
            preview.style.display = 'block';
            cancelButton.style.display = 'block'; // Show cancel button
        };

        if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        } else {
            //If no file is selected, hide the preview and cancel button
            preview.style.display = 'none';
            cancelButton.style.display = 'none';
        }
    }

    function cancelPreview() {
        var preview = document.getElementById('imagePreview');
        var cancelButton = document.getElementById('cancelImage');
        var imageInput = document.getElementById('image');

        preview.src = '#';
        preview.style.display = 'none';
        cancelButton.style.display = 'none';
        imageInput.value = ''; // Clear the file input
    }
</script>