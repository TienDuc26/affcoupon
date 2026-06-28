<link rel="stylesheet" href="/css/addstore.css" />

<div class="content-card add-store-page">

    <!-- PAGE HEADER -->
    <div class="page-header mb-4">
        <div>
            <h3 class="page-title">Thêm mới store</h3>
        </div>
    </div>

    <!-- FORM -->
    <form action="/admin/addstore" method="POST" enctype="multipart/form-data">
        
        <input type="hidden" name="__RequestVerificationToken" value="<?php echo BaseController::generateAntiForgeryToken(); ?>">

        <!-- STORE NAME -->
        <div class="form-row">
            <label>Tên store <span class="text-danger">*</span></label>
            <div class="form-right">
                <input type="text" name="Title" class="form-control-custom" placeholder="Tên store" required />
            </div>
        </div>

        <!-- SLUG -->
        <div class="form-row">
            <label>Slug <span class="text-danger">*</span></label>
            <div class="form-right">
                <input type="text" name="Slug" id="slug" class="form-control-custom" placeholder="slug-store" required />
            </div>
        </div>

        <!-- CATEGORY -->
        <div class="form-row">
            <label>Danh mục <span class="text-danger">*</span></label>
            <div class="form-right">
                <select name="CategoryId" class="form-control-custom" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>">
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <!-- AFFILIATE URL -->
        <div class="form-row">
            <label>Affiliate URL <span class="text-danger">*</span></label>
            <div class="form-right">
                <input type="url" name="AffiliateUrl" class="form-control-custom" placeholder="https://..." required />
            </div>
        </div>

        <!-- IMAGE -->
        <div class="form-row">
            <label>Thumbnail</label>
            <div class="form-right">
                <input type="file" name="ImageFile" class="form-control-custom" accept="image/*" />
            </div>
        </div>

        <!-- ACTIVE -->
        <div class="form-row">
            <label>Hiển thị</label>
            <div class="form-right">
                <label class="switch">
                    <input type="checkbox" name="IsActive" value="1" checked />
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <!-- FEATURED -->
        <div class="form-row">
            <label>Featured</label>
            <div class="form-right">
                <label class="switch">
                    <input type="checkbox" name="IsFeatured" value="1" />
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <!-- DESCRIPTION -->
        <div class="form-row">
            <label>Mô tả ngắn</label>
            <div class="form-right">
                <textarea name="Description" class="editor-box" rows="4" placeholder="Mô tả ngắn cho store..."></textarea>
            </div>
        </div>

        <!-- ABOUT STORE -->
        <div class="form-row">
            <label>About store</label>
            <div class="form-right">
                <textarea name="AboutStore" id="AboutStore" class="editor-box" rows="8" placeholder="Nội dung giới thiệu chi tiết về store..."></textarea>
            </div>
        </div>

        <!-- FAQS -->
        <div class="form-row">
            <label>FAQs</label>
            <div class="form-right">
                <textarea name="FaqText" class="editor-box" rows="8" placeholder="Question 1|Answer 1&#10;Question 2|Answer 2"></textarea>
                <small class="form-note text-muted d-block mt-1">
                    Mỗi dòng là 1 FAQ. Ngăn cách Câu hỏi và Câu trả lời bởi ký tự <strong>|</strong>.<br>
                    Ví dụ: <em>How to use coupon?|Copy code and apply at checkout</em>
                </small>
            </div>
        </div>

        <!-- SUBMIT -->
        <div class="submit-row mt-4">
            <button type="submit" class="btn btn-success">
                <i class="fa fa-save"></i> Save Store
            </button>
            <a href="/admin/dashboardadmin" class="btn btn-outline-secondary ml-2">
                Quay lại
            </a>
        </div>

    </form>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    // Auto-generate slug from store title safely
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
            console.warn('TinyMCE not loaded from cdnjs, trying jsdelivr...');
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
            selector: '#AboutStore',
            plugins: 'link image lists table code',
            menubar: 'file edit insert view format table',
            toolbar: 'link unlink image | blocks | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | code',
            height: 380,
            branding: false,
            promotion: false,
            setup: function (editor) {
                editor.on('change', function () {
                    tinymce.triggerSave();
                });
            }
        });
    }

    // Run on window load to ensure everything is ready
    window.addEventListener('load', initTinyMCE);
</script>
