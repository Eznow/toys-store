<div class="row">
    @foreach($products as $product)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm product-card">
                <img src="{{ asset('storage/' . $product->image_url) }}" class="card-img-top product-image" alt="{{ $product->name }}">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title product-item">{{ $product->name }}</h5>
                    <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                    <p class="card-text text-primary"><strong>{{ number_format($product->price, 0, ',', '.') }} đ</strong></p>
                    <a href="{{ route('products.show', $product->product_id) }}" class="btn btn-primary btn-block mt-auto">Xem chi tiết</a>
                </div>
            </div>
        </div>
    @endforeach
</div>
