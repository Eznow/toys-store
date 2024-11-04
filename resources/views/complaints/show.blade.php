@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Chi tiết khiếu nại</h2>
    
    <h5>Thông tin khiếu nại</h5>
    <p><strong>Người khiếu nại:</strong> {{ $complaint->user->name }}</p>
    <p><strong>Mô tả:</strong> {{ $complaint->description }}</p>
    <p><strong>Video:</strong></p>
    @if($complaint->media->isNotEmpty())
        <video width="400" controls>
            <source src="{{ asset('storage/' . $complaint->media->first()->file_path) }}" type="video/mp4">
        </video>
    @endif

    <hr>
    <h5>Phản hồi</h5>
    <div>
       @foreach ($complaint->replies as $reply)
           <p><strong>{{ $reply->user->name }}:</strong> {{ $reply->message }}</p>
           @foreach ($reply->media as $media)
               @php
                   $extension = pathinfo($media->file_path, PATHINFO_EXTENSION);
               @endphp
               @if (in_array($extension, ['mp4', 'mov', 'avi']))
                   <video width="400" controls>
                       <source src="{{ asset('storage/' . $media->file_path) }}" type="video/mp4">
                   </video>
               @elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                   <img src="{{ asset('storage/' . $media->file_path) }}" alt="Reply Media" style="width: 400px;">
               @else
                   <p>Unsupported file type</p>
               @endif
           @endforeach
       @endforeach
    </div>

    <hr>

    <!-- Form trả lời khiếu nại -->
    <form action="{{ route('complaints.reply', $complaint->complaint_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="message">Phản hồi</label>
            <textarea name="message" id="message" class="form-control" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="video">Đính kèm video (nếu có):</label>
            <input type="file" name="video" id="video" class="form-control-file">
        </div>
        <button type="submit" class="btn btn-primary mt-3">Gửi phản hồi</button>
    </form>
</div>
@endsection
