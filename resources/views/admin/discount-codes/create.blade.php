<!-- resources/views/admin/discount-codes/create.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Thêm mã giảm giá mới</h2>

    <form action="{{ route('discount-codes.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="code">Mã giảm giá:</label>
            <input type="text" name="code" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="discount_percentage">Phần trăm giảm:</label>
            <input type="number" name="discount_percentage" class="form-control" min="0" max="100" required>
        </div>
        <div class="form-group">
            <label for="min_order_value">Giá trị đơn hàng tối thiểu:</label>
            <input type="number" name="min_order_value" class="form-control" min="0" required>
        </div>
        <div class="form-group">
            <label for="valid_from">Thời gian bắt đầu:</label>
            <input type="date" name="valid_from" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="valid_until">Thời gian kết thúc:</label>
            <input type="date" name="valid_until" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="usage_limit">Giới hạn sử dụng:</label>
            <input type="number" name="usage_limit" class="form-control" min="1" required>
        </div>
        <button type="submit" class="btn btn-success">Tạo mã giảm giá</button>
    </form>
</div>
@endsection
