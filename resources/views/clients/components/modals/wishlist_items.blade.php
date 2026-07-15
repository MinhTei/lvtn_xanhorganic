<!-- Modal Yêu Thích -->
<div class="modal fade" id="wishlistModal" tabindex="-1" aria-labelledby="wishlistModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="wishlistModalLabel">Yêu thích của bạn</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="wishlist-modal-body">
        <div class="text-center py-4 text-muted">
          <div class="spinner-border text-success" role="status"></div>
          <p class="mt-2">Đang tải...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="btn-wishlist-add-all">
          Thêm tất cả vào giỏ hàng
        </button>
        <a href="{{ route('cart') }}" class="btn btn-primary">Đến giỏ hàng</a>
      </div>
    </div>
  </div>
</div>
