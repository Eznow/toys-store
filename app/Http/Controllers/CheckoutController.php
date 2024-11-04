<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\DiscountCode; // Thêm model DiscountCode
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    // Hiển thị trang thanh toán
    public function checkout()
{
    $cart = Cart::where('user_id', Auth::id())->with('cartItems.product')->first();
    if (!$cart || $cart->cartItems->isEmpty()) {
        return redirect()->route('cart.view')->with('error', 'Giỏ hàng của bạn trống.');
    }

    // Lấy mã giảm giá từ session nếu có
    $discountCode = session('discount') ? DiscountCode::where('code', session('discount'))->first() : null;
    $totalPrice = $cart->cartItems->sum(fn($item) => $item->product->price * $item->quantity);

    // Khởi tạo giá trị mặc định cho discountAmount
    $discountAmount = 0;

    // Kiểm tra xem mã giảm giá có hợp lệ với giỏ hàng hiện tại không
    if ($discountCode && $discountCode->valid_from <= now() && $discountCode->valid_until >= now() &&
        $discountCode->usage_count < $discountCode->usage_limit &&
        (!$discountCode->min_order_value || $discountCode->min_order_value <= $totalPrice)) {
        
        // Tính lại giá trị giảm giá nếu hợp lệ
        $discountAmount = $totalPrice * ($discountCode->discount_percentage / 100);
        session(['discount_amount' => $discountAmount]);
    } else {
        // Xóa mã giảm giá nếu không hợp lệ
        session()->forget('discount');
        session()->forget('discount_amount');
    }

    return view('checkout.index', compact('cart', 'discountAmount'));
}



    // Xử lý thanh toán
    public function processCheckout(Request $request)
{
    DB::beginTransaction();
    try {
        $cart = Cart::where('user_id', Auth::id())->with('cartItems.product')->first();
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.view')->with('error', 'Giỏ hàng của bạn trống.');
        }

        $totalPrice = $cart->cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $discountAmount = session('discount_amount', 0);
        $totalPriceAfterDiscount = $totalPrice - $discountAmount;

        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $totalPriceAfterDiscount,
            'status' => 'completed',
            'discount_amount' => $discountAmount,
        ]);

        foreach ($cart->cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->order_id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price,
            ]);
        }

        if (session('discount')) {
            $discountCode = DiscountCode::where('code', session('discount'))->first();
            if ($discountCode) {
                $discountCode->increment('usage_count');
            }
        }

        $cart->cartItems()->delete();
        $cart->delete();

        DB::commit();

        session()->forget('discount');
        session()->forget('discount_amount');

        return redirect()->route('orders.history')->with('success', 'Thanh toán thành công! Đơn hàng của bạn đã được lưu lại.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('cart.view')->with('error', 'Có lỗi xảy ra trong quá trình thanh toán. Vui lòng thử lại.');
    }
}

    


      // Áp dụng mã giảm giá
      public function applyDiscount(Request $request)
{
    $request->validate([
        'discount_code' => 'required|string',
    ]);

    $discountCode = DiscountCode::where('code', $request->discount_code)
        ->where('valid_from', '<=', now())
        ->where('valid_until', '>=', now())
        ->first();

    if (!$discountCode) {
        return response()->json(['success' => false, 'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.']);
    }

    if ($discountCode->usage_count >= $discountCode->usage_limit) {
        return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết số lần sử dụng.']);
    }

    $discountPercentage = $discountCode->discount_percentage;
    $cart = Cart::where('user_id', Auth::id())->with('cartItems.product')->first();
    $totalPrice = $cart->cartItems->sum(fn($item) => $item->product->price * $item->quantity);
    $discountAmount = $totalPrice * ($discountPercentage / 100);

    session([
        'discount' => $discountCode->code,
        'discount_amount' => $discountAmount
    ]);

    return response()->json(['success' => true, 'discount_amount' => $discountAmount]);
}


public function getAvailableDiscounts(Request $request)
{
    try {
        $cart = Cart::where('user_id', Auth::id())->with('cartItems.product')->first();
        if (!$cart) {
            return response()->json(['discounts' => []]);
        }

        // Tính tổng giá trị giỏ hàng nếu thuộc tính total_price không tồn tại
        $totalPrice = $cart->cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        // Lọc mã giảm giá theo điều kiện
        $discounts = DiscountCode::where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->whereColumn('usage_count', '<', 'usage_limit')
            ->where(function ($query) use ($totalPrice) {
                $query->whereNull('min_order_value')
                      ->orWhere('min_order_value', '<=', $totalPrice);
            })
            ->get();

        return response()->json(['discounts' => $discounts]);

    } catch (\Exception $e) {
        // Trả về JSON khi có lỗi
        return response()->json(['error' => 'Đã xảy ra lỗi khi tải mã giảm giá'], 500);
    }
}


}
