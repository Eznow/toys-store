<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductReview;
use App\Models\OrderItem;


class ProductController extends Controller
{
    // Hiển thị form thêm sản phẩm
public function create()
{
    $categories = Category::all(); // Lấy danh sách categories
    return view('products.create', compact('categories'));
}

// Xử lý lưu sản phẩm
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'category_id' => 'required|exists:categories,category_id', 
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Upload hình ảnh
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('products', 'public');
    } else {
        $imagePath = null;
    }

    // Tạo sản phẩm
    $product = Product::create([
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'stock' => $request->stock,
        'image_url' => $imagePath,
        'category_id' => $request->category_id,
        'age_group' => $request->age_group,  
        'gender' => $request->gender,
    ]);

    return redirect()->route('home')->with('success', 'Sản phẩm đã được thêm thành công!');
}

// public function index()
// {
//     // $products = Product::all(); // Lấy tất cả sản phẩm
//     $products = Product::where('status', 'active')->get();

//     return view('products.index', compact('products'));
// }


public function index(Request $request)
{
    // Lấy từ khóa tìm kiếm từ request
    $query = $request->input('query');

    // Lọc sản phẩm theo từ khóa tìm kiếm nếu có
    $products = Product::where('status', 'active')
        ->when($query, function ($queryBuilder) use ($query) {
            return $queryBuilder->where('name', 'LIKE', "%{$query}%");
        })
        ->with('reviews') // Lấy các review liên quan đến sản phẩm
        ->get();

    // Tính rating trung bình cho từng sản phẩm
    foreach ($products as $product) {
        $ratings = $product->reviews->where('rating', '>', 0)->groupBy('user_id')->map(function ($reviews) {
            return $reviews->first()->rating;
        });

        $product->averageRating = $ratings->count() > 0 ? $ratings->avg() : null;
    }

    // Lấy danh sách danh mục để truyền vào view
    $categories = Category::all();

    // Kiểm tra nếu là yêu cầu AJAX, chỉ trả về danh sách sản phẩm
    if ($request->ajax()) {
        return response()->json([
            'products' => view('products.partials.product_list', compact('products'))->render()
        ]);
    }

    // Trả về view home với danh sách sản phẩm và danh mục
    return view('home', compact('products', 'categories', 'query'));
}



public function show($id)
{
    // Tìm sản phẩm kèm theo đánh giá của người dùng
    $product = Product::with('reviews.user')->findOrFail($id);

    // Lấy rating đầu tiên của mỗi người dùng cho sản phẩm
    $ratings = ProductReview::select('user_id', 'rating')
        ->where('product_id', $id)
        ->where('rating', '>', 0) // Bỏ qua rating bằng 0
        ->orderBy('created_at') // Sắp xếp theo thời gian tạo
        ->distinct('user_id') // Lấy rating duy nhất theo user_id
        ->get();

    // Tính trung bình rating
    $averageRating = $ratings->avg('rating');

    $userReview = ProductReview::where('user_id', auth()->id())
                                ->where('product_id', $id)
                                ->orderBy('created_at')
                                ->first();

    return view('products.show', compact('product', 'averageRating', 'userReview'));
}

// Hiển thị sản phẩm theo danh mục
public function filterByCategory(Category $category)
{
    $products = $category->products()->get();
    return view('products.index', compact('products'));
}

public function filter(Request $request)
{
    $query = Product::query();

    // Filter by category (radio button, single choice)
    if ($request->has('category_id') && $request->category_id != 'all') {
        $query->where('category_id', $request->category_id);
    }

    // Filter by price range (checkboxes)
    if ($request->has('price_range')) {
        $priceRanges = $request->price_range;
        $query->where(function ($q) use ($priceRanges) {
            foreach ($priceRanges as $range) {
                switch ($range) {
                    case '0-50000':
                        $q->orWhereBetween('price', [0, 50000]);
                        break;
                    case '50001-100000':
                        $q->orWhereBetween('price', [50001, 100000]);
                        break;
                    case '100001-200000':
                        $q->orWhereBetween('price', [100001, 200000]);
                        break;
                    case '200001+':
                        $q->orWhere('price', '>', 200001);
                        break;
                }
            }
        });
    }

    // Filter by gender (checkboxes)
    if ($request->has('gender')) {
        $query->whereIn('gender', $request->gender);
    }

    // Get the filtered products
    $products = $query->with('reviews')->get();
    $categories = Category::all();

    // Pass products and categories to the view
    return view('home', compact('products', 'categories'));
}

// public function ajaxFilter(Request $request)
// {
//     if ($request->ajax()) {
//         $query = Product::where('status', 'active');

//         // Lọc theo danh mục
//         if ($request->filled('category_id')) {
//             $query->where('category_id', $request->category_id);
//         }

//         // Lọc theo nhiều khoảng giá
//         if ($request->filled('price_ranges')) {
//             $query->where(function($q) use ($request) {
//                 foreach ($request->price_ranges as $range) {
//                     [$min, $max] = explode('-', $range) + [null, null];
//                     if ($max) {
//                         $q->orWhereBetween('price', [(int)$min, (int)$max]);
//                     } else {
//                         $q->orWhere('price', '>=', (int)$min);
//                     }
//                 }
//             });
//         }

//         // Lọc theo giới tính
//         if ($request->filled('genders')) {
//             $query->whereIn('gender', $request->genders);
//         }

