@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Quản lý người dùng</h2>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên</th>
                <th>Email</th>
                <th>Vai trò hiện tại</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>
                    <!-- Form để thay đổi vai trò người dùng -->
                    <form action="{{ route('admin.users.changeRole', $user) }}" method="POST">
                        @csrf
                        <select name="role" class="form-control" onchange="this.form.submit()">
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="seller" {{ $user->role == 'seller' ? 'selected' : '' }}>Seller</option>
                            <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Customer</option>
                        </select>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
