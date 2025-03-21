<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="page-container">
    <div class="container">
        <div class="page-header text-center">
            <h1><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Çalma Listeleri' : 'Playlists'; ?></h1>
            <p class="text-secondary"><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Müzik koleksiyonunuzu düzenleyin ve yönetin' : 'Organize and manage your music collection'; ?></p>
        </div>

        <div class="playlist-actions mb-4 text-center">
            <a href="<?php echo URL_ROOT; ?>/music/createPlaylist" class="btn btn-glow with-icon">
                <i class="fas fa-plus"></i>
                <span><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Yeni Çalma Listesi Oluştur' : 'Create New Playlist'; ?></span>
            </a>
        </div>

        <?php flash('playlist_success'); ?>

        <?php if (empty($data['playlists'])) : ?>
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-list"></i></div>
                <h3><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Henüz çalma listeniz yok' : 'No playlists yet'; ?></h3>
                <p><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Müziğinizi düzenlemek için çalma listeleri oluşturun' : 'Create playlists to organize your music'; ?></p>
                <a href="<?php echo URL_ROOT; ?>/music/createPlaylist" class="btn btn-primary">
                    <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'İlk Çalma Listenizi Oluşturun' : 'Create Your First Playlist'; ?>
                </a>
            </div>
        <?php else : ?>
            <div class="playlists-grid-container">
                <div class="playlists-grid">
                    <?php foreach ($data['playlists'] as $playlist) : ?>
                        <div class="playlist-card animate-on-scroll">
                            <div class="playlist-art">
                                <div class="playlist-icon">
                                    <i class="fas fa-music"></i>
                                </div>
                                <div class="playlist-overlay">
                                    <a href="<?php echo URL_ROOT; ?>/music/playlist/<?php echo $playlist->id; ?>" class="view-btn">
                                        <i class="fas fa-list"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="playlist-info">
                                <h3 class="playlist-title"><?php echo htmlspecialchars($playlist->name); ?></h3>
                                <p class="playlist-meta">
                                    <span class="track-count"><?php echo $playlist->track_count; ?> <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'parça' : 'tracks'; ?></span>
                                    <span class="created-date"><?php echo date('M j, Y', strtotime($playlist->created_at)); ?></span>
                                </p>
                                <div class="playlist-description mb-2">
                                    <?php echo !empty($playlist->description) ? htmlspecialchars(substr($playlist->description, 0, 60)) . (strlen($playlist->description) > 60 ? '...' : '') : ''; ?>
                                </div>
                                <div class="playlist-actions">
                                    <a href="<?php echo URL_ROOT; ?>/music/playlist/<?php echo $playlist->id; ?>" class="btn btn-sm"><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Görüntüle' : 'View'; ?></a>
                                    <a href="<?php echo URL_ROOT; ?>/music/editPlaylist/<?php echo $playlist->id; ?>" class="btn-icon">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?php echo URL_ROOT; ?>/music/deletePlaylist/<?php echo $playlist->id; ?>" class="btn-icon delete-btn">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <!-- Create New Playlist Card -->
                    <div class="playlist-card create-playlist animate-on-scroll">
                        <a href="<?php echo URL_ROOT; ?>/music/createPlaylist" class="create-link">
                            <div class="create-icon">
                                <i class="fas fa-plus"></i>
                            </div>
                            <span><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Yeni Çalma Listesi Oluştur' : 'Create New Playlist'; ?></span>
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Animation for playlist cards
    document.addEventListener('DOMContentLoaded', function() {
        const animatedElements = document.querySelectorAll('.animate-on-scroll');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = 1;
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });
        
        animatedElements.forEach(element => {
            element.style.opacity = 0;
            element.style.transform = 'translateY(20px)';
            element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(element);
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>