@extends('layouts.admin')
@section('title','Term')
@section('content')

@php
    $currentSort = request('sort');
    $currentOrder = request('order') ?? 'asc';
@endphp
    

<h1>Term</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Term</li>
    </ol>
        

<div class="row">
{{-- Search --}}
  <div class="col-md-10">
    <div class="form-group">
      <form method="get" action="{{ url('/term') }}">
        <div class="input-group">
          <input type="text" class="form-controll" name="search" placeholder="Search..." value="{{isset($search) ? $search: ''}}">
          <button type="submit" class="btn btn-primary">Search</button>
        </div>
      </form>
    </div>
  </div>

  <div class="col-md-2">
    <a href="{{url('/term/add')}}" type="button" class="btn btn-primary mb-2">Add New</a>
    </div>
    
</div>


<div class="table-container">

<table class="table text-center align-middle mt-2">
    <thead class="table-dark">
      <tr>
        <th scope="col">            <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'order' => ($currentSort === 'id' && $currentOrder === 'desc') ? 'asc' : 'desc']) }}"
              class="text-white text-decoration-none">
                Id
                <i class="fa-solid 
                    @if ($currentSort === 'id')
                        {{ $currentOrder === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down' }}
                    @else
                        fa-arrow-up-long text-secondary
                    @endif"></i>
            </a></th>
        <th scope="col"><a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'order' => ($currentSort === 'name' && $currentOrder === 'asc') ? 'desc' : 'asc']) }}"
              class="text-white text-decoration-none">
                Term Name
                <i class="fa-solid 
                    @if ($currentSort === 'name')
                        {{ $currentOrder === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down' }}
                    @else
                        fa-arrow-up-long text-secondary
                    @endif"></i>
            </a></th>
        <th scope="col">Start Date</th>
        <th scope="col">End Date</th>
        <th scope="col">Gen</th>
        <th scope="col">Status</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
        @forelse($terms as $term)
        <tr>
            <th scope="row">{{$term->id}}</th>
            <td>{{$term->name}}</td>
            <td>{{$term->start_date}}</td>
            <td>{{$term->end_date}}</td>
            <td>
                @if ($term->status == 'ACT')
                  <span class="badge text-bg-success">Active</span>                  
                @endif
            </td>
      
            <td>
                <a href="{{route('term.edit',$term->id)}}" type="button" class="btn btn-warning btn-sm">
                  <i class="fa-solid fa-pen-to-square"></i>
                  Edit
                </a>
                <a href="{{route('term.show',$term->id)}}" type="button" class="btn btn-danger btn-sm">
                  <i class="fa-solid fa-trash"></i>
                  Delete
                </a>
            </td>
          </tr>
         @empty
         <tr>
          <td colspan="12" class="text-center">
          <h3 class="text-danger ">No Term found</h3>
          </td>
         </tr>
        
        @endforelse
    </tbody>
  </table>
</div>


    
   {{-- Pagination --}}
  {{$terms->Links()}}


@endsection