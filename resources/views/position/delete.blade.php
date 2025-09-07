@extends('layouts.admin')
@section('title','Delete Position')
@section('content')

<h1>Term</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Delete Position</li>
    </ol>
        
<form id="deleteForm" method="post" action="{{route('position.delete',$positions->id)}}" enctype="multipart/form-data">
    @csrf
    @method('DELETE')
    <div class="mb-3">
        <label for="name" class="form-label">Position Name</label>
        <input type="text" value="{{$positions->name}}" class="form-control" id="name" name="name" placeholder="Position Name">
      </div>
  <button class="btn btn-danger" tonclick="confirmDelete()" type="button" data-confirm-delete="true">Delete</button>    
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