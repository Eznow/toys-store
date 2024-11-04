@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Danh sách Khiếu nại của Bạn</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Đơn hàng</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($complaints as $complaint)
            <tr>
                <td>{{ $complaint->complaint_id }}</td>
                <td>#{{ $complaint->order_id }}</td>
                <td>{{ $complaint->status }}</td>
                <td>{{ $complaint->created_at }}</td>
                <td>
                    <a href="{{ route('complaints.user.show', $complaint->complaint_id) }}" class="btn btn-primary">Xem chi tiết</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
