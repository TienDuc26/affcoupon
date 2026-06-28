<?php
$isFavorite = isset($UserFavorites) && in_array($item['id'], $UserFavorites);
$isBookmark = isset($UserBookmarks) && in_array($item['id'], $UserBookmarks);
$shareUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/coupon/' . $item['slug'];
$shareTitle = $item['title'];
?>
<div class="coupon-action-bar border-top pt-3 mt-3 d-flex justify-content-around align-items-center" data-coupon-id="<?php echo $item['id']; ?>">
    <!-- Favorite Button -->
    <button class="action-btn fav-btn <?php echo $isFavorite ? 'active' : ''; ?>" onclick="toggleFavorite(this, <?php echo $item['id']; ?>)" data-bs-toggle="tooltip" data-bs-placement="top" title="Yêu thích">
        <i class="<?php echo $isFavorite ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart'; ?>"></i>
    </button>
    
    <!-- Share Button Wrapper -->
    <div class="position-relative share-dropdown-wrapper">
        <button class="action-btn share-btn" onclick="toggleShareMenu(this, <?php echo $item['id']; ?>, '<?php echo htmlspecialchars($shareUrl); ?>', '<?php echo htmlspecialchars($shareTitle); ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Chia sẻ">
            <i class="fa-solid fa-share-nodes"></i>
        </button>
        
        <!-- Custom Share Dropdown Menu -->
        <div class="custom-share-menu shadow-lg rounded-3 d-none">
            <a href="#" class="share-item" onclick="shareAction(event, 'copy', '<?php echo htmlspecialchars($shareUrl); ?>')">
                <i class="fa-regular fa-copy me-2"></i> Sao chép liên kết
            </a>
            <a href="#" class="share-item" onclick="shareAction(event, 'facebook', '<?php echo htmlspecialchars($shareUrl); ?>')">
                <i class="fab fa-facebook me-2 text-primary"></i> Facebook
            </a>
            <a href="#" class="share-item" onclick="shareAction(event, 'twitter', '<?php echo htmlspecialchars($shareUrl); ?>', '<?php echo htmlspecialchars($shareTitle); ?>')">
                <i class="fab fa-x-twitter me-2 text-dark"></i> X (Twitter)
            </a>
            <a href="#" class="share-item" onclick="shareAction(event, 'whatsapp', '<?php echo htmlspecialchars($shareUrl); ?>', '<?php echo htmlspecialchars($shareTitle); ?>')">
                <i class="fab fa-whatsapp me-2 text-success"></i> WhatsApp
            </a>
            <a href="#" class="share-item" onclick="shareAction(event, 'telegram', '<?php echo htmlspecialchars($shareUrl); ?>', '<?php echo htmlspecialchars($shareTitle); ?>')">
                <i class="fab fa-telegram me-2 text-info"></i> Telegram
            </a>
        </div>
    </div>
    
    <!-- Bookmark Button -->
    <button class="action-btn bookmark-btn <?php echo $isBookmark ? 'active' : ''; ?>" onclick="toggleBookmark(this, <?php echo $item['id']; ?>)" data-bs-toggle="tooltip" data-bs-placement="top" title="Lưu">
        <i class="<?php echo $isBookmark ? 'fa-solid fa-bookmark text-success' : 'fa-regular fa-bookmark'; ?>"></i>
    </button>
</div>
