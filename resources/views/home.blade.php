@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/home.css') }}">


@section('content')
<div class="container-xl mt-5">
    <div class="row">
        <!-- Sidebar Lọc -->
        <div class="col-md-3 filter-container">
    <h5>Lọc sản phẩm</h5>
    <form id="filter-form" data-url="{{ route('products.ajaxFilter') }}">
        <h6>Danh mục</h6>
        @foreach($categories as $category)
            <div>
                <input type="radio" name="category_id" value="{{ $category->category_id }}" class="filter-input">
                <label><i class="fas fa-tags icon"></i> {{ $category->name }}</label>
            </div>
        @endforeach

        <h6>Giá</h6>
        @foreach(['0-200000' => 'Dưới 200,000 đ', '200000-500000' => '200,000 đ - 500,000 đ', '500000-1000000' => '500,000 đ - 1,000,000 đ', '1000000-2000000' => '1,000,000 đ - 2,000,000 đ', '2000000-4000000' => '2,000,000 đ - 4,000,000 đ', '4000000-' => 'Trên 4,000,000 đ'] as $value => $label)
            <div>
                <input type="checkbox" name="price_ranges[]" value="{{ $value }}" class="filter-input">
                <label><i class="fas fa-money-bill-wave icon"></i> {{ $label }}</label>
            </div>
        @endforeach

        <h6>Giới tính</h6>
        @foreach(['male' => 'Nam', 'female' => 'Nữ', 'unisex' => 'Unisex'] as $value => $label)
            <div>
                <input type="checkbox" name="genders[]" value="{{ $value }}" class="filter-input">
                <label><i class="fas fa-venus-mars icon"></i> {{ $label }}</label>
            </div>
        @endforeach

        <button type="submit" class="apply-filter-btn">Bỏ áp dụng lọc</button>
    </form>
</div>



        <!-- Danh sách sản phẩm -->
        <div class="col-md-9">
            <!-- <h2 class="text-center mb-5 text-primary">Sản phẩm mới nhất</h2> -->
            <div id="product-list">
                @include('products.partials.product_list', ['products' => $products])
            </div>
        </div>
    </div>
</div>
@endsection

<script src="{{ asset('js/filter.js') }}"></script>
