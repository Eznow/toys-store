@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg p-4 bg-white rounded">
                <h2 class="text-center mb-4 text-primary">Cập nhật thông tin cá nhân</h2>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('user.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Tên:</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="form-control form-control-lg" placeholder="Nhập tên của bạn">
                    </div>

                    <div class="form-group mb-3">
                        <label for="phone_number" class="form-label">Số điện thoại:</label>
                        <input type="text" name="phone_number" value="{{ $user->phone_number }}" class="form-control form-control-lg" placeholder="Nhập số điện thoại">
                    </div>

                    <div class="form-group mb-4">
                        <label for="address" class="form-label">Địa chỉ:</label>
                        <textarea name="address" class="form-control form-control-lg" rows="4" placeholder="Nhập địa chỉ">{{ $user->address }}</textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Cập nhật thông tin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
