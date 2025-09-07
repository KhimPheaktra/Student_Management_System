@extends('layouts.admin')
@section('title','Edit Teacher')
@section('content')


<h1>Teacher</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Edit Teacher</li>
    </ol>
        
<form class="row g-3" method="post" action="{{route('teacher.update',$teachers->id)}}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="col-12">
        <label for="id" class="form-label">Teacher Id</label>
        <input disabled type="text" value="{{$teachers->id}}" class="form-control">
      </div>

      <div class="col-md-6">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" value="{{$teachers->first_name}}" class="form-control" id="first_name" name="first_name" placeholder="Teacher First Name">
      </div>
      <div class="col-md-6">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" value="{{$teachers->last_name}}" class="form-control" id="last_name" name="last_name" placeholder="Teacher Last Name">
      </div>
      <div class="col-md-6">
        <label for="dob" class="form-label">Date Of Birth</label>
        <input type="date" value={{$teachers->dob}} class="form-control" id="dob" name="dob" placeholder="dob">
      </div>
      <div class="col-md-6">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" value="{{$teachers->phone}}" class="form-control" id="phone" name="phone" placeholder="phone">
      </div>

      <div class="col-md-6">
        <label for="salary" class="form-label">Salary</label>
        <input type="number"
        value="{{ old('salary', isset($teachers->salary_id) ? number_format(optional(App\Models\TeacherSalaryModel::find($teachers->salary_id))->salary, 2, '.', '') : '') }}"
        name="salary"
        id="salary"
        class="form-control"
        placeholder="Enter Salary">
 
    </div>
    
    
    

      <div class="col-md-6">
        <label for="gender_id" class="form-label">Gender</label>
        <select class="form-select" aria-label="Default select example" name="gender_id">
            <option selected>Select Gender</option>
            @foreach($genders as $gender)

            <option value="{{$gender->id}}" {{ $teachers->gender_id == $gender->id ? 'selected' : '' }}>{{$gender->name}}</option>
            @endforeach
          </select>
      </div>
    
    <div class="col-md-6">
      <label for="pob" class="form-label">Place Of Birth</label>
      <select class="form-select" aria-label="Default select example" name="pob">
          <option selected>Select Gender</option>
          @foreach($provinces as $province)

          <option value="{{$province->id}}" {{ $teachers->pob == $province->id ? 'selected' : '' }}>{{$province->name}}</option>
          @endforeach
        </select>
    </div>

    <div class="col-md-6">
      <label for="position_id" class="form-label">Position</label>
      <select class="form-select" aria-label="Default select example" name="position_id">
          <option selected>Select Position</option>
          @foreach($positions as $position)
          <option value="{{$position->id}}" {{$teachers->position_id == $position->id ? 'selected' : ''}}>{{$position->name}}</option>
          @endforeach

        </select>
    </div>

    <div class="col-md-6">
        <label for="oldImage" class="form-label">Old Image</label>
        <img src="{{ asset($teachers->image) }}" width="60" alt="">
        <input type="hidden" name="old_image" value="{{ $teachers->image }}">
    </div>
    
    <div class="col-md-6">
        <label for="image" class="form-label">New Image</label>
        <img src="" id="imagePreview" width="60" alt="">
        <input type="file" id="image" name="image" class="form-control mt-2" onchange="previewImage(event)">
        <button type="button" id="cancelImage" class="btn btn-danger btn-sm mt-2" style="display:none;" onclick="cancelPreview()">Cancel</button>
    </div>

    <button class="btn btn-warning col-md-1" type="submit">Edit</button>    
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