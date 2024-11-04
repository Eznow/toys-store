@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg p-4 bg-light rounded">
                <h2 class="text-center text-success mb-4">Lịch sử mua hàng</h2>
                <table class="table table-hover table-striped">
                    <thead class="table-success">
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Tổng giá</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ number_format($order->total, 0, ',', '.') }} VND</td>
                            <td>{{ $order->status }}</td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
