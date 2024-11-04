<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintMedia;
use App\Models\ReplyMedia;
use App\Models\ComplaintReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;



class ComplaintController extends Controller
{
    // Hiển thị danh sách khiếu nại cho admin
    public function index()
    {
        $complaints = Complaint::with(['user', 'order'])->get();
        return view('admin.complaints.index', compact('complaints'));
    }

    // Hiển thị chi tiết một khiếu nại
    public function show(Complaint $complaint)
    {
        $complaint->load(['user', 'order', 'media', 'replies.user', 'replies.media']);

        return view('complaints.show', compact('complaint'));
    }

    // Tạo khiếu nại từ người dùng
    public function store(Request $request, $orderId)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'video' => 'nullable|file|mimes:mp4,mov,avi|max:10240',
        ]);

        $complaint = Complaint::create([
            'user_id' => auth()->id(),
            'order_id' => $orderId,
            'description' => $request->input('description'),
            'status' => 'pending',
        ]);

        if ($request->hasFile('video')) {
            $filePath = $request->file('video')->store('complaint_videos', 'public');
            ComplaintMedia::create([
                'complaint_id' => $complaint->complaint_id,
                'file_path' => $filePath,
                'file_type' => 'video',
            ]);
        }

        return redirect()->route('orders.history')->with('success', 'Đã gửi khiếu nại thành công.');
    }

    public function reply(Request $request, Complaint $complaint)
    {
        try {
            // Xác thực input
            $request->validate([
                'message' => 'required|string',
                'video' => 'nullable|file|mimes:mp4,mov,avi,jpg,jpeg,png|max:10240',
            ]);
    
            // Bắt đầu transaction
            DB::beginTransaction();
    
            // Tạo phản hồi
            $reply = ComplaintReply::create([
                'complaint_id' => $complaint->complaint_id,
                'user_id' => auth()->id(),
                'message' => $request->message,
            ]);
    
            Log::info("Reply created with ID: " . $reply->reply_id);
    
            // Kiểm tra và lưu video nếu có
            if ($request->hasFile('video')) {
                $videoPath = $request->file('video')->store('reply_videos', 'public');
    
                // Chỉ tạo media nếu reply đã được tạo thành công
                if ($reply->reply_id) {
                    ReplyMedia::create([
                        'reply_id' => $reply->reply_id,
                        'file_path' => $videoPath,
                    ]);
                    Log::info("Video uploaded for Reply ID: " . $reply->reply_id);
                } else {
                    Log::error("Failed to create reply, no ID found.");
                    throw new \Exception("Failed to create reply, no ID found.");
                }
            }
    
            // Cam kết transaction
            DB::commit();
            return back()->with('success', 'Phản hồi của bạn đã được gửi.');
    
        } catch (\Exception $e) {
            // Rollback nếu có lỗi
            DB::rollBack();
            Log::error("Error during reply creation: " . $e->getMessage());
            return back()->withErrors('Có lỗi xảy ra khi lưu phản hồi.');
        }
    }

    


    public function updateStatus(Request $request, $complaintId)
{
    $complaint = Complaint::findOrFail($complaintId);
    $complaint->status = $request->status;
    $complaint->save();

    return redirect()->route('admin.complaints.index')->with('success', 'Complaint status updated successfully.');
}

public function storeReply(Request $request, $complaintId)
{
    $reply = new ComplaintReply([
        'message' => $request->message,
        'user_id' => auth()->id(),
        'complaint_id' => $complaintId,
    ]);
    $reply->save();

    // Handle media upload
    if ($request->hasFile('video')) {
        foreach ($request->file('video') as $file) {
            $path = $file->store('reply_videos', 'public');
            ReplyMedia::create([
                'reply_id' => $reply->id,
                'file_path' => $path,
            ]);
        }
    }

    return redirect()->back();
}

}
