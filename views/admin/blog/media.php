<div class="content-card">

    <div class="page-header mb-4">
        <h3>Thư viện Media</h3>
    </div>

    <div class="row">
        <!-- UPLOAD FORM -->
        <div class="col-md-4 mb-4">
            <div class="card p-3 border-0 shadow-sm">
                <h5 class="text-success mb-3">Tải Ảnh Mới Lên</h5>
                <form action="/admin/medialibrary" method="POST" enctype="multipart/form-data">
                    
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 500;">Chọn Hình Ảnh <span class="text-danger">*</span></label>
                        <input type="file" name="MediaFile" class="form-control" accept="image/*" required style="border-radius: 6px; padding: 6px;" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 500;">Thẻ ALT (SEO)</label>
                        <input type="text" name="Alt" class="form-control" placeholder="Mô tả hình ảnh phục vụ SEO..." style="border-radius: 6px;" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 500;">Chú thích ảnh</label>
                        <input type="text" name="Caption" class="form-control" placeholder="Chú thích hiển thị dưới ảnh..." style="border-radius: 6px;" />
                    </div>

                    <button type="submit" class="btn btn-success w-100" style="background-color: #16A516 !important; border: none; border-radius: 6px; font-weight: 600;">
                        <i class="fas fa-upload"></i> Tải Lên
                    </button>
                </form>
            </div>
        </div>

        <!-- MEDIA LIST GRID -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-3">
                <h5 class="text-secondary mb-3">Hình ảnh trong Thư viện</h5>
                
                <?php if (!empty($mediaFiles)): ?>
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                        <?php foreach ($mediaFiles as $file): ?>
                            <div class="col mb-3">
                                <div class="card h-100 border shadow-none" style="border-radius: 8px; overflow: hidden; background: #fafafa;">
                                    <div class="ratio ratio-4x3 d-flex align-items-center justify-content-center border-bottom bg-white" style="height: 140px; overflow: hidden;">
                                        <img src="<?php echo htmlspecialchars($file['url']); ?>" alt="<?php echo htmlspecialchars($file['alt'] ?? ''); ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;" />
                                    </div>
                                    <div class="card-body p-2 d-flex flex-column justify-content-between">
                                        <div>
                                            <small class="d-block text-truncate text-muted" style="font-size: 0.75rem;" title="<?php echo htmlspecialchars($file['caption'] ?? ''); ?>">
                                                <?php echo htmlspecialchars($file['caption'] ?: 'Không có chú thích'); ?>
                                            </small>
                                        </div>
                                        <div class="mt-2 pt-2 border-top d-flex justify-content-between align-items-center">
                                            <!-- Copy Link Button -->
                                            <button type="button" class="btn btn-sm btn-outline-success px-2 py-1" onclick="copyToClipboard('<?php echo htmlspecialchars($file['url']); ?>', this)" style="font-size: 0.75rem; border-radius: 4px;">
                                                <i class="far fa-copy"></i> Copy URL
                                            </button>
                                            
                                            <!-- Delete Form -->
                                            <form action="/admin/deletemedia" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa vĩnh viễn hình ảnh này khỏi server?')" class="d-inline">
                                                <input type="hidden" name="id" value="<?php echo $file['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-2 py-1" style="font-size: 0.75rem; border-radius: 4px; padding: 0;">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="far fa-image fa-3x mb-3 text-light"></i>
                        <p>Thư viện media trống. Hãy tải lên ảnh đầu tiên!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<script>
    function copyToClipboard(text, btnElement) {
        // Build absolute URL
        const absoluteUrl = window.location.origin + text;
        
        navigator.clipboard.writeText(absoluteUrl).then(() => {
            const originalHTML = btnElement.innerHTML;
            btnElement.innerHTML = '<i class="fas fa-check"></i> Copied!';
            btnElement.classList.remove('btn-outline-success');
            btnElement.classList.add('btn-success');
            btnElement.style.color = '#fff';
            
            setTimeout(() => {
                btnElement.innerHTML = originalHTML;
                btnElement.classList.remove('btn-success');
                btnElement.classList.add('btn-outline-success');
                btnElement.style.color = '';
            }, 1500);
        }).catch(err => {
            console.error('Không thể copy link: ', err);
        });
    }
</script>
