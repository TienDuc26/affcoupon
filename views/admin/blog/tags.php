<div class="content-card">

    <div class="page-header mb-4">
        <h3>Quản lý thẻ Tags</h3>
    </div>

    <div class="row">
        <!-- ADD TAG FORM -->
        <div class="col-md-4 mb-4">
            <div class="card p-3 border-0 shadow-sm">
                <h5 class="text-success mb-3">Thêm Tag Mới</h5>
                <form action="/admin/blogtags" method="POST">
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 500;">Tên Tag <span class="text-danger">*</span></label>
                        <input type="text" name="Name" class="form-control" placeholder="Ví dụ: AI, Web Dev" required style="border-radius: 6px;" />
                    </div>
                    <button type="submit" class="btn btn-success w-100" style="background-color: #16A516 !important; border: none; border-radius: 6px; font-weight: 600;">
                        <i class="fas fa-plus"></i> Thêm Tag
                    </button>
                </form>
            </div>
        </div>

        <!-- TAGS LIST -->
        <div class="col-md-8">
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên Tag</th>
                            <th>Slug (SEO)</th>
                            <th width="100">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($tags)): ?>
                            <?php foreach ($tags as $tag): ?>
                                <tr>
                                    <td><?php echo $tag['id']; ?></td>
                                    <td><strong><?php echo htmlspecialchars($tag['name']); ?></strong></td>
                                    <td><code><?php echo htmlspecialchars($tag['slug']); ?></code></td>
                                    <td>
                                        <form action="/admin/deleteblogtag" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tag này? Các bài viết liên quan sẽ không bị xóa mà chỉ gỡ liên kết tag này.')" class="d-inline">
                                            <input type="hidden" name="id" value="<?php echo $tag['id']; ?>">
                                            <button type="submit" class="btn btn-link text-danger p-0 border-0" style="cursor: pointer;">
                                                <i class="fa fa-trash"></i> Xóa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Không tìm thấy tag nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
