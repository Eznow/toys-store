@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/complaint.css') }}">


@section('content')
<div class="container">
    <h2>Danh sách khiếu nại</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Người khiếu nại</th>
                <th>Đơn hàng</th>
                <th>Mô tả</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($complaints as $complaint)
            <tr>
                <td>{{ $complaint->user->name }}</td>
                <td>#{{ $complaint->order->order_id }}</td>
                <td>{{ $complaint->description }}</td>
                <td><form action="{{ route('admin.complaints.updateStatus', $complaint->complaint_id) }}" method="POST">
    @csrf
    @method('PUT')
    <select name="status" class="form-control" onchange="this.form.submit();">
        <option value="pending" {{ $complaint->status == 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
    </select>
</form></td>
                <td>
                    <a href="{{ route('admin.complaints.show', $complaint->complaint_id) }}" class="btn btn-primary">Xem chi tiết</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>



@endsection
