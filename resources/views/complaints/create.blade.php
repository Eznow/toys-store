<!-- Modal cho form Khiếu nại -->
<div class="modal fade" id="complaintModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('complaints.store', $product->product_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Gửi Khiếu nại</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="file">Tải lên video (không bắt buộc)</label>
                        <input type="file" name="file" class="form-control" accept="video/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Gửi Khiếu nại</button>
                </div>
            </form>
        </div>
    </div>
</div>
