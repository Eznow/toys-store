<!-- resources/views/admin/discount-codes/edit.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Chỉnh sửa mã giảm giá</h2>

    <form action="{{ route('discount-codes.update', $discountCode->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="code">Mã giảm giá:</label>
            <input type="text" name="code" class="form-control" value="{{ $discountCode->code }}" required>
        </div>
        <div class="form-group">
            <label for="discount_percentage">Phần trăm giảm:</label>
            <input type="number" name="discount_percentage" class="form-control" min="0" max="100" value="{{ $discountCode->discount_percentage }}" required>
        </div>
        <div class="form-group">
            <label for="min_order_value">Giá trị đơn hàng tối thiểu:</label>
            <input type="number" name="min_order_value" class="form-control" min="0" value="{{ $discountCode->min_order_value }}" required>
        </div>
        <div class="form-group">
            <label for="valid_from">Thời gian bắt đầu:</label>
            <input type="date" name="valid_from" class="form-control" value="{{ \Carbon\Carbon::parse($discountCode->valid_from)->format('d-m-Y') }}" required>
        </div>
        <div class="form-group">
            <label for="valid_until">Thời gian kết thúc:</label>
            <input type="date" name="valid_until" class="form-control" value="{{ \Carbon\Carbon::parse($discountCode->valid_until)->format('d-m-Y') }}" required>
        </div>
        <div class="form-group">
            <label for="usage_limit">Giới hạn sử dụng:</label>
            <input type="number" name="usage_limit" class="form-control" min="1" value="{{ $discountCode->usage_limit }}" required>
        </div>
        <button type="submit" class="btn btn-success">Cập nhật mã giảm giá</button>
    </form>
</div>
@endsection
