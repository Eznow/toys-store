@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/btn_atc.css') }}">

@section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Bộ lọc ở bên trái -->
        <div class="col-md-3">
            <form action="{{ route('products.filter') }}" method="GET" class="filter-form">
                <h4>Lọc sản phẩm</h4>

                <h5>Nhóm tuổi</h5>
                <div class="form-group">
                    <div>
                        <input type="checkbox" name="age_group[]" value="under_1"> Dưới 1 tuổi
                    </div>
                    <div>
                        <input type="checkbox" name="age_group[]" value="1-3"> 1-3 tuổi
                    </div>
                    <div>
                        <input type="checkbox" name="age_group[]" value="4-6"> 4-6 tuổi
                    </div>
                    <div>
                        <input type="checkbox" name="age_group[]" value="7-12"> 7-12 tuổi
                    </div>
                    <div>
                        <input type="checkbox" name="age_group[]" value="13_and_above"> 13 tuổi trở lên
                    </div>
                </div>

                <h5>Giới tính</h5>
                <div class="form-group">
                    <div>
                        <input type="checkbox" name="gender[]" value="male"> Nam
                    </div>
                    <div>
                        <input type="checkbox" name="gender[]" value="female"> Nữ
                    </div>
                    <div>
                        <input type="checkbox" name="gender[]" value="unisex"> Không phân biệt
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Lọc</button>
            </form>
        </div>

        <!-- Danh sách sản phẩm ở bên phải -->
        <div class="col-md-9">
            <div class="row">
                @foreach($products as $product)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 product-item shadow-sm">
                            <img src="{{ asset('storage/' . $product->image_url) }}" class="card-img-top product-image" alt="{{ $product->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <!-- <p class="card-text">{{ Str::limit($product->description, 100) }}</p> -->
                                <p class="card-text text-primary m-0">{{ number_format($product->price, 0, ',', '.') }} đ</p>
                                <a href="{{ route('products.show', $product->product_id) }}" class="btn btn-primary btn-block">Xem chi tiết</a>
                                <!-- <button class="add-to-cart-button">
        <svg class="add-to-cart-box box-1" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="24" height="24" rx="2" fill="#ffffff"/></svg>
        <svg class="add-to-cart-box box-2" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="24" height="24" rx="2" fill="#ffffff"/></svg>
        <svg class="cart-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
        <svg class="tick" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"/><path fill="#ffffff" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM9.29 16.29L5.7 12.7c-.39-.39-.39-1.02 0-1.41.39-.39 1.02-.39 1.41 0L10 14.17l6.88-6.88c.39-.39 1.02-.39 1.41 0 .39.39.39 1.02 0 1.41l-7.59 7.59c-.38.39-1.02.39-1.41 0z"/></svg>
        <span class="add-to-cart">Add to cart</span>
        <span class="added-to-cart">Added to cart</span>
    </button> -->
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

<script src="{{ asset('js/home.js') }}"></script>
<script src="{{ asset('js/btn_atc.js') }}"></script>