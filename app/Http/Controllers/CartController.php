<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem; 
use Illuminate\Support\Facades\Auth;
use App\Models\DiscountCode;


class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        // Xác thực người dùng
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.']);
        }

        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Lấy giỏ hàng của người dùng
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            // Nếu sản phẩm đã có, tăng số lượng
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Nếu sản phẩm chưa có, thêm mới
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Sản phẩm đã được thêm vào giỏ hàng']);
    }

//     public function viewCart()
// {
//     $cart = Cart::where('user_id', Auth::id())->with('cartItems.product')->first();
//     return view('cart.index', compact('cart'));
// }

public function viewCart()
{
    // Lấy giỏ hàng của người dùng đăng nhập
    $cart = Cart::where('user_id', Auth::id())->with('cartItems.product')->first();
    $total = 0;

    if ($cart) {
        // Tính tổng tiền của giỏ hàng
        $total = $cart->cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
    }

    // Truyền cả giỏ hàng và tổng tiền vào view
    return view('cart.index', [
        'cart' => $cart,
        'total' => $total
    ]);
}


public function remove($id)
{
    $cartItem = CartItem::findOrFail($id); // Tìm item theo ID
    $cartItem->delete(); // Xóa item

    return redirect()->back()->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.'); // Chuyển hướng về trang trước
}

public function update(Request $request)
{
    $itemId = $request->input('item_id');
    $quantity = $request->input('quantity');

    // Lấy cart_item từ database
    $cartItem = CartItem::find($itemId);

    if ($cartItem && $cartItem->cart->user_id == auth()->id()) {
        $cartItem->quantity = $quantity;
        $cartItem->save();

        // Tính lại tổng tiền cho từng sản phẩm và toàn bộ giỏ hàng
        $itemTotal = $cartItem->product->price * $cartItem->quantity;
        $cartTotal = $cartItem->cart->cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        return response()->json([
            'success' => true,
            'itemTotal' => number_format($itemTotal),
            'cartTotal' => number_format($cartTotal)
        ]);
    }

    return response()->json(['success' => false], 400);
}

public function applyDiscount(Request $request)
{
    $request->validate(['discount_code' => 'required|string']);

    $discountCode = DiscountCode::where('code', $request->discount_code)
        ->where('valid_from', '<=', now())
        ->where('valid_until', '>=', now())
        ->first();

    if (!$discountCode || $discountCode->usage_count >= $discountCode->usage_limit) {
        return response()->json(['success' => false, 'message' => 'Mã giảm giá không hợp lệ hoặc đã hết số lần sử dụng.']);
    }

    $cart = Cart::where('user_id', Auth::id())->with('cartItems.product')->first();
    if ($discountCode->min_order_value && $cart->total_price < $discountCode->min_order_value) {
        return response()->json(['success' => false, 'message' => 'Không đủ điều kiện để áp dụng mã giảm giá này.']);
    }

    session(['discount' => $discountCode->code, 'discount_amount' => $discountCode->discount_percentage / 100 * $cart->total_price]);

    return response()->json(['success' => true]);
}

}
