@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Thêm sản phẩm mới</h2>
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name">Tên sản phẩm:</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
        </div>

        <div class="form-group">
            <label for="description">Mô tả:</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="price">Giá:</label>
            <input type="text" name="price" class="form-control" value="{{ old('price') }}">
        </div>

        <div class="form-group">
            <label for="stock">Số lượng trong kho:</label>
            <input type="number" name="stock" class="form-control" value="{{ old('stock') }}">
        </div>

        <div class="form-group">
            <label>Nhóm tuổi:</label>
            <div>
                <input type="radio" name="age_group" value="under_1" {{ old('age_group') == 'under_1' ? 'checked' : '' }}> Dưới 1 tuổi
            </div>
            <div>
                <input type="radio" name="age_group" value="1-3" {{ old('age_group') == '1-3' ? 'checked' : '' }}> 1-3 tuổi
            </div>
            <div>
                <input type="radio" name="age_group" value="4-6" {{ old('age_group') == '4-6' ? 'checked' : '' }}> 4-6 tuổi
            </div>
            <div>
                <input type="radio" name="age_group" value="7-12" {{ old('age_group') == '7-12' ? 'checked' : '' }}> 7-12 tuổi
            </div>
            <div>
                <input type="radio" name="age_group" value="13_and_above" {{ old('age_group') == '13_and_above' ? 'checked' : '' }}> 13 tuổi trở lên
            </div>
        </div>

        <!-- Giới tính -->
        <div class="form-group">
            <label>Giới tính:</label>
            <div>
                <input type="radio" name="gender" value="male" {{ old('gender') == 'male' ? 'checked' : '' }}> Nam
            </div>
            <div>
                <input type="radio" name="gender" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}> Nữ
            </div>
            <div>
                <input type="radio" name="gender" value="unisex" {{ old('gender') == 'unisex' ? 'checked' : '' }}> Không phân biệt
            </div>
        </div>

        <div class="form-group">
            <label>Chọn danh mục:</label>
            <div>
                @foreach($categories as $category)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="category_id" value="{{ $category->category_id }}">
                        <label class="form-check-label">{{ $category->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <label for="image">Hình ảnh sản phẩm:</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
    </form>
</div>
@endsection
