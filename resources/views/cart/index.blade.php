@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Giỏ hàng của bạn</h2>
    @if ($cart && $cart->cartItems->isNotEmpty())
        <table class="table">
            <thead>
                <tr>
                    <th>Ảnh sản phẩm</th>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Thành tiền</th> <!-- Thành tiền cho từng sản phẩm -->
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart->cartItems as $item)
                    <tr data-item-id="{{ $item->id }}">
                        <td>
                            <a href="{{ route('products.show', $item->product->product_id) }}">
                                <img src="{{ asset('storage/' . optional($item->product)->image_url) }}" alt="{{ optional($item->product)->name }}" style="width: 50px; height: 50px;">
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('products.show', $item->product->product_id) }}">
                                {{ optional($item->product)->name }}
                            </a>
                        </td>
                        <td>
                            <div class="input-group quantity-group">
                                <button class="btn btn-outline-secondary decrease-qty" data-item-id="{{ $item->id }}">-</button>
                                <input type="number" class="form-control text-center quantity-input" data-item-id="{{ $item->id }}" value="{{ $item->quantity }}" min="1">
                                <button class="btn btn-outline-secondary increase-qty" data-item-id="{{ $item->id }}">+</button>
                            </div>
                        </td>
                        <td>{{ optional($item->product)->price ? number_format($item->product->price) : 'N/A' }} VND</td>
                        <td class="item-total">{{ optional($item->product)->price ? number_format($item->product->price * $item->quantity) : 'N/A' }} VND</td> <!-- Thành tiền của sản phẩm này -->
                        <td>
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Hiển thị tổng tiền của tất cả sản phẩm -->
        <div class="text-right">
            <h4>Tổng tiền: <span id="cart-total">{{ number_format($total) }} VND</span></h4> <!-- Tổng tiền của giỏ hàng -->
        </div>

        <!-- Nút thanh toán -->
        <div class="text-right mt-3">
            <a href="{{ route('checkout.index') }}" class="btn btn-success">Thanh toán ngay</a>
        </div>
    @else
        <p>Giỏ hàng của bạn đang trống.</p>
    @endif
</div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    // Thiết lập AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Cập nhật số lượng và tổng tiền khi bấm nút tăng/giảm số lượng
    function updateCart(itemId, quantity) {
        $.ajax({
            url: '{{ route('cart.update') }}',
            method: 'POST',
            data: {
                item_id: itemId,
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    $('tr[data-item-id="' + itemId + '"] .item-total').text(response.itemTotal + ' VND');
                    $('#cart-total').text(response.cartTotal + ' VND');
                } else {
                    alert('Có lỗi xảy ra khi cập nhật số lượng.');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }

    // Xử lý khi nhấn nút giảm số lượng
    $('.decrease-qty').on('click', function() {
        const itemId = $(this).data('item-id');
        let quantityInput = $('input[data-item-id="' + itemId + '"]');
        let quantity = parseInt(quantityInput.val());

        if (quantity > 1) {
            quantity--;
            quantityInput.val(quantity);
            updateCart(itemId, quantity);
        }
    });

    // Xử lý khi nhấn nút tăng số lượng
    $('.increase-qty').on('click', function() {
        const itemId = $(this).data('item-id');
        let quantityInput = $('input[data-item-id="' + itemId + '"]');
        let quantity = parseInt(quantityInput.val());

        quantity++;
        quantityInput.val(quantity);
        updateCart(itemId, quantity);
    });

    // Cập nhật trực tiếp khi thay đổi số lượng bằng input
    $('.quantity-input').on('change', function() {
        const itemId = $(this).data('item-id');
        let quantity = parseInt($(this).val());

        if (quantity < 1) {
            $(this).val(1);
            quantity = 1;
        }

        updateCart(itemId, quantity);
    });
});
</script>
