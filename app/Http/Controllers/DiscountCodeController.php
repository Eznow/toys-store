<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiscountCode;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;


class DiscountCodeController extends Controller
{
    // Hiển thị danh sách mã giảm giá
    public function index()
    {
        $discountCodes = DiscountCode::all();
        return view('admin.discount-codes.index', compact('discountCodes'));
    }

    // Hiển thị form tạo mã giảm giá mới
    public function create()
    {
        return view('admin.discount-codes.create');
    }

    // Lưu mã giảm giá mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:discount_codes,code',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'min_order_value' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
            'usage_limit' => 'required|integer|min:1',
        ]);

        DiscountCode::create($request->all());
        return redirect()->route('discount-codes.index')->with('success', 'Mã giảm giá đã được tạo thành công.');
    }

    // Hiển thị form chỉnh sửa mã giảm giá
    public function edit($id)
    {
        $discountCode = DiscountCode::findOrFail($id);
        return view('admin.discount-codes.edit', compact('discountCode'));
    }

    // Cập nhật mã giảm giá
    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|unique:discount_codes,code,' . $id,
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'min_order_value' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
            'usage_limit' => 'required|integer|min:1',
        ]);

        $discountCode = DiscountCode::findOrFail($id);
        $discountCode->update($request->all());
        return redirect()->route('discount-codes.index')->with('success', 'Mã giảm giá đã được cập nhật thành công.');
    }

    // Xóa mã giảm giá
    public function destroy($id)
    {
        $discountCode = DiscountCode::findOrFail($id);
        $discountCode->delete();
        return redirect()->route('discount-codes.index')->with('success', 'Mã giảm giá đã được xóa thành công.');
    }

}
