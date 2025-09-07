@extends('layouts.admin')
@section('title','Add Generation')
@section('content')

<h1>Generation</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Add Generation</li>
    </ol>
        
<form id="deleteForm" method="post" action="{{route('generation.delete',$generations->id)}}" enctype="multipart/form-data">
    @csrf
    @method('DELETE')
    <div class="mb-3">
        <label for="gen" class="form-label">Generation</label>
        <input type="text" value="{{$generations->gen}}" class="form-control" id="gen" name="gen" placeholder="Generation" required>
      </div>
        <div class="mb-3">
        <label for="year" class="form-label">Year</label>
<input type="date" value="{{ $generations->year ? $generations->year . '-01-01' : '' }}" class="form-control" id="year" name="year" placeholder="Year" required>
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