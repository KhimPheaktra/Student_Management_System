@extends('layouts.admin')
@section('title', 'Add User')
@section('content')

<div class="container-fluid px-4">
    <h1 class="mt-4">Add User</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">User Management</li>
    </ol>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0">New User Details</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('user.create') }}">
                @csrf

                 <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">User Name</label>
                    <select class="form-select" name="name" required>
                        <option value="" disabled selected>Select User</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->first_name . ' ' . $employee->last_name }}">
                                {{ $employee->first_name . ' ' . $employee->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('name')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                 <!-- Role -->
                 <div class="mb-3">
                    <label for="name" class="form-label">Role</label>
                    <select class="form-select" name="name" required>
                        <option value="" disabled selected>Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id}}">
                                {{$role->name}}
                            </option>
                        @endforeach
                    </select>
                    @error('name')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="form-control mt-1" type="email" name="email" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="text-danger mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="form-control mt-1" type="password" name="password" required />
                    <x-input-error :messages="$errors->get('password')" class="text-danger mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="form-control mt-1" type="password" name="password_confirmation" required />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="text-danger mt-2" />
                </div>

                <!-- Submit -->
                <div class="mt-4">
                    <button class="btn btn-primary" type="submit">Add User</button>
                </div>
            </form>
        </div>
    </div>


@endsection
