@extends('layouts.admin')
@section('title','Delete Role')
@section('content')

<h1>Role</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Delete Role</li>
    </ol>
        
<form id="deleteForm" method="post" action="{{route('role.delete',$roles->id)}}" enctype="multipart/form-data">
    @csrf
    @method('DELETE')
        <div class="mb-3">
        <label class="form-label">Role Name</label>
        <input type="text" disabled value="{{$roles->id}}" class="form-control" >
      </div>

    <div class="mb-3">
        <label for="name" class="form-label">Role Name</label>
        <input type="text" value="{{$roles->name}}" class="form-control" id="name" name="name" >
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


