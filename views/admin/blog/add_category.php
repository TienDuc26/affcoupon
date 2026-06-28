<link rel="stylesheet" href="/css/addstore.css" />

<div class="content-card add-store-page">

    <!-- PAGE HEADER -->
    <div class="page-header mb-4">
        <div>
            <h3 class="page-title">Thêm danh mục Blog</h3>
        </div>
    </div>

    <!-- FORM -->
    <form action="/admin/addblogcategory" method="POST">
        
        <input type="hidden" name="__RequestVerificationToken" value="<?php echo BaseController::generateAntiForgeryToken(); ?>">

        <!-- NAME -->
        <div class="form-row">
            <label>Tên Danh mục <span class="text-danger">*</span></label>
            <div class="form-right">
                <input type="text" name="Name" class="form-control-custom" placeholder="Tên danh mục" required />
            </div>
        </div>

        <!-- SLUG -->
        <div class="form-row">
            <label>Slug <span class="text-danger">*</span></label>
            <div class="form-right">
                <input type="text" name="Slug" id="slug" class="form-control-custom" placeholder="slug-danh-muc" required />
            </div>
        </div>

        <!-- PARENT CATEGORY -->
        <div class="form-row">
            <label>Danh mục cha</label>
            <div class="form-right">
                <select name="ParentId" class="form-control-custom">
                    <option value="">-- Không có (Danh mục gốc) --</option>
                    <?php if (!empty($parentCategories)): ?>
                        <?php foreach ($parentCategories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>">
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <!-- DESCRIPTION -->
        <div class="form-row">
            <label>Mô tả ngắn</label>
            <div class="form-right">
                <textarea name="Description" class="form-control-custom" rows="4" placeholder="Mô tả danh mục..."></textarea>
            </div>
        </div>

        <!-- ACTIVE -->
        <div class="form-row">
            <label>Trạng thái kích hoạt</label>
            <div class="form-right">
                <label class="switch">
                    <input type="checkbox" name="IsActive" value="1" checked />
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <!-- SUBMIT -->
        <div class="submit-row mt-4">
            <button type="submit" class="btn btn-success" style="background-color: #16A516 !important; border: none;">
                <i class="fa fa-save"></i> Thêm Danh mục
            </button>
            <a href="/admin/blogcategories" class="btn btn-outline-secondary ml-2">
                Quay lại
            </a>
        </div>

    </form>

</div>

<script>
    // Auto-generate slug from name safely
    const nameInput = document.querySelector('input[name="Name"]');
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            let name = this.value;
            let slug = name.toLowerCase()
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
</script>
