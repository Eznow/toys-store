<!-- resources/views/admin/discount-codes/index.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Danh sách mã giảm giá</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mã giảm giá</th>
                <th>Phần trăm giảm</th>
                <th>Giá trị đơn hàng tối thiểu</th>
                <th>Thời gian bắt đầu</th>
                <th>Thời gian kết thúc</th>
                <th>Giới hạn sử dụng</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($discountCodes as $discountCode)
                <tr>
                    <td>{{ $discountCode->id }}</td>
                    <td>{{ $discountCode->code }}</td>
                    <td>{{ $discountCode->discount_percentage }}%</td>
                    <td>{{ number_format($discountCode->min_order_value) }} Đ</td>
                    <td>{{ \Carbon\Carbon::parse($discountCode->valid_from)->format('d-m-Y') }}</td>
<td>{{ \Carbon\Carbon::parse($discountCode->valid_until)->format('d-m-Y') }}</td>

                    <td>{{ $discountCode->usage_limit }}</td>
                    <td>
                        <a href="{{ route('discount-codes.edit', $discountCode->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <form action="{{ route('discount-codes.destroy', $discountCode->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
