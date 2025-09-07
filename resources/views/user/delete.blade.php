@extends('layouts.admin')
@section('title', 'Delete User')
@section('content')

<div class="container-fluid px-4">
    <h1 class="mt-4">Delete User</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">User Management</li>
    </ol>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0">Delete User Details</h5>
        </div>
        <div class="card-body">
            <form id="deleteForm" method="POST" action="{{ route('user.delete', $users->id) }}">
                @csrf   
                @method('DELETE')
                <!-- Employee (User Name) -->
                <div class="mb-3">
                    <label for="name" class="form-label">User Name</label>
                    <select class="form-select" name="name" required>
                        <option value="" disabled>Select User</option>
                        @foreach($employees as $employee)
                            @php
                                $fullName = $employee->first_name . ' ' . $employee->last_name;
                            @endphp
                            <option value="{{ $fullName }}" {{ $users->name === $fullName ? 'selected' : '' }}>
                                {{ $fullName }}
                            </option>
                        @endforeach
                    </select>
                    @error('name')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Role -->
                <div class="mb-3">
                    <label for="role_id" class="form-label">Role</label>
                    <select class="form-select" name="role_id" required>
                        <option value="" disabled>Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ $users->role_id == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" value="{{ old('email', $users->email) }}" class="form-control mt-1" type="email" name="email" required />
                    @error('email')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password (Optional) -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password (Leave blank to keep current)</label>
                    <input id="password" class="form-control mt-1" type="password" name="password" />
                    @error('password')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input id="password_confirmation" class="form-control mt-1" type="password" name="password_confirmation" />
                    @error('password_confirmation')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="mt-4">
                    <button class="btn btn-danger" onclick="confirmDelete()" type="button">Delete User</button>
                </div>
            </form>
        </div>
    </div>


<script>
    function confirmDelete() {
        Swal.fire({
            title: 'Are you sure you want to delete user ?',
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