//         $products = $query->get();

//         return response()->json([
//             'products' => view('products.partials.product_list', compact('products'))->render()
//         ]);
//     }

//     abort(404);
// }


public function ajaxFilter(Request $request)
{
    if ($request->ajax()) {
        $query = Product::where('status', 'active');

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo giá
        if ($request->filled('price_ranges')) {
            $selectedRanges = $request->price_ranges;
            $query->where(function ($q) use ($selectedRanges) {
                foreach ($selectedRanges as $range) {
                    [$min, $max] = explode('-', $range);
                    $q->orWhereBetween('price', [(int) $min, (int) $max]);
                }
            });
        }

        // Lọc theo giới tính
        if ($request->filled('genders')) {
            $query->whereIn('gender', $request->genders);
        }

        // Lọc theo từ khóa tìm kiếm
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->get();

        // Trả về dữ liệu JSON cho AJAX
        return response()->json([
            'products' => view('products.partials.product_list', compact('products'))->render()
        ]);
    }

    abort(404); // Nếu không phải là yêu cầu AJAX
}





public function addOrUpdateReview(Request $request, $productId)
{
    // Kiểm tra người dùng đã mua sản phẩm hay chưa
    $hasPurchased = OrderItem::whereHas('order', function ($query) {
                                $query->where('user_id', auth()->id());
                            })
                            ->where('product_id', $productId)
                            ->exists();
               

    // Validate bình luận và đánh giá sao
    $request->validate([
        'rating' => 'nullable|integer|min:0|max:5', 
        'review' => 'nullable|string|max:1000',
    ]);

    // Tìm tất cả đánh giá của người dùng với sản phẩm
    $existingReviews = ProductReview::where('user_id', auth()->id())
                                   ->where('product_id', $productId);

    if ($existingReviews->exists()) {
        // Cập nhật rating cho tất cả các bản ghi đánh giá hiện có
        $existingReviews->update(['rating' => $request->rating]);

        // Thêm một bình luận mới (chỉ một lần)
        ProductReview::create([
            'user_id' => auth()->id(),
            'product_id' => $productId,
            'rating' => $request->rating, 
            'review' => $request->review,
        ]);

        return redirect()->back()->with('success', 'Bình luận mới và đánh giá của bạn đã được lưu cho tất cả các bản ghi!');
    } else {
        // Nếu chưa có đánh giá, tạo mới đánh giá và bình luận
        ProductReview::create([
            'user_id' => auth()->id(),
            'product_id' => $productId,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);
        return redirect()->back()->with('success', 'Đánh giá và bình luận của bạn đã được lưu!');
    }
}

public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);
    $product->name = $request->input('name');
    $product->description = $request->input('description');
    $product->price = $request->input('price');

    // Nếu có upload ảnh mới
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imagePath = $image->store('products', 'public');
        $product->image_url = $imagePath;
    }

    $product->save();

    return redirect()->route('products.show', $product->product_id)
        ->with('success', 'Sản phẩm đã được cập nhật thành công');
}

public function destroy($id)
{
    // Tìm sản phẩm theo id
    $product = Product::findOrFail($id);

    // Xóa sản phẩm
    $product->delete();

    // Chuyển hướng về danh sách sản phẩm kèm theo thông báo
    return redirect()->route('products.index')->with('success', 'Sản phẩm đã được xóa thành công');
}

public function disableProduct($id)
{
    // Tìm sản phẩm theo id
    $product = Product::findOrFail($id);

    // ID của danh mục "Vô hiệu hóa" (cập nhật theo giá trị thực tế)
    $disabledCategoryId = 4; 

    // Nếu sản phẩm chưa bị vô hiệu hóa, chuyển vào danh mục "Vô hiệu hóa"
    if ($product->category_id != $disabledCategoryId) {
        $product->update([
            'category_id' => $disabledCategoryId
        ]);
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được vô hiệu hóa.');
    } 
    // Nếu sản phẩm đã bị vô hiệu hóa, khôi phục sản phẩm (trả lại danh mục cũ)
    else {
        // Chuyển lại sản phẩm về danh mục cũ (cần có cách lưu danh mục cũ)
        $oldCategoryId = 1; // Giả sử danh mục cũ là 1
        $product->update([
            'category_id' => $oldCategoryId
        ]);
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được kích hoạt lại.');
    }
}

public function disabledProducts()
{
    // Lấy tất cả các sản phẩm bị vô hiệu hóa
    $disabledProducts = Product::where('status', 'disabled')->get();

    // Trả về view hiển thị danh sách sản phẩm bị vô hiệu hóa
    return view('admin.disabled-products', compact('disabledProducts'));
}


public function toggleStatus($id)
{
    try {
        $product = Product::findOrFail($id);

        if ($product->status === 'active') {
            // Vô hiệu hóa sản phẩm
            $product->status = 'disabled';
        } else {
            // Kích hoạt lại sản phẩm
            $product->status = 'active';
        }

        $product->save();

        // Trả về phản hồi JSON cho AJAX
        return response()->json(['success' => true, 'status' => $product->status, 'message' => 'Trạng thái sản phẩm đã được cập nhật.']);
    } catch (\Exception $e) {
        // Trả về phản hồi JSON khi có lỗi
        return response()->json(['success' => false, 'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
    }
}

}
