<?php require_once APPROOT . '/views/inc/header.php'; ?>

<div class="music-library-container">
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sol Menü -->
            <div class="col-md-2 sidebar-menu">
                <div class="sidebar-wrapper">
                    <div class="nav-section">
                        <div class="brand-logo mb-4">
                            <h3>Audio <span class="text-primary">X</span></h3>
                        </div>
                        
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo URLROOT; ?>/pages/index">
                                    <i class="fas fa-home me-2"></i> <span class="nav-text">Home</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo URLROOT; ?>/music/search">
                                    <i class="fas fa-search me-2"></i> <span class="nav-text">Search</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="<?php echo URLROOT; ?>/music/library">
                                    <i class="fas fa-book me-2"></i> <span class="nav-text">My library</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="nav-section mt-4">
                        <h6 class="nav-header">COLLECTION</h6>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo URLROOT; ?>/music/createPlaylist">
                                    <i class="fas fa-plus-circle me-2"></i> <span class="nav-text">New playlist</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo URLROOT; ?>/music/library/favorites">
                                    <i class="fas fa-heart me-2"></i> <span class="nav-text">Liked songs</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="nav-section mt-4">
                        <h6 class="nav-header">USER</h6>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo URLROOT; ?>/music/settings">
                                    <i class="fas fa-cog me-2"></i> <span class="nav-text">Settings</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo URLROOT; ?>/users/logout">
                                    <i class="fas fa-sign-out-alt me-2"></i> <span class="nav-text">Log out</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-footer">
                        <div class="user-profile">
                            <div class="user-avatar">
                                <img src="<?php echo URLROOT; ?>/public/img/avatar.png" alt="User avatar">
                            </div>
                            <div class="user-info">
                                <h6><?php echo $_SESSION['user_name'] ?? 'User'; ?></h6>
                                <span class="user-status">Online</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Ana İçerik -->
            <div class="col-md-10 main-content">
                <div class="top-header mb-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="page-title"><?php echo $data['genreName']; ?></h1>
                        <p class="text-muted"><?php echo count($data['tracks']); ?> parça</p>
                    </div>
                    <div>
                        <a href="<?php echo URLROOT; ?>/music/library" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kütüphaneye Dön
                        </a>
                    </div>
                </div>
                
                <!-- Hero Banner -->
                <div class="hero-banner mb-5" style="background-image: url('<?php echo URLROOT; ?>/public/images/genres/<?php echo $data['genre']; ?>.jpg');">
                    <div class="hero-gradient-overlay"></div>
                    <div class="banner-content">
                        <h2 class="mb-2"><?php echo $data['genreName']; ?></h2>
                        <h1 class="display-4 fw-bold mb-4">Collection</h1>
                        <?php if (!empty($data['tracks'])): ?>
                        <button class="btn btn-primary btn-lg rounded-circle play-all-btn">
                            <i class="fas fa-play"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Parça Listesi -->
                <?php if(!empty($data['tracks'])): ?>
                <div class="track-list mt-5">
                    <div class="row tracks-header mb-3">
                        <div class="col-md-1">#</div>
                        <div class="col-md-5">Parça</div>
                        <div class="col-md-3">Oluşturulma Tarihi</div>
                        <div class="col-md-2">Süre</div>
                        <div class="col-md-1">İşlemler</div>
                    </div>
                    
                    <?php foreach($data['tracks'] as $key => $track): ?>
                    <div class="row track-row align-items-center py-3" data-id="<?php echo $track->id; ?>" data-url="<?php echo $track->audio_url; ?>">
                        <div class="col-md-1 track-number"><?php echo $key + 1; ?></div>
                        <div class="col-md-5">
                            <div class="d-flex align-items-center">
                                <div class="track-play-button me-3">
                                    <button class="btn btn-sm btn-primary rounded-circle play-track-btn" data-id="<?php echo $track->id; ?>" data-url="<?php echo $track->audio_url; ?>">
                                        <i class="fas fa-play play-icon"></i>
                                        <i class="fas fa-pause pause-icon d-none"></i>
                                    </button>
                                </div>
                                <div class="track-info">
                                    <h5 class="mb-0"><?php echo $track->title; ?></h5>
                                    <small class="text-muted"><?php echo substr($track->prompt, 0, 60) . (strlen($track->prompt) > 60 ? '...' : ''); ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted"><?php echo date('d M Y, H:i', strtotime($track->created_at)); ?></span>
                        </div>
                        <div class="col-md-2">
                            <span class="text-muted"><?php echo gmdate("i:s", $track->duration); ?></span>
                        </div>
                        <div class="col-md-1">
                            <div class="d-flex align-items-center">
                                <button class="btn btn-sm btn-icon toggle-favorite me-2" data-id="<?php echo $track->id; ?>" data-is-favorite="<?php echo in_array($track->id, array_column($data['favoriteTracks'] ?? [], 'id')) ? 'true' : 'false'; ?>">
                                    <i class="<?php echo in_array($track->id, array_column($data['favoriteTracks'] ?? [], 'id')) ? 'fas' : 'far'; ?> fa-heart"></i>
                                </button>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-icon" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/music/track/<?php echo $track->id; ?>">Detaylar</a></li>
                                        <li><a class="dropdown-item share-track-btn" href="#" data-id="<?php echo $track->id; ?>" data-title="<?php echo $track->title; ?>">Paylaş</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/music/download/<?php echo $track->id; ?>">İndir</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="empty-state text-center my-5 py-5">
                    <div class="empty-icon mb-4">
                        <i class="fas fa-music fa-4x text-muted"></i>
                    </div>
                    <h3>Bu kategoride henüz müzik yok</h3>
                    <p class="text-muted mb-4">Bu türde müzik oluşturmak için "Yeni Müzik Oluştur" butonuna tıklayabilirsiniz.</p>
                    <a href="<?php echo URLROOT; ?>/music/generate" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i> Yeni Müzik Oluştur
                    </a>
                </div>
                <?php endif; ?>
                
                <!-- Önerilen Kategoriler -->
                <div class="suggested-categories mt-5">
                    <h3 class="mb-4">Benzer Kategoriler</h3>
                    <div class="row">
                        <?php 
                        // Tür eşleştirme haritası
                        $relatedGenres = [
                            'workout' => ['techno', 'electronic', 'pop'],
                            'techno' => ['electronic', 'workout', '80s'],
                            'quiet' => ['ambient', 'focus', 'classical'],
                            'rap' => ['hip-hop', 'pop', 'urban'],
                            'focus' => ['ambient', 'quiet', 'classical'],
                            'beach' => ['pop', 'electronic', 'ambient'],
                            'pop' => ['electronic', 'rap', '80s'],
                            'movie' => ['cinematic', 'classical', 'ambient'],
                            'folk' => ['acoustic', 'country', 'indie'],
                            'travel' => ['ambient', 'acoustic', 'world'],
                            'kids' => ['pop', 'cheerful', 'acoustic'],
                            '80s' => ['pop', 'techno', 'rock']
                        ];
                        
                        $currentGenre = $data['genre'];
                        $related = $relatedGenres[$currentGenre] ?? ['electronic', 'pop', 'ambient'];
                        
                        // Kategori başlıkları (genre.php'de tanımlandı)
                        $genreTitles = [
                            'workout' => 'Work Out',
                            'techno' => 'Techno 90s',
                            'quiet' => 'Quiet Hours',
                            'rap' => 'Rap',
                            'focus' => 'Deep Focus',
                            'beach' => 'Beach Vibes',
                            'pop' => 'Pop Hits',
                            'movie' => 'Movie Classics',
                            'folk' => 'Folk Music',
                            'travel' => 'Travelling',
                            'kids' => 'For Kids',
                            '80s' => '80s Pop',
                            'electronic' => 'Electronic',
                            'hip-hop' => 'Hip Hop',
                            'ambient' => 'Ambient',
                            'cinematic' => 'Cinematic',
                            'rock' => 'Rock',
                            'jazz' => 'Jazz',
                            'classical' => 'Classical',
                            'acoustic' => 'Acoustic',
                            'country' => 'Country',
                            'indie' => 'Indie',
                            'world' => 'World Music',
                            'cheerful' => 'Cheerful',
                            'urban' => 'Urban'
                        ];
                        
                        foreach ($related as $genre): 
                        ?>
                        <div class="col-md-4 mb-4">
                            <a href="<?php echo URLROOT; ?>/music/genre/<?php echo $genre; ?>" class="category-card-link">
                                <div class="category-card" style="background-image: url('<?php echo URLROOT; ?>/public/images/genres/<?php echo $genre; ?>.jpg')">
                                    <div class="category-overlay"></div>
                                    <div class="category-content">
                                        <h3><?php echo $genreTitles[$genre] ?? ucfirst($genre); ?></h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Audio Players Container -->
