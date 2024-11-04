@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Lịch sử mua hàng</h2>

    @if ($orders->isNotEmpty())
        @foreach ($orders as $order)
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Đơn hàng #{{ $order->order_id }}</h5>
                    <small>Tổng tiền: {{ number_format($order->total_price) }} VND</small>
                    <small class="float-right">Trạng thái: {{ $order->status }}</small>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <a href="{{ route('products.show', $item->product->product_id) }}">
                                            {{ $item->product->name }}
                                        </a>
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->price) }} VND</td>
                                    <td>{{ number_format($item->price * $item->quantity) }} VND</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @php
                        $complaint = $order->complaints->firstWhere('order_id', $order->order_id);
                    @endphp

                    @if ($complaint)
                        <div class="alert mt-3 {{ $complaint->status == 'resolved' ? 'alert-success' : 'alert-warning' }}">
                            <strong>Trạng thái khiếu nại:</strong> {{ $complaint->status }}
                            <a href="{{ route('complaints.show', $complaint->complaint_id) }}" class="btn btn-info btn-sm float-right">
                                Xem chi tiết
                            </a>
                        </div>
                    @else
                        <button class="btn btn-danger mt-2" data-toggle="modal" data-target="#complaintModal{{$order->order_id}}">
                            Gửi khiếu nại
                        </button>
                    @endif
                </div>
            </div>

            <!-- Modal to file a new complaint -->
            <div class="modal fade" id="complaintModal{{$order->order_id}}" tabindex="-1" role="dialog" aria-labelledby="complaintModalLabel{{$order->order_id}}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="{{ route('complaints.store', $order->order_id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="complaintModalLabel{{$order->order_id}}">Khiếu nại đơn hàng #{{ $order->order_id }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="description">Mô tả khiếu nại</label>
                                    <textarea name="description" id="description" class="form-control" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="video">Tải lên video chứng minh (tùy chọn)</label>
                                    <input type="file" name="video" id="video" class="form-control-file" accept="video/*">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                <button type="submit" class="btn btn-primary">Gửi khiếu nại</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <p>Bạn chưa mua sản phẩm nào.</p>
    @endif
</div>
@endsection
