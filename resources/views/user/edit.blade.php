@extends('layouts.admin')
@section('title', 'Edit User')
@section('content')

<div class="container-fluid px-4">
    <h1 class="mt-4">Edit User</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">User Management</li>
    </ol>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0">Edit User Details</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('user.update', $users->id) }}">
                @csrf   
                @method('PUT')
                <div class="input-group">  
                    <!-- Text input for name (pre-filled with current user name) -->  
                    <input type="text" id="name" name="name" class="form-control" 
                        placeholder="Enter or select user name" value="{{ old('name', $users->name) }}" required>  

                    <!-- Employee select dropdown -->  
                    <select id="employeeSelect" class="form-select" style="max-width: 200px;">  
                        <option value="">Select Employee</option>  
                        @foreach($employees as $employee)  
                            @php
                                $fullName = $employee->first_name . ' ' . $employee->last_name;
                            @endphp
                            <option value="{{ $fullName }}" {{ $fullName === $users->name ? 'selected' : '' }}>  
                                {{ $fullName }}  
                            </option>  
                        @endforeach  
                    </select>  
                </div>  

                @error('name')  
                    <div class="text-danger mt-1">{{ $message }}</div>  
                @enderror  
                <!-- Employee (User Name) -->
                {{-- <div class="mb-3">
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
                </div> --}}

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
                    <button class="btn btn-warning" type="submit">Update User</button>
                </div>
            </form>
        </div>
    </div>

  
<script>
document.getElementById('employeeSelect').addEventListener('change', function() {
    document.getElementById('name').value = this.value;
});
</script>
@endsection
