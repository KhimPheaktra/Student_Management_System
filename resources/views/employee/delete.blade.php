@extends('layouts.admin')
@section('title','DElete Employee')
@section('content')


<h1>Employee</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Edit Employee</li>
    </ol>
        
<form id="deleteForm" class="row g-2" method="post" action="{{route('employee.delete',$employees->id)}}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="md-3">
        <label class="form-label">Id</label>
        <input disabled type="text" value="{{$employees->id}}" class="form-control" >
      </div>

    <div class="col-md-6">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" value="{{$employees->first_name}}" class="form-control" id="first_name" name="first_name" placeholder="Employee First Name">
      </div>
      <div class="col-md-6">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" value="{{$employees->last_name}}" class="form-control" id="last_name" name="last_name" placeholder="Employee Last Name">
      </div>
      <div class="col-md-6">
        <label for="dob" class="form-label">Date Of Birth</label>
        <input type="date" value="{{$employees->dob}}" class="form-control" id="dob" name="dob" placeholder="dob">
      </div>
      <div class="col-md-6">
        <label for="pob" class="form-label">Place Of Birth</label>
        <select class="form-select" aria-label="Default select example" name="pob">
            <option selected>Select Province</option>
            @foreach($provinces as $province)
            <option value="{{$province->id}}" {{$employees->pob == $province->id ? 'selected' : ''}}>{{$province->name}}</option>
            @endforeach

          </select>
      </div>
      <div class="col-md-6">
        <label for="gender_id" class="form-label">Gender</label>
        <select class="form-select" aria-label="Default select example" name="gender_id">
            <option selected>Select Gender</option>
            @foreach($genders as $gender)
            <option value="{{$gender->id}}" {{$employees->gender_id == $gender->id ? 'selected' : ''}}>{{$gender->name}}</option>
            @endforeach

          </select>
      </div>



      <div class="col-md-6">
        <label for="position_id" class="form-label">Position</label>
        <select class="form-select" aria-label="Default select example" name="position_id">
            <option selected>Select Position</option>
            @foreach($positions as $position)
            <option value="{{$position->id}}" {{$employees->position_id == $position->id ? 'selected' : ''}}>{{$position->name}}</option>
            @endforeach

          </select>
      </div>

      <div class="col-md-6">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" value="{{$employees->phone}}" class="form-control" id="phone" name="phone" placeholder="phone">
      </div>


      <div class="col-md-6">
        <label for="salary" class="form-label">Salary</label>
        <input type="number"
        value="{{ old('salary', isset($employees->salary_id) ? number_format(optional(App\Models\EmployeeSalaryModel::find($employees->salary_id))->salary, 2, '.', '') : '') }}"
        name="salary"
        id="salary"
        class="form-control"
        placeholder="Enter Salary">
    </div>
    <div class="col-md-6">
        <label for="image" class="form-label">Old Image</label>
        <input hidden type="file" id="image" name="image" class="form-control" onchange="previewImage(event)">
        <img src="{{$employees->image}}" class="mt-3" width="60"/>
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

    // Comfiem Delete
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