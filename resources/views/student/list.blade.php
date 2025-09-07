@extends('layouts.admin')
@section('title','Student')
@section('content')

@php
    $currentSort = request('sort');
    $currentOrder = request('order') ?? 'asc';
@endphp

    
<h1>Student</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Student</li>
    </ol>
        
<div class="row">

{{-- Search --}}
<div class="col-md-10">
    <div class="form-group">
      <form method="get" action="{{ url('/student') }}">
        <div class="input-group">
          <input type="text" class="form-controll" name="search" placeholder="Search..." value="{{isset($search) ? $search: ''}}">
          <button type="submit" class="btn btn-primary">Search</button>
        </div>
      </form>
    </div>
  </div>
    <div class="col-md-2">
        <a href="{{url('/student/add')}}" type="button" class="btn btn-primary mb-2">Add New</a>
    </div>
  </div>

<div class="table-container">
<table class="table text-center align-middle mt-2">
  <thead class="table-dark">
      <tr>
          <th scope="col">          
              <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'order' => ($currentSort === 'id' && $currentOrder === 'desc') ? 'asc' : 'desc']) }}"
              class="text-white text-decoration-none">
                Id
                <i class="fa-solid 
                    @if ($currentSort === 'id')
                        {{ $currentOrder === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down' }}
                    @else
                        fa-arrow-up-long text-secondary
                    @endif"></i>
            </a>
        </th>
          <th scope="col">
               <a href="{{ request()->fullUrlWithQuery(['sort' => 'student_code', 'order' => ($currentSort === 'student_code' && $currentOrder === 'desc') ? 'asc' : 'desc']) }}"
              class="text-white text-decoration-none">
                Code
                <i class="fa-solid 
                    @if ($currentSort === 'student_code')
                        {{ $currentOrder === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down' }}
                    @else
                        fa-arrow-up-long text-secondary
                    @endif"></i>
            </a>
          </th>
          <th scope="col">
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'first_name', 'order' => ($currentSort === 'first_name' && $currentOrder === 'asc') ? 'desc' : 'asc']) }}"
              class="text-white text-decoration-none">
                Name
                <i class="fa-solid 
                    @if ($currentSort === 'first_name')
                        {{ $currentOrder === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down' }}
                    @else
                        fa-arrow-up-long text-secondary
                    @endif"></i>
            </a>
        </th>
          <th scope="col">Date Of Birth</th>
          <th scope="col">Gender</th>
          <th scope="col">Phone</th>
          <th scope="col">Parent Phone</th>
          <th scope="col">Place Of Birth</th>
          <th scope="col">Enroll At</th>
          <th scope="col">Gen</th>
          <th scope="col">Major</th>
          <th scope="col">Image</th>
          <th scope="col">Status</th>
          <th scope="col">Action</th>
      </tr>
  </thead>
  <tbody>
      @forelse ($students as $student)
      <tr>
          <th scope="row">{{$student->id}}</th>
          <td>{{$student->student_code}}</td>
          <td>{{ $student->first_name . ' ' . $student->last_name }}</td>
          <td>{{ \Carbon\Carbon::parse($student->dob)->format('d M Y') }}</td>
          <td>
              @php
                  $gender = App\Models\GenderModel::find($student->gender_id);
                  echo $gender ? $gender->name : '';
              @endphp
          </td>
          <td>{{$student->phone}}</td>
          <td>{{$student->parent_phone}}</td>
          <td>
              @php
                  $province = App\Models\ProvinceModel::find($student->pob);
                  echo $province ? $province->name : '';
              @endphp
          </td>
          <td>
              {{\Carbon\Carbon::parse($student->enroll_at)->format('d M Y')}}
          </td>
          <td>
            @php 
            $gen = App\Models\GenerationModel::find($student->gen_id);
            echo $gen ? $gen->gen : '';
            @endphp
        </td>
        <td>
            @php 
            $major = App\Models\MajorModel::find($student->major_id);
            echo $major ? $major->name : '';
            @endphp
        </td>
          <td>
              <img src="{{asset($student->image)}}" alt="student image" width="40px" style="cursor: pointer;" onclick="openImageModal(this.src)">
          </td>
          <td>
              @if ($student->status == 'ACT')
                  <span class="badge text-bg-success">Active</span>
              @endif
          </td>
          <td>
              <a href="{{url('student\edit',$student->id)}}" type="button" class="btn btn-warning btn-sm">
                  <i class="fa-solid fa-pen-to-square"></i>
                  Edit
              </a>
              <a href="{{url('student\show',$student->id)}}" type="button" class="btn btn-danger btn-sm">
                  <i class="fa-solid fa-trash"></i>
                  Delete
              </a>
          </td>
      </tr>
      @empty
      <tr>
          <td colspan="12" class="text-center">
              <h3 class="text-danger">No students found</h3>
          </td>
      </tr>
      @endforelse
  </tbody>
</table>
</div>
    {{-- Pagination --}}
   {{$students->Links()}} 


   {{-- Pop up the image --}}
   <div id="imageModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.8); z-index:9999; justify-content:center; align-items:center;">
    <span onclick="closeImageModal()" style="position:absolute; top:10px; right:20px; color:white; font-size:30px; cursor:pointer;">&times;</span>
    <img id="modalImage" src="" style="max-width:90%; max-height:90%; border:5px solid white; border-radius:10px;">



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