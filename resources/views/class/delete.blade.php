@extends('layouts.admin')
@section('title','Delete Class')
@section('content')

<h1>Class</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Delete Class</li>
    </ol>
        
<form id="deleteForm" method="post" action="{{route('class.delete',$classes->id)}}" enctype="multipart/form-data">
    @csrf
    @method('DELETE')
    <div class="mb-3">
        <label for="name" class="form-label">Class Id</label>
        <input disabled type="text" value="{{$classes->id}}" class="form-control" id="name" name="name" placeholder="Class Name">
      </div>

    <div class="mb-3">
        <label for="name" class="form-label">Class Name</label>
        <input type="text" value="{{$classes->name}}" class="form-control" id="name" name="name" placeholder="Class Name">
      </div>
  <button class="btn btn-danger" onclick=" confirmDelete()" type="button" data-confirm-delete="true">Delete</button>    
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