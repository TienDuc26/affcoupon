<link rel="stylesheet" href="/css/categorymanagement.css" />

<div class="content-card">

    <!-- HEADER -->
    <div class="page-header mb-4">
        <div>
            <h2 class="page-title">Thêm danh mục</h2>
            <p class="page-subtitle text-muted">Tạo danh mục mới</p>
        </div>
    </div>

    <!-- FORM -->
    <form action="/admin/addcategory" method="POST">
        
        <input type="hidden" name="__RequestVerificationToken" value="<?php echo BaseController::generateAntiForgeryToken(); ?>">

        <!-- NAME -->
        <div class="mb-3">
            <label class="form-label font-weight-bold">Tên danh mục <span class="text-danger">*</span></label>
            <input type="text" name="Name" id="nameInput" class="form-control custom-input" placeholder="Nhập tên danh mục" required />
        </div>

        <!-- SLUG -->
        <div class="mb-3">
            <label class="form-label font-weight-bold">Slug <span class="text-danger">*</span></label>
            <input type="text" name="Slug" id="slugInput" class="form-control custom-input" placeholder="Slug tự động tạo" required />
        </div>

        <!-- DESCRIPTION -->
        <div class="mb-3">
            <label class="form-label font-weight-bold">Mô tả</label>
            <textarea name="Description" rows="5" class="form-control custom-input" placeholder="Nhập mô tả danh mục"></textarea>
        </div>

        <!-- STATUS -->
        <div class="mb-3">
            <label class="form-label d-block font-weight-bold">Trạng thái</label>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="IsActive" value="1" checked id="isActiveSwitch" />
                <label class="form-check-label" for="isActiveSwitch">
                    Hiển thị danh mục
                </label>
            </div>
        </div>

        <!-- BUTTON -->
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-success px-4 mr-2">
                <i class="fas fa-save"></i> Lưu danh mục
            </button>
            <a href="/admin/categorymanagement" class="btn btn-outline-secondary px-4">
                Quay lại
            </a>
        </div>

    </form>

</div>

<script>
    const nameInput = document.getElementById("nameInput");
    const slugInput = document.getElementById("slugInput");

    nameInput.addEventListener("input", function () {
        let slug = this.value.toLowerCase();

        // Bỏ dấu tiếng Việt
        slug = slug.normalize("NFD")
                   .replace(/[\u0300-\u036f]/g, "");

        // đ -> d
        slug = slug.replace(/đ/g, "d");

        // Thay khoảng trắng thành -
        slug = slug.replace(/\s+/g, "-");

        // Bỏ ký tự đặc biệt
        slug = slug.replace(/[^a-z0-9-]/g, "");

        // Bỏ -- liên tiếp
        slug = slug.replace(/-+/g, "-");

        // Bỏ - đầu cuối
        slug = slug.replace(/^-|-$/g, "");

        slugInput.value = slug;
    });
</script>
