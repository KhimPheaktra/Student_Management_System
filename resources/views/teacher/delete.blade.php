@extends('layouts.admin')
@section('title','Delete Teacher')
@section('content')


<h1>Teacher</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Delete Teacher</li>
    </ol>
     
    
<form class="row g-3" id="deleteForm" method="post" action="{{route('teacher.delete',$teachers->id)}}" enctype="multipart/form-data">
    @csrf
    @method('DELETE')
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
            <option selected>Select Province</option>
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
      
    <div class="md-3">
        <label for="image" class="form-label">Image</label>
        <img src="{{asset($teachers->image)}}" width="60" alt="">
        <input hidden type="file" value="{{$teachers->teacherName}}" id="image" name="image" class="form-control">
    </div>

    <button onclick="confirmDelete()" class="btn btn-danger col-md-1" type="button">Delete</button>    
</form>

@endsection

<script>
    function confirmDelete() {
        Swal.fire({
            title: 'Are you sure you want to delete this ?',
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
