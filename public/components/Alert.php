<?php
function Alert() { 
    $notif_map = ['success' => 'fa-circle-check', 'error' => 'fa-circle-exclamation'];
    
    foreach ($notif_map as $key => $icon): ?>
        <?php if (isset($_SESSION[$key])): ?>
            <div class="alert alert-<?= $key === 'success' ? 'success' : 'danger' ?> py-2 px-3 small border-0 mb-4"
                style="border-radius: 8px;">
                <i class="fa-solid <?= $icon ?> me-2"></i>
                <?= htmlspecialchars($_SESSION[$key], ENT_QUOTES, 'UTF-8'); ?>
                <?php unset($_SESSION[$key]); ?>
            </div>
        <?php endif; ?>
    <?php endforeach; 
} 
?>