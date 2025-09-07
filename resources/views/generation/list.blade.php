@extends('layouts.admin')
@section('title','Generation')
@section('content')


@php
    $currentSort = request('sort');
    $currentOrder = request('order') ?? 'asc';
@endphp

    
<h1>Generation</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Generation</li>
    </ol>
        

<div class="row">
{{-- Search --}}
  <div class="col-md-10">
    <div class="form-group">
      <form method="get" action="{{ url('/generation') }}">
        <div class="input-group">
          <input type="text" class="form-controll" name="search" placeholder="Search..." value="{{isset($search) ? $search: ''}}">
          <button type="submit" class="btn btn-primary">Search</button>
        </div>
      </form>
    </div>
  </div>

  <div class="col-md-2">
    <a href="{{url('/generation/add')}}" type="button" class="btn btn-primary mb-2">Add New</a>
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
          <a href="{{ request()->fullUrlWithQuery(['sort' => 'gen', 'order' => ($currentSort === 'gen' && $currentOrder === 'asc') ? 'desc' : 'asc']) }}"
              class="text-white text-decoration-none">
                Generation
                <i class="fa-solid 
                    @if ($currentSort === 'gen')
                        {{ $currentOrder === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down' }}
                    @else
                        fa-arrow-up-long text-secondary
                    @endif"></i>
            </a>

        </th>
        <th scope="col">
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'year', 'order' => ($currentSort === 'year' && $currentOrder === 'asc') ? 'desc' : 'asc']) }}"
              class="text-white text-decoration-none">
                Year
                <i class="fa-solid 
                    @if ($currentSort === 'year')
                        {{ $currentOrder === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down' }}
                    @else
                        fa-arrow-up-long text-secondary
                    @endif"></i>
            </a>
        </th>
        <th scope="col">Status</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
        @forelse($generations as $gen)
        <tr>
            <th scope="row">{{$gen->id}}</th>
            <td>{{$gen->gen}}</td>
            <td>{{$gen->year}}</td>
            <td>
                @if ($gen->status == 'ACT')
                  <span class="badge text-bg-success">Active</span>                  
                @endif
            </td>
      
            <td>
                <a href="{{route('generation.edit',$gen->id)}}" type="button" class="btn btn-warning btn-sm">
                  <i class="fa-solid fa-pen-to-square"></i>
                  Edit
                </a>
                <a href="{{route('generation.show',$gen->id)}}" type="button" class="btn btn-danger btn-sm">
                  <i class="fa-solid fa-trash"></i>
                  Delete
                </a>
            </td>
          </tr>
         @empty
         <tr>
          <td colspan="12" class="text-center">
          <h3 class="text-danger ">No Generation found</h3>
          </td>
         </tr>
        
        @endforelse
    </tbody>
  </table>
</div>
    
   {{-- Pagination --}}
  {{$generations->Links()}}


@endsection