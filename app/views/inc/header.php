<?php
// Safety check for constants
if (!defined('APPROOT')) {
    echo '<div style="color: red; background: #ffdddd; padding: 15px; margin: 15px; border: 1px solid #ff0000; border-radius: 5px;">
        <h2>Configuration Error:</h2>
        <p>APPROOT constant is not defined. Please check app/config/config.php</p>
    </div>';
    exit;
}

// Include language helper if not already included
require_once APPROOT . '/helpers/language_helper.php';
?>
<!DOCTYPE html>
<html lang="<?php echo getCurrentLanguage(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo isset($data['title']) ? $data['title'] : SITE_NAME; ?></title>
    <meta name="description" content="<?php echo isset($data['description']) ? $data['description'] : __('create_music_with_ai', 'Create music with artificial intelligence'); ?>">
    
    <!-- Favicon -->
    <link rel="icon" href="/public/img/favicon.png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/public/css/style.css">
    <!-- User theme preference -->
    <?php if(isset($_SESSION['user_data']) && isset($_SESSION['user_data']->theme)): ?>
    <link rel="stylesheet" href="/public/css/themes/<?php echo $_SESSION['user_data']->theme; ?>.css">
    <?php else: ?>
    <link rel="stylesheet" href="/public/css/themes/dark.css">
    <?php endif; ?>
    
    <!-- Mobile-specific stylesheet -->
    <link rel="stylesheet" href="/public/css/mobile.css">
    
    <!-- PWA Support -->
    <link rel="manifest" href="/public/manifest.json">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#0a0a12">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="/public/img/icon-192.png">
    
    <!-- Site Meta Data for JavaScript -->
    <meta name="urlroot" content="<?php echo URLROOT; ?>">
    <meta name="add_to_favorites" content="<?php echo __('add_to_favorites', 'Favorilere Ekle'); ?>">
    <meta name="remove_from_favorites" content="<?php echo __('remove_from_favorites', 'Favorilerden Çıkar'); ?>">
    
    <!-- Additional page-specific stylesheets -->
    <?php if(isset($data['additionalStyles']) && is_array($data['additionalStyles'])): ?>
        <?php foreach($data['additionalStyles'] as $stylesheet): ?>
        <link rel="stylesheet" href="<?php echo $stylesheet; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Load Chart.js for profile page -->
    <?php if(isset($_GET['url']) && $_GET['url'] == 'users/profile'): ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php endif; ?>
</head>
<body <?php if(isset($_SESSION['user_data']->theme)): ?>data-theme="<?php echo $_SESSION['user_data']->theme; ?>"<?php else: ?>data-theme="dark"<?php endif; ?>
      <?php if(isset($_SESSION['user_data']->color_scheme)): ?>data-color="<?php echo $_SESSION['user_data']->color_scheme; ?>"<?php else: ?>data-color="default"<?php endif; ?>>
    <?php include_once 'navbar.php'; ?>
    
    <!-- Flash Messages -->
    <?php flash(); ?>
    
    <!-- Welcome Modal for first-time users -->
    <?php if(isset($_SESSION['user_data']) && !isset($_SESSION['welcome_shown'])): ?>
    <div id="welcome-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2><?php echo __('welcome_to', 'Welcome to Octaverum AI'); ?></h2>
            <div class="modal-body">
                <p><?php echo __('get_started', 'Let\'s get you started with generating amazing music using AI!'); ?></p>
                <div class="welcome-steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h3><?php echo __('create_music', 'Create Music'); ?></h3>
                            <p><?php echo __('describe_music', 'Describe the music you want to create using our AI prompt system.'); ?></p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h3><?php echo __('customize_parameters', 'Customize Parameters'); ?></h3>
                            <p><?php echo __('fine_tune', 'Fine-tune your creation with genre, BPM, key, and more.'); ?></p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h3><?php echo __('save_and_share', 'Save and Share'); ?></h3>
                            <p><?php echo __('save_tracks', 'Save your tracks to your library, create playlists, and more!'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <a href="/music/generate" class="btn btn-primary pulse-btn"><?php echo __('generate_first_track', 'Generate Your First Track'); ?></a>
                </div>
            </div>
        </div>
    </div>
    <?php $_SESSION['welcome_shown'] = true; ?>
    <?php endif; ?>
    
    <div class="container">