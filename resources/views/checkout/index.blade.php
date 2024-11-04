@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Xác nhận thanh toán</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cart->cartItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->product->price) }} VND</td>
                    <td>{{ number_format($item->product->price * $item->quantity) }} VND</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-right">
        <!-- Hiển thị tổng tiền trước giảm giá -->
        <h4>Tổng tiền: {{ number_format($cart->cartItems->sum(fn($item) => $item->product->price * $item->quantity)) }} VND</h4>
    </div>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#discountModal">
    Danh sách mã giảm giá có thể áp dụng
</button>

    <!-- Hiển thị thông báo giảm giá nếu có -->
    @if (session('discount_amount'))
        <div class="alert alert-success">Mã giảm giá đã được áp dụng: {{ number_format(session('discount_amount')) }} VND</div>
        <h4>Tổng sau giảm: 
            {{ number_format($cart->cartItems->sum(fn($item) => $item->product->price * $item->quantity) - session('discount_amount')) }} VND
        </h4>
    @endif
    @if (session('discount'))
    <!-- <p>Phần trăm giảm giá: {{ session('discount') }}%</p> -->
@endif


    <!-- Nút xác nhận thanh toán -->
    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success">Xác nhận thanh toán</button>
    </form>
</div>

<!-- <div class="modal fade" id="discountModal" tabindex="-1" aria-labelledby="discountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="discountModalLabel">Danh sách mã giảm giá có thể áp dụng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="discount-list">
                    <!-- Các mã giảm giá sẽ được load động vào đây -->
                <!-- </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="applySelectedDiscount()">Xác nhận</button>
            </div>
        </div>
    </div>
</div> --> -->

<div class="modal fade" id="discountModal" tabindex="-1" aria-labelledby="discountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="discountModalLabel">Danh sách mã giảm giá có thể áp dụng</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="discount-list" class="list-group">
                    <!-- Các mã giảm giá sẽ được load động vào đây -->
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-success" onclick="applySelectedDiscount()">Xác nhận</button>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('discountModal').addEventListener('show.bs.modal', function () {
    fetch('/discounts/available')
        .then(response => response.json())
        .then(data => {
            const discountList = document.getElementById('discount-list');
            discountList.innerHTML = '';
            data.discounts.forEach(discount => {
                // const validUntil = new Date(discount.valid_until);
            const minOrderValue = discount.min_order_value ? `${new Intl.NumberFormat('vi-VN').format(discount.min_order_value)} VND` : "Không yêu cầu";
            // const remainingDays = Math.ceil((validUntil - new Date()) / (1000 * 60 * 60 * 24));
                discountList.innerHTML += `
                    <li>
                        <input type="radio" name="discount" value="${discount.code}">
                          <strong>${discount.code}</strong> - Giảm ${discount.discount_percentage}% cho đơn hàng thanh toán ít nhất ${minOrderValue} - Thời gian áp dụng  tới hết ${discount.valid_until}
                    </li>
                `;
            });
        });
});

});
function applySelectedDiscount() {
    const selectedDiscount = document.querySelector('input[name="discount"]:checked');
    if (selectedDiscount) {
        fetch('/checkout/applyDiscount', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ discount_code: selectedDiscount.value })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Không thể áp dụng mã giảm giá.');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Áp dụng mã giảm giá thành công!');
                window.location.reload();
            } else {
                alert('Không thể áp dụng mã giảm giá.');
            }
        })
        .catch(error => {
            console.error('Lỗi:', error);
            alert('Đã xảy ra lỗi khi áp dụng mã giảm giá.');
        });
    } else {
        // alert('Vui lòng chọn mã giảm giá.');
    }
}

    const selectedDiscount = document.querySelector('input[name="discount"]:checked');
    if (selectedDiscount) {
        fetch('/checkout/applyDiscount', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ discount_code: selectedDiscount.value })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Áp dụng mã giảm giá thành công!');
                window.location.reload();
            } else {
                alert('Không thể áp dụng mã giảm giá.');
            }
        });
    } else {
        // alert('Vui lòng chọn mã giảm giá.');
    }

</script>

<style>
    /* Tùy chỉnh cho modal */
    .modal-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }
    .list-group-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .list-group-item input[type="radio"] {
        margin-right: 10px;
    }
</style>