<div id="audio-players" style="display: none;"></div>

<!-- Paylaşım Popup ve Overlay Yapısı -->
<?php include_once APPROOT . '/views/music/partials/share_popup.php'; ?>

<?php require_once APPROOT . '/views/inc/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Parça satırlarına animasyon ekle
        const trackRows = document.querySelectorAll('.track-row');
        trackRows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.05}s`;
            row.classList.add('animate__fadeIn');
        });
        
        // Play All butonu için
        const playAllBtn = document.querySelector('.play-all-btn');
        if (playAllBtn) {
            playAllBtn.addEventListener('click', function() {
                this.classList.add('active');
                // Buton animasyonu
                this.querySelector('i').classList.add('fa-pulse');
                
                // İlk parçayı çal
                const trackRows = document.querySelectorAll('.track-row');
                if (trackRows.length > 0) {
                    const firstTrack = trackRows[0];
                    const trackId = firstTrack.getAttribute('data-id');
                    const trackUrl = firstTrack.getAttribute('data-url');
                    
                    // Tüm satırları normal duruma getir
                    trackRows.forEach(row => row.classList.remove('playing'));
                    // İlk satırı çalıyor olarak işaretle
                    firstTrack.classList.add('playing');
                    
                    // Parçayı çal
                    if (typeof playTrack === 'function') {
                        playTrack(trackId, trackUrl);
                    } else {
                        window.location.href = '<?php echo URLROOT; ?>/music/track/' + trackId;
                    }
                    
                    // Buton animasyonunu kaldır
                    setTimeout(() => {
                        playAllBtn.querySelector('i').classList.remove('fa-pulse');
                    }, 1000);
                }
            });
        }
        
        // Parça satırlarına tıklama işlevi
        const trackPlayButtons = document.querySelectorAll('.play-track-btn');
        trackPlayButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const trackId = this.getAttribute('data-id');
                const trackUrl = this.getAttribute('data-url');
                const trackRow = this.closest('.track-row');
                
                // Tüm satırları normal duruma getir
                document.querySelectorAll('.track-row').forEach(row => row.classList.remove('playing'));
                // Bu satırı çalıyor olarak işaretle
                trackRow.classList.add('playing');
                
                // Play/pause ikonlarını değiştir
                const playIcon = this.querySelector('.play-icon');
                const pauseIcon = this.querySelector('.pause-icon');
                
                // Tüm butonları normal duruma getir
                trackPlayButtons.forEach(btn => {
                    btn.querySelector('.play-icon').classList.remove('d-none');
                    btn.querySelector('.pause-icon').classList.add('d-none');
                });
                
                // Bu butonu çalıyor olarak işaretle
                playIcon.classList.add('d-none');
                pauseIcon.classList.remove('d-none');
                
                // Parçayı çal
                if (typeof playTrack === 'function') {
                    playTrack(trackId, trackUrl);
                } else {
                    window.location.href = '<?php echo URLROOT; ?>/music/track/' + trackId;
                }
                
                // Buton efekti
                button.classList.add('pulse-effect');
                setTimeout(() => {
                    button.classList.remove('pulse-effect');
                }, 700);
            });
        });
        
        // Favorilere ekle/çıkar
        const favoriteButtons = document.querySelectorAll('.toggle-favorite');
        favoriteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const trackId = this.getAttribute('data-id');
                const isFavorite = this.getAttribute('data-is-favorite') === 'true';
                const icon = this.querySelector('i');
                
                // Animasyon efekti
                this.classList.add('animate__animated', 'animate__heartBeat');
                
                // AJAX isteği
                fetch('<?php echo URLROOT; ?>/music/toggleFavorite/' + trackId, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Durumu tersine çevir
                        if (data.isFavorite) {
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                            this.setAttribute('data-is-favorite', 'true');
                        } else {
                            icon.classList.remove('fas');
                            icon.classList.add('far');
                            this.setAttribute('data-is-favorite', 'false');
                        }
                    }
                    
                    // Animasyonu kaldır
                    setTimeout(() => {
                        this.classList.remove('animate__animated', 'animate__heartBeat');
                    }, 1000);
                })
                .catch(error => {
                    console.error('Favoriler hatası:', error);
                    this.classList.remove('animate__animated', 'animate__heartBeat');
                });
            });
        });
    });
</script>

<style>
    /* Genre sayfası için stil değişiklikleri */
    .hero-banner {
        border-radius: 20px;
        background-size: cover;
        background-position: center;
        overflow: hidden;
        position: relative;
        height: 350px;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        animation: fadeInUp 0.8s ease-out forwards;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .hero-gradient-overlay {
        background: linear-gradient(to top, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.4) 60%, rgba(0, 0, 0, 0.1) 100%);
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1;
    }
    
    .banner-content {
        position: relative;
        z-index: 2;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        height: 100%;
    }
    
    .banner-content h2 {
        color: rgba(255, 255, 255, 0.8);
        font-size: 24px;
        margin: 0;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
    }
    
    .banner-content h1 {
        font-size: 60px;
        font-weight: 800;
        margin-bottom: 25px;
        background: linear-gradient(to right, #ffffff, #0ff7ef);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }
    
    .play-all-btn {
        width: 65px;
        height: 65px;
        background: linear-gradient(135deg, #0ff7ef, #f72aa0);
        border: none;
        box-shadow: 0 10px 25px rgba(15, 247, 239, 0.3);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .play-all-btn:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at center, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
        opacity: 0;
        transition: all 0.3s ease;
    }
    
    .play-all-btn:hover {
        transform: scale(1.08);
        box-shadow: 0 15px 30px rgba(15, 247, 239, 0.4);
    }
    
    .play-all-btn:hover:before {
        opacity: 1;
        animation: pulse-ripple 0.8s infinite;
    }
    
    .play-all-btn.active {
        transform: scale(0.95);
        box-shadow: 0 5px 15px rgba(15, 247, 239, 0.2);
    }
    
    @keyframes pulse-ripple {
        0% {
            transform: scale(1);
            opacity: 0.5;
        }
        100% {
            transform: scale(1.5);
            opacity: 0;
        }
    }
    
    /* Parça Listesi Stilleri */
    .track-list {
        margin-top: 40px;
        animation: fadeIn 0.5s ease-out forwards;
    }
    
    .tracks-header {
        color: rgba(255, 255, 255, 0.5);
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding-bottom: 12px;
    }
    
    .track-row {
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        transition: all 0.3s ease;
        border-radius: 8px;
        position: relative;
        animation: fadeIn 0.5s ease-out forwards;
    }
    
    .track-row:hover {
        background: rgba(15, 247, 239, 0.05);
        transform: translateY(-2px);
        border-bottom-color: transparent;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .track-row.playing {
        background: rgba(15, 247, 239, 0.08);
        border-bottom-color: transparent;
        border-left: 3px solid #0ff7ef;
    }
    
    .track-row.playing:after {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: linear-gradient(to bottom, #0ff7ef, #f72aa0);
        opacity: 0.8;
    }
    
    .track-play-button .btn {
        width: 40px;
        height: 40px;
        background: rgba(15, 247, 239, 0.1);
        border: none;
        color: #0ff7ef;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 0 0 0 rgba(15, 247, 239, 0.3);
    }
    
    .track-play-button .btn:hover {
        background: rgba(15, 247, 239, 0.2);
        transform: scale(1.08);
        box-shadow: 0 0 15px rgba(15, 247, 239, 0.2);
    }
    
    .pulse-effect {
        animation: pulse-btn 0.7s ease-out;
    }
    
    @keyframes pulse-btn {
        0% {
            box-shadow: 0 0 0 0 rgba(15, 247, 239, 0.7);
        }
        70% {
            box-shadow: 0 0 0 12px rgba(15, 247, 239, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(15, 247, 239, 0);
        }
    }
    
    .track-info h5 {
        font-size: 16px;
        font-weight: 600;
        transition: color 0.3s ease;
    }
    
    .track-row:hover .track-info h5 {
        color: #0ff7ef;
    }
    
    .track-row.playing .track-info h5 {
        color: #0ff7ef;
    }
    
    .toggle-favorite {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.05);
        border: none;
        color: rgba(255, 255, 255, 0.7);
    }
    
    .toggle-favorite:hover {
        background: rgba(247, 42, 160, 0.1);
        color: #f72aa0;
        transform: scale(1.1);
    }
    
    .toggle-favorite .fas {
        color: #f72aa0;
    }
    
    .dropdown-menu {
        background: rgba(10, 10, 20, 0.95);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        border-radius: 12px;
        padding: 10px 0;
        min-width: 200px;
        margin-top: 10px;
        animation: fadeInMenu 0.2s ease-out forwards;
    }
    
    @keyframes fadeInMenu {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .dropdown-item {
        color: rgba(255, 255, 255, 0.8);
        padding: 10px 16px;
        transition: all 0.3s ease;
        font-size: 14px;
    }
    
    .dropdown-item:hover {
        background: rgba(15, 247, 239, 0.05);
        color: #0ff7ef;
        padding-left: 20px;
    }
    
    .dropdown-divider {
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        margin: 6px 0;
    }
    
    /* Boş Durum Stili */
    .empty-state {
        padding: 60px 0;
        animation: fadeIn 0.5s ease-out forwards;
    }
    
    .empty-icon {
        margin-bottom: 20px;
    }
    
    .empty-icon i {
        color: rgba(15, 247, 239, 0.3);
        animation: pulse 2s infinite ease-in-out;
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 0.5;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.8;
        }
        100% {
            transform: scale(1);
            opacity: 0.5;
        }
    }
    
    .empty-state h3 {
        font-size: 24px;
        margin-bottom: 10px;
        background: linear-gradient(to right, #ffffff, #0ff7ef);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    /* Kategori Kartları */
    .suggested-categories {
        margin-top: 60px;
    }
    
    .suggested-categories h3 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 25px;
        background: linear-gradient(to right, #ffffff, #0ff7ef);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .category-card {
        height: 180px;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.4s ease;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        animation: fadeIn 0.5s ease-out forwards;
    }
    
    .category-card:hover {
        transform: translateY(-7px) scale(1.02);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
    }
    
    .category-overlay {
        background: linear-gradient(to top, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.3) 70%);
        transition: all 0.4s ease;
    }
    
    .category-card:hover .category-overlay {
        background: linear-gradient(to top, rgba(0, 0, 0, 0.9) 0%, rgba(15, 247, 239, 0.2) 100%);
    }
    
    .category-content {
        padding: 25px;
    }
    
    .category-content h3 {
        font-size: 20px;
        font-weight: 700;
        transition: all 0.3s ease;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
    }
    
    .category-card:hover .category-content h3 {
        transform: scale(1.05);
        background: linear-gradient(to right, #ffffff, #0ff7ef);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style> 