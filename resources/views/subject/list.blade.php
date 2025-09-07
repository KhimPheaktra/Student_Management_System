@extends('layouts.admin')
@section('title','Subject')
@section('content')

@php
    $currentSort = request('sort');
    $currentOrder = request('order') ?? 'asc';
@endphp

    
<h1>Subject</h1>
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Subject</li>
    </ol>
        

<div class="row">
{{-- Search --}}
  <div class="col-md-10">
    <div class="form-group">
      <form method="get" action="{{ url('/subject') }}">
        <div class="input-group">
          <input type="text" class="form-controll" name="search" placeholder="Search..." value="{{isset($search) ? $search: ''}}">
          <button type="submit" class="btn btn-primary">Search</button>
        </div>
      </form>
    </div>
  </div>

  <div class="col-md-2">
    <a href="{{url('/subject/add')}}" type="button" class="btn btn-primary mb-2">Add New</a>
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
                Subject Name
                <i class="fa-solid 
                    @if ($currentSort === 'name')
                        {{ $currentOrder === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down' }}
                    @else
                        fa-arrow-up-long text-secondary
                    @endif"></i>
            </a></th>
        <th scope="col">Full Score</th>
        <th scope="col">Term</th>
        <th scope="col">Created At</th>
        <th scope="col">Status</th>
        <th scope="col">Active</th>
      </tr>
    </thead>
    <tbody>
        @forelse($subjects as $subject)
        <tr>
            <th scope="row">{{$subject->id}}</th>
            <td>{{$subject->name}}</td>
            <td>{{$subject->full_score}}</td>
            <td>
              @php 
                $term = App\Models\TermModel::find($subject->term_id);
                echo $term ? $term->name : '';
            @endphp
            </td>
            <td>{{$subject->created_at}}</td>
            <td>
              @if ($subject->status == 'ACT')
                <span class="badge text-bg-success">Active</span>                  
              @endif
            </td>
            <td>
                <a href="{{route('subject.edit',$subject->id)}}" type="button" class="btn btn-warning btn-sm">
                  <i class="fa-solid fa-pen-to-square"></i>
                  Edit
                </a>
                <a href="{{route('subject.show',$subject->id)}}" type="button" class="btn btn-danger btn-sm">
                  <i class="fa-solid fa-trash"></i>
                  Delete
                </a>
            </td>
          </tr>
         @empty
         <tr>
          <td colspan="12" class="text-center">
          <h3 class="text-danger ">No subject found</h3>
          </td>
         </tr>
        
        @endforelse
    </tbody>
  </table>
</div>


    
   {{-- Pagination --}}
  {{$subjects->Links()}}


@endsection