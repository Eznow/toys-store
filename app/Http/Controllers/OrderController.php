<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    public function index()
{
    // Lấy danh sách đơn hàng của người dùng hiện tại
    $orders = Auth::user()->orders()->orderBy('created_at', 'desc')->get();

    return view('orders.index', compact('orders'));
}

// public function history()
// {
//     $orders = Order::where('user_id', Auth::id())->with('orderItems.product')->get();

//     return view('orders.history', compact('orders'));
// }

public function history()
{
    $orders = Order::with('orderItems.product') // Tải các sản phẩm liên quan cho mỗi item
                    ->where('user_id', auth()->id()) // Chỉ lấy đơn hàng của người dùng hiện tại
                    ->get();



    return view('orders.history', compact('orders'));
}

}
