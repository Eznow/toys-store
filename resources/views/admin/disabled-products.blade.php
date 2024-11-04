@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center">Danh sách sản phẩm bị vô hiệu hóa</h2>
    <div class="row">
        @forelse($disabledProducts as $product)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ $product->description }}</p>
                        <p class="card-text"><strong>Giá:</strong> {{ number_format($product->price, 0, ',', '.') }} VND</p>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#enableModal{{$product->product_id}}">
                            <i class="fas fa-check"></i> Bỏ vô hiệu hóa
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Bỏ Vô Hiệu Hóa -->
            <div class="modal fade" id="enableModal{{$product->product_id}}" tabindex="-1" role="dialog" aria-labelledby="enableModalLabel{{$product->product_id}}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="enableModalLabel{{$product->product_id}}">Xác nhận bỏ vô hiệu hóa</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Bạn có chắc chắn muốn kích hoạt lại sản phẩm này không?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                            <button type="button" class="btn btn-success" id="confirmEnable{{$product->product_id}}">
                                Bỏ vô hiệu hóa
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center">Không có sản phẩm nào bị vô hiệu hóa.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
