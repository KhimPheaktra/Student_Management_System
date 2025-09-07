@extends('layouts.admin')
@section('title','Employee')
@section('content')

    
<h1>Employee</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Employee</li>
    </ol>
        
<div class="row">

{{-- Search --}}
<div class="col-md-10">
    <div class="form-group">
      <form method="get" action="{{ url('/employee') }}">
        <div class="input-group">
          <input type="text" class="form-controll" name="search" placeholder="Search..." value="{{isset($search) ? $search: ''}}">
          <button type="submit" class="btn btn-primary">Search</button>
        </div>
      </form>
    </div>
  </div>
    <div class="col-md-2">
        <a href="{{url('/employee/add')}}" type="button" class="btn btn-primary mb-2">Add New</a>
    </div>
  </div>


  
<div class="table-container">

<table class="table text-center align-middle mt-2">
  <thead class="table-dark">
      <tr>
          <th scope="col">Id</th>
          <th scope="col">Name</th>
          <th scope="col">Date Of Birth</th>
          <th scope="col">Gender</th>
          <th scope="col">Phone</th>
          <th scope="col">Place Of Birth</th>
          <th scope="col">Salary</th>
          <th scope="col">Image</th>
          <th scope="col">Status</th>
          <th scope="col">Action</th>
      </tr>
  </thead>

  <tbody>
      @forelse ($employees as $employee)
      <tr>
          <th scope="row">{{$employee->id}}</th>
          <td>{{ $employee->first_name . ' ' . $employee->last_name }}</td>
          <td>{{ \Carbon\Carbon::parse($employee->dob)->format('d M Y') }}</td>
          <td>
              @php
                  $gender = App\Models\GenderModel::find($employee->gender_id);
                  echo $gender ? $gender->name : '';
              @endphp
          </td>
          <td>{{$employee->phone}}</td>
          <td>
              @php
                  $province = App\Models\ProvinceModel::find($employee->pob);
                  echo $province ? $province->name : '';
              @endphp
          </td>
          <td>
            @php
                $salary = App\Models\EmployeeSalaryModel::find($employee->salary_id);
                echo $salary ? '$' . number_format($salary->salary, 2) : '';
            @endphp
        </td>
          <td>
              <img src="{{asset($employee->image)}}" alt="student image" width="40px" style="cursor: pointer;" onclick="openImageModal(this.src)">
          </td>
          <td>
              @if ($employee->status == 'ACT')
                  <span class="badge text-bg-success">Active</span>
              @endif
          </td>
          <td>
              <a href="{{route('employee.edit', $employee->id)}}" type="button" class="btn btn-warning btn-sm">
                  <i class="fa-solid fa-pen-to-square"></i>
                  Edit
              </a>
              <a href="{{route('employee.show',$employee->id)}}" type="button" class="btn btn-danger btn-sm">
                  <i class="fa-solid fa-trash"></i>
                  Delete
              </a>
          </td>
      </tr>
      @empty
      <tr>
          <td colspan="12" class="text-center">
              <h3 class="text-danger">No Employee found</h3>
          </td>
      </tr>
      @endforelse
  </tbody>
</table>
</div>

    {{-- Pagination --}}
   {{$employees->Links()}} 


   {{-- Pop up the image --}}
   <div id="imageModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.8); z-index:9999; justify-content:center; align-items:center;">
    <span onclick="closeImageModal()" style="position:absolute; top:10px; right:20px; color:white; font-size:30px; cursor:pointer;">&times;</span>
    <img id="modalImage" src="" style="max-width:90%; max-height:90%; border:5px solid white; border-radius:10px;">
</div>


{{-- Script for pop up the image --}}
<script>
    function openImageModal(src) {
        document.getElementById("modalImage").src = src;
        document.getElementById("imageModal").style.display = "flex";
    }

    function closeImageModal() {
        document.getElementById("imageModal").style.display = "none";
    }
</script>



@endsection