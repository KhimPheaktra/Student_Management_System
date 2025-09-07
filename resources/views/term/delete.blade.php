@extends('layouts.admin')
@section('title','Delete Term')
@section('content')

<h1>Term</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Delete Term</li>
    </ol>
        
<form id="deleteForm" method="post" action="{{route('term.delete',$terms->id)}}" enctype="multipart/form-data">
    @csrf
    @method('DELETE')
    <div class="mb-3">
        <label for="name" class="form-label">Term Id</label>
        <input disabled type="text" value="{{$terms->id}}" class="form-control" id="name" name="name" placeholder="Term Name">
      </div>
    <div class="mb-3">
        <label for="name" class="form-label">Term Name</label>
        <input type="text" value="{{$terms->name}}" class="form-control" id="name" name="name" placeholder="Term Name">
      </div>
      <div class="mb-3">
        <label for="start_date" class="form-label">Start Date</label>
        <input type="date" value="{{$terms->start_date}}" class="form-control" id="start_date" name="start_date">
      </div>
      <div class="mb-3">
        <label for="end_date" class="form-label">End Date</label>
        <input type="date" value="{{$terms->end_date}}" class="form-control" id="end_date" name="end_date">
      </div>

  <button class="btn btn-danger" onclick="confirmDelete()" type="button">Edit</button>    
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
