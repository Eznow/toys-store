@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/btn_atc.css') }}">
<link rel="stylesheet" href="{{ asset('css/show.css') }}">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ asset('storage/' . $product->image_url) }}" class="img-fluid" alt="{{ $product->name }}">
        </div>
        <div class="col-md-6">
            <h2 class="text-primary">{{ $product->name }}</h2>
            @if($averageRating)
                <p>Đánh giá trung bình: {{ number_format($averageRating, 1) }} / 5 <i class="fa fa-star" style="color: #fbc02d;"></i></p>
            @else
                <p>Chưa có đánh giá</p>
            @endif
            <p class="text-muted">{{ $product->description }}</p>
            <p class="text-primary"><strong>{{ number_format($product->price) }} VND</strong></p>
            <!-- Kiểm tra xem người dùng là admin hoặc seller -->
            @if(auth()->user() && (auth()->user()->role == 'admin' || auth()->user()->role == 'seller'))
                <!-- Nút Sửa thông tin sản phẩm -->
                <button class="btn btn-warning" data-toggle="modal" data-target="#editProductModal">Sửa thông tin sản phẩm</button>
                
                <!-- Form Vô Hiệu Hóa Sản Phẩm -->
<form id="toggleStatusForm" action="{{ route('products.toggleStatus', $product->product_id) }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- Nút mở modal xác nhận vô hiệu hóa -->
@if ($product->status === 'active')
    <!-- Nếu sản phẩm đang hoạt động, hiển thị nút Vô hiệu hóa -->
    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#disableModal{{$product->product_id}}">
        <i class="fas fa-ban"></i> Vô hiệu hóa
    </button>
@else
    <!-- Nếu sản phẩm đã vô hiệu hóa, hiển thị nút Bỏ vô hiệu hóa -->
    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#disableModal{{$product->product_id}}">
        <i class="fas fa-check"></i> Bỏ vô hiệu hóa
    </button>
@endif
            @else
            <div class="form-group">
                <label for="quantity">Số lượng:</label>
                <div class="input-group">
                    <button type="button" class="btn btn-outline-secondary" id="decrease-qty">-</button>
                    <input type="number" id="quantity" name="quantity" class="form-control text-center" value="1" min="1">
                    <button type="button" class="btn btn-outline-secondary" id="increase-qty">+</button>
                </div>
            </div>
            @if($product->status === 'disabled')
    <!-- Sản phẩm đã bị vô hiệu hóa -->
    <p>Sản phẩm này đã bị vô hiệu hóa và không thể mua được.</p>
@else
            <button class="add-to-cart-button" id="add-to-cart-btn" data-product-id="{{ $product->product_id }}">
                <svg class="add-to-cart-box box-1" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="24" height="24" rx="2" fill="#ffffff"/></svg>
                <svg class="add-to-cart-box box-2" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="24" height="24" rx="2" fill="#ffffff"/></svg>
                <svg class="cart-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                <svg class="tick" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"/><path fill="#ffffff" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM9.29 16.29L5.7 12.7c-.39-.39-.39-1.02 0-1.41.39-.39 1.02-.39 1.41 0L10 14.17l6.88-6.88c.39-.39 1.02-.39 1.41 0 .39.39.39 1.02 0 1.41l-7.59 7.59c-.38.39-1.02.39-1.41 0z"/></svg>
                <span class="add-to-cart">Thêm vào giỏ hàng</span>
                <span class="added-to-cart">Đã thêm vào giỏ hàng</span>
            </button>
            @endif
            @endif
        </div>
    </div>
    <div class="row">
    <div class="col-md-12">
    @auth
@php
    $isAdminOrSeller = auth()->user()->role == 'admin' || auth()->user()->role == 'seller';
    $hasPurchased = \App\Models\OrderItem::whereHas('order', function ($query) {
        $query->where('user_id', auth()->id());
    })->where('product_id', $product->product_id)->exists();
@endphp

<form action="{{ route('products.addReview', ['id' => $product->product_id]) }}" method="POST">
        @csrf

        @if($isAdminOrSeller)
            <input type="hidden" name="rating" value="0" />
            <div class="form-group">
                <label for="review">Bình luận:</label>
                <textarea name="review" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Gửi</button>
        @elseif($hasPurchased)
            <div class="form-group">
                <label for="rating">Đánh giá của bạn:</label>
                <div class="rating">
                    @for ($i = 5; $i >= 1; $i--)
                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" 
                               {{ $userReview && $userReview->rating == $i ? 'checked' : '' }} />
                        <label for="star{{ $i }}" class="star" data-value="{{ $i }}">
                            <i class="far fa-star"></i>
                        </label>
                    @endfor
                </div>
            </div>

            <div class="form-group">
                <label for="review">Bình luận:</label>
                <textarea name="review" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Gửi</button>
        @else
            <p>Bạn cần mua sản phẩm để có thể đánh giá.</p>
        @endif
    </form>


