<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductReview;

class ProductReviewController extends Controller
{
    public function destroy($review_id)
{
    $review = ProductReview::find($review_id);

    // Kiểm tra nếu bản ghi tồn tại
    if ($review) {
        // Đặt trường 'review' thành rỗng thay vì xóa bản ghi
        $review->update(['review' => 'Bình luận đã bị quản trị viên xóa vì vi phạm nội quy']);
        return response()->json(['message' => 'Review has been cleared successfully.']);
    } else {
        return response()->json(['message' => 'Review not found.'], 404);
    }
}


}
