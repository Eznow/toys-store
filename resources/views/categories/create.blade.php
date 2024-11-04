@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Thêm danh mục mới</h2>
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="name">Tên danh mục:</label>
            <input type="text" name="name" class="form-control" placeholder="Nhập tên danh mục" required>
        </div>

        <button type="submit" class="btn btn-primary">Thêm danh mục</button>
    </form>
</div>
@endsection