@endauth

<h3>Bình luận</h3>
@foreach ($product->reviews as $review)
    <div class="review">
    @if ($review->rating==0&&$review->review=='Bình luận đã bị quản trị viên xóa vì vi phạm nội quy')
            @for ($i = $review->rating; $i >= 1; $i--)
                        <i class="fa-solid fa-star" style="color: #fbc02d"></i>
            @endfor
        @elseif ($review->review=='')
        
        @else
        <div><strong>{{ $review->user->name }}</strong></div>
        @if ($review->rating)
            @for ($i = $review->rating; $i >= 1; $i--)
                        <i class="fa-solid fa-star" style="color: #fbc02d"></i>
                    @endfor
        @else
        <p style="font-weight: bold; color: red;">
                Nhân viên bán hàng
            </p>
        @endif
        <p>{{ $review->review }}</p>
    <hr>
        @if ($isAdminOrSeller)
            <form action="{{ route('reviews.destroy', $review->review_id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa bình luận này không?')" class="btn btn-danger btn-sm">Xóa</button>
            </form>
        @endif
        @endif
    </div>
@endforeach
        </div>
    </div>
</div>

<!-- Modal chỉnh sửa sản phẩm (dành cho admin hoặc seller) -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Chỉnh sửa sản phẩm</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('products.update', $product->product_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Tên sản phẩm:</label>
                        <input type="text" class="form-control" name="name" value="{{ $product->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Mô tả:</label>
                        <textarea class="form-control" name="description" required>{{ $product->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="price">Giá:</label>
                        <input type="number" class="form-control" name="price" value="{{ $product->price }}" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Ảnh sản phẩm:</label>
                        <input type="file" class="form-control-file" name="image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Xác Nhận Vô Hiệu Hóa -->
<div class="modal fade" id="disableModal{{$product->product_id}}" tabindex="-1" role="dialog" aria-labelledby="disableModalLabel{{$product->product_id}}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="disableModalLabel{{$product->product_id}}">
                    @if ($product->status === 'active') Xác nhận vô hiệu hóa @else Xác nhận bỏ vô hiệu hóa @endif
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if ($product->status === 'active')
                    Bạn có chắc chắn muốn vô hiệu hóa sản phẩm này không?
                @else
                    Bạn có chắc chắn muốn kích hoạt lại sản phẩm này không?
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-warning" id="confirmDisable{{$product->product_id}}">
                    @if ($product->status === 'active') Vô hiệu hóa @else Bỏ vô hiệu hóa @endif
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Structure -->
<div class="modal fade" id="ratingUpdateModal" tabindex="-1" aria-labelledby="ratingUpdateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ratingUpdateModalLabel">Xác nhận cập nhật</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="modalMessage">Bạn đã đánh giá sản phẩm này. Bạn có muốn cập nhật lại đánh giá của mình không?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-primary" id="confirmUpdate">Cập nhật</button>
      </div>
    </div>
  </div>
</div>



@endsection

<script src="{{ asset('js/product_quantity.js') }}"></script>
<script src="{{ asset('js/btn_atc.js') }}"></script>

<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#add-to-cart-btn').on('click', function() {
        const productId = $(this).data('product-id');
        const quantity = $('#quantity').val();
        console.log(productId);
        console.log(quantity);


        $.ajax({
            url: '{{ route('cart.add') }}',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    alert('Sản phẩm đã được thêm vào giỏ hàng');
                } else {
                    alert('Có lỗi xảy ra');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });
});

$(document).ready(function(){
    $('.rating input').on('change', function(){
        var rating = $(this).val();
        console.log("User rated: " + rating);
    });
});

$(document).ready(function() {
        // Khi nhấn nút xác nhận vô hiệu hóa
        $('#confirmDisable').click(function() {
            // Gửi form vô hiệu hóa sản phẩm
            $('#toggleStatusForm').submit();
        });
    });


    $(document).ready(function() {
    $('#confirmDisable{{$product->product_id}}').on('click', function() {
        $.ajax({
            url: "{{ route('products.toggleStatus', $product->product_id) }}", // Route vô hiệu hóa sản phẩm
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}" // Đảm bảo CSRF token được gửi đi
            },
            success: function(response) {
                if (response.success) {
                    alert('Sản phẩm đã được cập nhật trạng thái.');
                    location.reload(); // Tải lại trang để cập nhật thay đổi trạng thái
                } else {
                    
                    alert('Có lỗi xảy ra. Vui lòng thử lại.');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert('Lỗi hệ thống, vui lòng thử lại sau.');
            }
        });
    });
});

