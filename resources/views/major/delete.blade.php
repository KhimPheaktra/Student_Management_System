@extends('layouts.admin')
@section('title','Delete Major')
@section('content')

<h1>Major</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Delete Major</li>
    </ol>
        
<form id="deleteForm" method="post" action="{{route('major.delete',$majors->id)}}" enctype="multipart/form-data">
    @csrf
    @method('DELETE')
    <div class="mb-3">
        <label for="name" class="form-label">Major Name</label>
        <input type="text" value="{{$majors->name}}" class="form-control" id="name" name="name" placeholder="Major Name">
      </div>
      
  <button class="btn btn-danger" type="button" onclick="confirmDelete()">Delete</button>    
</form>



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
@endsection
