<link rel="stylesheet" href="/css/addstore.css" />

<div class="content-card add-store-page">

    <!-- PAGE HEADER -->
    <div class="page-header mb-4">
        <div>
            <h3 class="page-title">Viết bài mới</h3>
        </div>
    </div>

    <!-- FORM -->
    <form action="/admin/addblogpost" method="POST" enctype="multipart/form-data">
        
        <input type="hidden" name="__RequestVerificationToken" value="<?php echo BaseController::generateAntiForgeryToken(); ?>">

        <!-- POST TITLE -->
        <div class="form-row">
            <label>Tiêu đề bài viết <span class="text-danger">*</span></label>
            <div class="form-right">
                <input type="text" name="Title" class="form-control-custom" placeholder="Nhập tiêu đề bài viết" required />
            </div>
        </div>

        <!-- SLUG -->
        <div class="form-row">
            <label>Slug <span class="text-danger">*</span></label>
            <div class="form-right">
                <input type="text" name="Slug" id="slug" class="form-control-custom" placeholder="slug-bai-viet" required />
            </div>
        </div>

        <!-- CATEGORY -->
        <div class="form-row">
            <label>Danh mục <span class="text-danger">*</span></label>
            <div class="form-right">
                <select name="CategoryId" class="form-control-custom" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php if (!empty($blogCategories)): ?>
                        <?php foreach ($blogCategories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>">
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <!-- IMAGE -->
        <div class="form-row">
            <label>Ảnh Thumbnail</label>
            <div class="form-right">
                <input type="file" name="ThumbnailFile" class="form-control-custom" accept="image/*" />
            </div>
        </div>

        <!-- TAGS -->
        <div class="form-row">
            <label>Tags</label>
            <div class="form-right">
                <input type="text" name="Tags" class="form-control-custom" placeholder="Ví dụ: AI, Web Development, Tutorial (cách nhau bằng dấu phẩy)" />
            </div>
        </div>

        <!-- STATUS -->
        <div class="form-row">
            <label>Trạng thái</label>
            <div class="form-right">
                <select name="Status" class="form-control-custom">
                    <option value="draft">Bản nháp (Draft)</option>
                    <option value="published">Xuất bản (Published)</option>
                </select>
            </div>
        </div>

        <!-- SUMMARY -->
        <div class="form-row">
            <label>Tóm tắt / Mô tả ngắn</label>
            <div class="form-right">
                <textarea name="Summary" class="editor-box" rows="3" placeholder="Tóm tắt ngắn gọn bài viết để hiển thị ở danh sách bài..."></textarea>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="form-row">
            <label>Nội dung chi tiết</label>
            <div class="form-right">
                <textarea name="Content" id="Content" class="editor-box" rows="15" placeholder="Nội dung bài viết..."></textarea>
            </div>
        </div>

        <!-- SEO ACCORDION / TOGGLE HEADER -->
        <div class="my-4 border-top pt-3">
            <h5 class="text-success mb-3"><i class="fas fa-search"></i> Cấu hình SEO Meta</h5>
            
            <div class="form-row">
                <label>SEO Title</label>
                <div class="form-right">
                    <input type="text" name="SeoTitle" class="form-control-custom" placeholder="Mặc định sử dụng tiêu đề bài viết" />
                </div>
            </div>

            <div class="form-row">
                <label>SEO Description</label>
                <div class="form-right">
                    <textarea name="SeoDescription" class="form-control-custom" rows="3" placeholder="Mô tả SEO tối ưu hóa tìm kiếm Google"></textarea>
                </div>
            </div>

            <div class="form-row">
                <label>Canonical URL</label>
                <div class="form-right">
                    <input type="url" name="CanonicalUrl" class="form-control-custom" placeholder="https://example.com/canonical-url" />
                </div>
            </div>
        </div>

        <!-- SUBMIT -->
        <div class="submit-row mt-4">
            <button type="submit" class="btn btn-success" style="background-color: #16A516 !important; border: none;">
                <i class="fa fa-save"></i> Lưu bài viết
            </button>
            <a href="/admin/blogposts" class="btn btn-outline-secondary ml-2">
                Quay lại
            </a>
        </div>

    </form>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    // Auto-generate slug from title safely
    const titleInput = document.querySelector('input[name="Title"]');
    if (titleInput) {
        titleInput.addEventListener('input', function() {
            let title = this.value;
            let slug = title.toLowerCase()
                .replace(/[áàảãạăắằẳẵặâấầẩẫậ]/g, 'a')
                .replace(/[éèẻẽẹêếềểễệ]/g, 'e')
                .replace(/[íìỉĩị]/g, 'i')
                .replace(/[óòỏõọôốồổỗộơớờởỡợ]/g, 'o')
                .replace(/[úùủũụưứừửữự]/g, 'u')
                .replace(/[ýỳỷỹỵ]/g, 'y')
                .replace(/đ/g, 'd')
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            const slugInput = document.getElementById('slug');
            if (slugInput) {
                slugInput.value = slug;
            }
        });
    }

    function initTinyMCE() {
        if (typeof tinymce === 'undefined') {
            var script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/tinymce@6.8.2/tinymce.min.js';
            script.referrerPolicy = 'origin';
            script.onload = function() {
                startTinyMCE();
            };
            document.head.appendChild(script);
        } else {
            startTinyMCE();
        }
    }

    function startTinyMCE() {
        tinymce.init({
            selector: '#Content',
            plugins: 'link image media lists table code hr wordcount',
            menubar: 'file edit insert view format table',
            toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table hr blockquote code',
            height: 500,
            branding: false,
            promotion: false,
            // Configure media library uploads for images inside content
            images_upload_url: '/admin/uploadmediaajax',
            automatic_uploads: true,
            relative_urls: false,
            remove_script_host: false,
            setup: function (editor) {
                editor.on('change', function () {
                    tinymce.triggerSave();
                });
            }
        });
    }

    window.addEventListener('load', initTinyMCE);
</script>