$(document).ready(function() {
    // Khi hover vào sao
    $('.star').hover(function() {
        let ratingValue = $(this).data('value');

        // Thay đổi tất cả các sao trước sao hiện tại thành solid
        $(this).siblings().each(function() {
            let siblingValue = $(this).data('value');
            if (siblingValue <= ratingValue) {
                $(this).find('i').removeClass('far').addClass('fas'); // Solid star
            } else {
                $(this).find('i').removeClass('fas').addClass('far'); // Empty star
            }
        });
    }, function() {
        // Khi rời chuột ra, quay lại trạng thái ban đầu dựa trên rating đã chọn
        let selectedRating = $('input[name="rating"]:checked').val();
        $('.star').each(function() {
            let starValue = $(this).data('value');
            if (starValue <= selectedRating) {
                $(this).find('i').removeClass('far').addClass('fas');
            } else {
                $(this).find('i').removeClass('fas').addClass('far');
            }
        });
    });

    // Khi click vào sao để chọn đánh giá
    $('.star').click(function() {
        let ratingValue = $(this).data('value');
        $('#star' + ratingValue).prop('checked', true);

        // Cập nhật tất cả các sao
        $('.star').each(function() {
            let starValue = $(this).data('value');
            if (starValue <= ratingValue) {
                $(this).find('i').removeClass('far').addClass('fas');
            } else {
                $(this).find('i').removeClass('fas').addClass('far');
            }
        });
    });
});

$('#submit-review').on('click', function(e) {
    e.preventDefault();
    
    let formData = $('#review-form').serialize(); // Serialize form data

    $.ajax({
        type: 'POST',
        url: '/products/' + productId + '/add-review', // URL đến route của phương thức addReview
        data: formData,
        success: function(response) {
            if (response.confirm_update) {
                // Hiển thị modal nếu cần xác nhận sửa đánh giá
                $('#confirmUpdateModal').modal('show');
            } else {
                location.reload(); // Reload trang sau khi đánh giá được lưu
            }
        },
        error: function(error) {
            console.error('Error:', error);
        }
    });
});

// Nếu người dùng xác nhận muốn sửa đánh giá
$('#confirm-update-btn').on('click', function() {
    // Gửi lại request với lựa chọn update
    $('#review-form').submit();
    location.reload(); // Reload trang sau khi đánh giá được lưu
});

$(document).ready(function() {
    $('form').on('submit', function(e) {
        e.preventDefault(); // Ngăn chặn hành động mặc định

        const form = $(this);
        const url = form.attr('action');
        const formData = form.serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.confirm_update) {
                    if (confirm(response.message)) {
                        // Người dùng xác nhận cập nhật đánh giá
                        form.append('<input type="hidden" name="confirm_update" value="true">');
                        form.off('submit').submit(); // Gửi form lại
                    }
                } else {
                    // Không có xác nhận cần thiết, tiếp tục như bình thường
                    location.reload();
                }
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.rating label');
    const reviewForm = document.querySelector('form[action="{{ route('products.addReview', ['id' => $product->product_id]) }}"]');
    const currentRating = {{ $userReview ? $userReview->rating : 'null' }};
    let newRating;

    // Đánh dấu rating hiện tại
    if (currentRating) {
        document.querySelector(`input[name="rating"][value="${currentRating}"]`).checked = true;
    }

    stars.forEach(star => {
        star.addEventListener('click', function() {
            newRating = this.getAttribute('data-value');

            // Hiển thị modal nếu người dùng thay đổi đánh giá
            if (currentRating && newRating !== currentRating.toString()) {
                const modalElement = document.getElementById('ratingUpdateModal');
                const modal = new bootstrap.Modal(modalElement);
                modal.show();

                // Gán sự kiện cho nút "Cập nhật" khi modal được hiển thị
                modalElement.addEventListener('shown.bs.modal', function() {
                    const confirmButton = document.getElementById('confirmUpdate');

                    // Gán sự kiện click cho nút "Cập nhật"
                    confirmButton.onclick = function() {
                        // Cập nhật giá trị của form và gửi đi
                        const ratingInput = reviewForm.querySelector(`input[name="rating"][value="${newRating}"]`);
                        if (ratingInput) {
                            ratingInput.checked = true;
                            modal.hide();
                        }
                    };

                    // Gán sự kiện click cho nút "Hủy"
                    const cancelButton = modalElement.querySelector('.btn-secondary');
                    cancelButton.onclick = function() {
                        modal.hide(); // Đóng modal khi nhấn "Hủy"
                        // Khôi phục lại rating đã có
                        const existingRatingInput = reviewForm.querySelector(`input[name="rating"][value="${currentRating}"]`);
                        if (existingRatingInput) {
                            existingRatingInput.checked = true;
                        }
                    };
                }, { once: true }); // Đảm bảo sự kiện chỉ được thêm một lần
            }
        });
    });
});

</script>

