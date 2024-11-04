@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Chi tiết Khiếu nại #{{ $complaint->complaint_id }}</h2>
    <p><strong>Người khiếu nại:</strong> {{ $complaint->user->name }}</p>
    <p><strong>Đơn hàng:</strong> #{{ $complaint->order_id }}</p>
    <p><strong>Mô tả:</strong> {{ $complaint->description }}</p>
    <p><strong>Trạng thái:</strong> {{ $complaint->status }}</p>

    <h4>Phản hồi</h4>
    @foreach($complaint->replies as $reply)
        <div class="mb-3">
            <p><strong>{{ $reply->user->name }}:</strong> {{ $reply->message }}</p>
            <small>{{ $reply->created_at }}</small>
        </div>
    @endforeach

    <!-- Form để admin thêm phản hồi -->
    <form action="{{ route('complaints.reply', $complaint->complaint_id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="message">Trả lời</label>
            <textarea class="form-control" id="message" name="message" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Gửi phản hồi</button>
    </form>
</div>
@endsection
