<link rel="stylesheet" href="/css/addstore.css" />

<div class="content-card add-store-page">

    <!-- PAGE HEADER -->
    <div class="page-header mb-4">
        <div>
            <h3 class="page-title">Chỉnh sửa danh mục Blog</h3>
        </div>
    </div>

    <!-- FORM -->
    <form action="/admin/editblogcategory?id=<?php echo $category['id']; ?>" method="POST">
        
        <!-- NAME -->
        <div class="form-row">
            <label>Tên Danh mục <span class="text-danger">*</span></label>
            <div class="form-right">
                <input type="text" name="Name" class="form-control-custom" value="<?php echo htmlspecialchars($category['name']); ?>" required />
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
                            <option value="<?php echo $cat['id']; ?>" <?php echo $category['parent_id'] == $cat['id'] ? 'selected' : ''; ?>>
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
                <textarea name="Description" class="form-control-custom" rows="4" placeholder="Mô tả danh mục..."><?php echo htmlspecialchars($category['description'] ?? ''); ?></textarea>
            </div>
        </div>

        <!-- ACTIVE -->
        <div class="form-row">
            <label>Trạng thái kích hoạt</label>
            <div class="form-right">
                <label class="switch">
                    <input type="checkbox" name="IsActive" value="1" <?php echo $category['is_active'] ? 'checked' : ''; ?> />
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <!-- SUBMIT -->
        <div class="submit-row mt-4">
            <button type="submit" class="btn btn-success" style="background-color: #16A516 !important; border: none;">
                <i class="fa fa-save"></i> Cập nhật
            </button>
            <a href="/admin/blogcategories" class="btn btn-outline-secondary ml-2">
                Quay lại
            </a>
        </div>

    </form>

</div>
