<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="page-container">
    <div class="library-container">
        <!-- Kütüphane Başlığı ve Filtreler -->
        <div class="library-header">
            <div class="library-title-section">
                <h1 class="glitch-text" data-text="<?php echo __('your_music_library'); ?>"><?php echo __('your_music_library'); ?></h1>
                <p><?php echo __('library_description', 'Tüm müzik koleksiyonunuzu keşfedin, yönetin ve dinleyin.'); ?></p>
            </div>
            
            <div class="library-actions">
                <a href="<?php echo URL_ROOT; ?>/music/generate" class="btn btn-primary with-icon">
                    <i class="fas fa-plus"></i>
                    <span><?php echo __('generate_new'); ?></span>
                </a>
                
                <a href="<?php echo URL_ROOT; ?>/music/playlists" class="btn btn-outline with-icon">
                    <i class="fas fa-list"></i>
                    <span><?php echo __('playlists'); ?></span>
                </a>
            </div>
        </div>
        
        <!-- Kütüphane Filtreleri ve Arama -->
        <div class="library-filters">
            <div class="search-bar">
                <form action="<?php echo URL_ROOT; ?>/music/search" method="GET" class="input-with-icon">
                    <i class="fas fa-search"></i>
                    <input type="text" name="q" placeholder="<?php echo __('search_library'); ?>" value="<?php echo isset($data['search_query']) ? htmlspecialchars($data['search_query']) : ''; ?>">
                </form>
            </div>
            
            <div class="filter-options">
                <div class="filter-group">
                    <label for="filterGenre"><?php echo __('filter_by_genre'); ?></label>
                    <select id="filterGenre" class="filter-select">
                        <option value="all"><?php echo __('all_genres'); ?></option>
                        <?php foreach($data['genres'] as $genreKey => $genreName): ?>
                            <option value="<?php echo $genreKey; ?>" <?php echo (isset($data['selectedGenre']) && $data['selectedGenre'] === $genreKey) ? 'selected' : ''; ?>>
                                <?php echo $genreName; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="sortBy"><?php echo __('sort_by'); ?></label>
                    <select id="sortBy" class="filter-select">
                        <option value="newest" <?php echo (isset($data['sortBy']) && $data['sortBy'] === 'newest') ? 'selected' : ''; ?>><?php echo __('newest_first'); ?></option>
                        <option value="oldest" <?php echo (isset($data['sortBy']) && $data['sortBy'] === 'oldest') ? 'selected' : ''; ?>><?php echo __('oldest_first'); ?></option>
                        <option value="title_asc" <?php echo (isset($data['sortBy']) && $data['sortBy'] === 'title_asc') ? 'selected' : ''; ?>><?php echo __('title_a_z'); ?></option>
                        <option value="title_desc" <?php echo (isset($data['sortBy']) && $data['sortBy'] === 'title_desc') ? 'selected' : ''; ?>><?php echo __('title_z_a'); ?></option>
                        <option value="duration_asc" <?php echo (isset($data['sortBy']) && $data['sortBy'] === 'duration_asc') ? 'selected' : ''; ?>><?php echo __('duration_shortest'); ?></option>
                        <option value="duration_desc" <?php echo (isset($data['sortBy']) && $data['sortBy'] === 'duration_desc') ? 'selected' : ''; ?>><?php echo __('duration_longest'); ?></option>
                    </select>
                </div>
                
                <div class="filter-group view-mode">
                    <span><?php echo __('view'); ?>:</span>
                    <button class="view-btn grid-view active" data-view="grid">
                        <i class="fas fa-th-large"></i>
                    </button>
                    <button class="view-btn list-view" data-view="list">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Ana İçerik - Müzik Koleksiyonu -->
        <div class="library-visualizer">
            <div class="visualizer-container">
                <canvas id="libraryVisualizer"></canvas>
                <div class="visualizer-overlay"></div>
            </div>
        </div>
        
        <!-- Kolon Başlıkları -->
        <div class="track-columns-header">
            <div class="track-column-item title"><?php echo __('track_name'); ?></div>
            <div class="track-column-item genre"><?php echo __('genre'); ?></div>
            <div class="track-column-item duration"><?php echo __('duration'); ?></div>
            <div class="track-column-item key"><?php echo __('key'); ?></div>
            <div class="track-column-item date"><?php echo __('created_at'); ?></div>
        </div>
        
        <!-- Müzik Koleksiyonu - Grid Görünümü (Varsayılan) -->
        <div class="library-content view-grid">
            <?php if(empty($data['tracks'])): ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-music"></i>
                    </div>
                    <h3><?php echo __('no_tracks_yet'); ?></h3>
                    <p><?php echo __('library_empty_message'); ?></p>
                    <a href="<?php echo URL_ROOT; ?>/music/generate" class="btn btn-glow"><?php echo __('generate_first_track'); ?></a>
                </div>
            <?php else: ?>
                <div class="tracks-grid">
                    <?php foreach($data['tracks'] as $track): ?>
                        <div class="track-card" data-id="<?php echo $track->id; ?>" data-genre="<?php echo $track->genre; ?>">
                            <div class="track-art">
                                <img src="<?php echo URL_ROOT; ?>/public/img/track-art/<?php echo $track->genre; ?>.jpg" alt="<?php echo htmlspecialchars($track->title); ?>">
                                <div class="track-overlay">
                                    <a href="<?php echo URL_ROOT; ?>/music/track/<?php echo $track->id; ?>" class="play-btn">
                                        <i class="fas fa-play"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="track-info">
                                <div class="track-info-column title"><?php echo htmlspecialchars($track->title); ?></div>
                                <div class="track-info-column genre"><?php echo ucfirst($track->genre); ?></div>
                                <div class="track-info-column duration"><?php echo floor($track->duration / 60) . ':' . str_pad($track->duration % 60, 2, '0', STR_PAD_LEFT); ?></div>
                                <div class="track-info-column key"><?php echo $track->key; ?></div>
                                <div class="track-info-column date"><?php echo date('d.m.Y', strtotime($track->created_at)); ?></div>
                            </div>
                            <div class="track-actions">
                                <a href="<?php echo URL_ROOT; ?>/music/track/<?php echo $track->id; ?>" class="btn btn-sm"><?php echo __('details_btn'); ?></a>
                                <a href="javascript:void(0);" class="btn-icon add-to-playlist" data-id="<?php echo $track->id; ?>" title="<?php echo __('add_to_playlist'); ?>">
                                    <i class="fas fa-plus"></i>
                                </a>
                                <a href="<?php echo URL_ROOT; ?>/music/toggleFavorite/<?php echo $track->id; ?>" class="btn-icon <?php echo $this->musicModel->isTrackFavorite($_SESSION['user_id'], $track->id) ? 'favorited' : ''; ?>" title="<?php echo $this->musicModel->isTrackFavorite($_SESSION['user_id'], $track->id) ? __('remove_from_favorites') : __('add_to_favorites'); ?>">
                                    <i class="fas fa-heart"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Müzik Koleksiyonu - Liste Görünümü -->
        <div class="library-content view-list" style="display: none;">
            <?php if(empty($data['tracks'])): ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-music"></i>
                    </div>
                    <h3><?php echo __('no_tracks_yet'); ?></h3>
                    <p><?php echo __('library_empty_message'); ?></p>
                    <a href="<?php echo URL_ROOT; ?>/music/generate" class="btn btn-glow"><?php echo __('generate_first_track'); ?></a>
                </div>
            <?php else: ?>
                <div class="tracks-table-container">
                    <table class="tracks-table">
                        <thead>
                            <tr>
                                <th class="th-play"></th>
                                <th class="th-title"><?php echo __('track_details'); ?></th>
                                <th class="th-created"><?php echo __('created_at'); ?></th>
                                <th class="th-actions"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['tracks'] as $track): ?>
                                <tr class="track-row" data-id="<?php echo $track->id; ?>" data-genre="<?php echo $track->genre; ?>">
                                    <td class="td-play">
                                        <a href="<?php echo URL_ROOT; ?>/music/track/<?php echo $track->id; ?>" class="play-btn-sm">
                                            <i class="fas fa-play"></i>
                                        </a>
                                    </td>
                                    <td class="td-title">
                                        <div class="track-list-title"><?php echo htmlspecialchars($track->title); ?></div>
                                        <div class="track-list-details">
                                            <div class="track-list-detail">
                                                <span class="detail-label"><?php echo __('genre'); ?>:</span>
                                                <span class="detail-value track-genre"><?php echo ucfirst($track->genre); ?></span>
                                            </div>
                                            <div class="track-list-detail">
                                                <span class="detail-label"><?php echo __('duration'); ?>:</span>
                                                <span class="detail-value"><?php echo floor($track->duration / 60) . ':' . str_pad($track->duration % 60, 2, '0', STR_PAD_LEFT); ?></span>
                                            </div>
                                            <div class="track-list-detail">
                                                <span class="detail-label"><?php echo __('key'); ?>:</span>
                                                <span class="detail-value track-key"><?php echo $track->key; ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="td-created"><?php echo date('d.m.Y', strtotime($track->created_at)); ?></td>
                                    <td class="td-actions">
                                        <div class="track-actions-list">
                                            <a href="<?php echo URL_ROOT; ?>/music/track/<?php echo $track->id; ?>" class="btn-icon" title="<?php echo __('details_btn'); ?>">
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                            <a href="javascript:void(0);" class="btn-icon add-to-playlist" data-id="<?php echo $track->id; ?>" title="<?php echo __('add_to_playlist'); ?>">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                            <a href="<?php echo URL_ROOT; ?>/music/toggleFavorite/<?php echo $track->id; ?>" class="btn-icon <?php echo $this->musicModel->isTrackFavorite($_SESSION['user_id'], $track->id) ? 'favorited' : ''; ?>" title="<?php echo $this->musicModel->isTrackFavorite($_SESSION['user_id'], $track->id) ? __('remove_from_favorites') : __('add_to_favorites'); ?>">
                                                <i class="fas fa-heart"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Sayfalama -->
        <?php if(!empty($data['tracks']) && $data['totalPages'] > 1): ?>
            <div class="pagination">
                <?php if($data['currentPage'] > 1): ?>
                    <a href="<?php echo URL_ROOT; ?>/music/library?page=<?php echo $data['currentPage']-1; ?><?php echo isset($data['selectedGenre']) ? '&genre='.$data['selectedGenre'] : ''; ?><?php echo isset($data['sortBy']) ? '&sort='.$data['sortBy'] : ''; ?>" class="pagination-btn">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                <?php endif; ?>
                
                <?php for($i = 1; $i <= $data['totalPages']; $i++): ?>
                    <?php if($i == $data['currentPage']): ?>
                        <a href="#" class="pagination-btn active"><?php echo $i; ?></a>
                    <?php else: ?>
                        <a href="<?php echo URL_ROOT; ?>/music/library?page=<?php echo $i; ?><?php echo isset($data['selectedGenre']) ? '&genre='.$data['selectedGenre'] : ''; ?><?php echo isset($data['sortBy']) ? '&sort='.$data['sortBy'] : ''; ?>" class="pagination-btn"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if($data['currentPage'] < $data['totalPages']): ?>
                    <a href="<?php echo URL_ROOT; ?>/music/library?page=<?php echo $data['currentPage']+1; ?><?php echo isset($data['selectedGenre']) ? '&genre='.$data['selectedGenre'] : ''; ?><?php echo isset($data['sortBy']) ? '&sort='.$data['sortBy'] : ''; ?>" class="pagination-btn">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Çalma Listesine Ekle Modal -->
<div id="playlistModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><?php echo __('add_to_playlist'); ?></h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="playlists-select">
                <?php if(empty($data['playlists'])): ?>
                    <div class="empty-playlists">
                        <p><?php echo __('no_playlists_yet'); ?></p>
                        <a href="<?php echo URL_ROOT; ?>/music/create_playlist" class="btn btn-primary"><?php echo __('create_first_playlist'); ?></a>
                    </div>
                <?php else: ?>
                    <div class="playlist-list">
                        <?php foreach($data['playlists'] as $playlist): ?>
                            <div class="playlist-option" data-id="<?php echo $playlist->id; ?>">
                                <div class="playlist-option-info">
                                    <div class="playlist-option-image">
                                        <?php if(!empty($playlist->cover_url)): ?>
                                            <img src="<?php echo $playlist->cover_url; ?>" alt="<?php echo htmlspecialchars($playlist->name); ?>">
                                        <?php else: ?>
                                            <div class="playlist-icon">
                                                <i class="fas fa-music"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="playlist-option-details">
                                        <h4><?php echo htmlspecialchars($playlist->name); ?></h4>
                                        <p><?php echo $playlist->track_count; ?> <?php echo __('tracks'); ?></p>
                                    </div>
                                </div>
                                <button class="add-to-playlist-btn btn btn-sm"><?php echo __('add'); ?></button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="playlist-actions">
                        <a href="<?php echo URL_ROOT; ?>/music/create_playlist" class="btn btn-outline"><?php echo __('create_new_playlist'); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Kütüphane görselleştirici için JavaScript -->
<script>
/**
 * Octaverum AI - Kütüphane Görselleştirici
 * Müzik kütüphanesi sayfası için özel gelişmiş animasyonlu görselleştirici
 */
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('libraryVisualizer');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    let isActive = true;
    const particles = [];
    const maxParticles = 100;
    const wavePoints = [];
    const wavePointCount = 128;
    const waveAmplitude = 30;
    const waveSpeed = 0.03;
    const genreColors = {
        'electronic': {
            color: '#00B7FF',
            rgb: [0, 183, 255]
        },
        'pop': {
            color: '#FF69B4',
            rgb: [255, 105, 180]
        },
        'rock': {
            color: '#FF3D00',
            rgb: [255, 61, 0]
        },
        'hip-hop': {
            color: '#8A2BE2',
            rgb: [138, 43, 226]
        },
        'jazz': {
            color: '#FFD700',
            rgb: [255, 215, 0]
        },
        'classical': {
            color: '#008080',
            rgb: [0, 128, 128]
        },
        'ambient': {
            color: '#ADD8E6',
            rgb: [173, 216, 230]
        },
        'folk': {
            color: '#8B4513',
            rgb: [139, 69, 19]
        },
        'metal': {
            color: '#2F4F4F',
            rgb: [47, 79, 79]
        },
        'funk': {
            color: '#FF8C00',
            rgb: [255, 140, 0]
        },
        'blues': {
            color: '#000080',
            rgb: [0, 0, 128]
        }
    };
    
    // Tüm müzik türlerini topla
    const genres = Object.keys(genreColors);
    
    // Pencere yeniden boyutlandırıldığında canvas'ı güncelle
    window.addEventListener('resize', resizeCanvas);
    
    // Canvas'ı başlat
    resizeCanvas();
    initWavePoints();
    initParticles();
    animate();
    
    // Etkileşim ekle
    canvas.addEventListener('mousemove', handleMouseMove);
    canvas.addEventListener('mouseleave', handleMouseLeave);
    
    function resizeCanvas() {
        canvas.width = canvas.parentElement.offsetWidth;
        canvas.height = canvas.parentElement.offsetHeight;
        
        // Dalga noktalarını yeniden başlat
        initWavePoints();
    }
    
    function initWavePoints() {
        while(wavePoints.length > 0) wavePoints.pop();
        
        const width = canvas.width;
        const step = width / (wavePointCount - 1);
        
        for (let i = 0; i < wavePointCount; i++) {
            wavePoints.push({
                x: i * step,
                y: canvas.height / 2,
                originalY: canvas.height / 2,
                speed: waveSpeed * (Math.random() * 0.5 + 0.8), // Biraz rastgele hız
                amplitude: waveAmplitude * (Math.random() * 0.5 + 0.8), // Biraz rastgele genlik
                phase: Math.random() * Math.PI * 2, // Rastgele başlangıç fazı
                genre: genres[Math.floor(Math.random() * genres.length)]
            });
        }
    }
    
    function initParticles() {
        while(particles.length > 0) particles.pop();
        
        for (let i = 0; i < maxParticles; i++) {
            addParticle();
        }
    }
    
    function addParticle() {
        const genre = genres[Math.floor(Math.random() * genres.length)];
        const color = genreColors[genre].color;
        const rgb = genreColors[genre].rgb;
        
        particles.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            size: Math.random() * 3 + 1,
            speedX: Math.random() * 0.5 - 0.25,
            speedY: Math.random() * 0.5 - 0.25,
            color: color,
            rgb: rgb,
            opacity: Math.random() * 0.5 + 0.1,
            genre: genre
        });
    }
    
    function updateWavePoints(time) {
        for (let i = 0; i < wavePoints.length; i++) {
            const point = wavePoints[i];
            // Sinüs dalgası hareketi oluştur
            point.y = point.originalY + Math.sin(time * point.speed + point.phase) * point.amplitude;
        }
    }
    
    function updateParticles() {
        for (let i = 0; i < particles.length; i++) {
            const particle = particles[i];
            
            // Parçacıkları hareket ettir
            particle.x += particle.speedX;
            particle.y += particle.speedY;
            
            // Ekran dışına çıkan parçacıkları sıfırla
            if (particle.x < 0) particle.x = canvas.width;
            if (particle.x > canvas.width) particle.x = 0;
            if (particle.y < 0) particle.y = canvas.height;
            if (particle.y > canvas.height) particle.y = 0;
        }
    }
    
    function drawWaves() {
        if (wavePoints.length < 2) return;
        
        // Her tür için bir dalga çiz
        genres.forEach((genre, genreIndex) => {
            const color = genreColors[genre].color;
            const offset = (genreIndex - genres.length / 2) * (waveAmplitude / 2);
            
            ctx.beginPath();
            ctx.moveTo(0, canvas.height / 2 + offset);
            
            // Dalga için eğri çiz
            for (let i = 0; i < wavePoints.length - 1; i++) {
                const point = wavePoints[i];
                const nextPoint = wavePoints[i + 1];
                
                // Geçerli nokta bu türe aitse dalga genliğini uygula
                const currentY = point.genre === genre ? point.y + offset : canvas.height / 2 + offset;
                const nextY = nextPoint.genre === genre ? nextPoint.y + offset : canvas.height / 2 + offset;
                
                // Bezier eğrisi ile noktaları bağla
                const xc = (point.x + nextPoint.x) / 2;
                const yc = (currentY + nextY) / 2;
                ctx.quadraticCurveTo(point.x, currentY, xc, yc);
            }
            
            // Dalganın altını canvas tabanına bağla (dolgu için)
            ctx.lineTo(canvas.width, canvas.height);
            ctx.lineTo(0, canvas.height);
            ctx.closePath();
            
            // Dalga gradienti oluştur
            const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
            gradient.addColorStop(0, `${color}30`); // Üst (daha şeffaf)
            gradient.addColorStop(0.5, `${color}15`); // Orta
            gradient.addColorStop(1, 'rgba(0, 0, 0, 0)'); // Alt (tamamen şeffaf)
            
            ctx.fillStyle = gradient;
            ctx.fill();
            
            // Dalga çizgisini çiz
            ctx.beginPath();
            ctx.moveTo(0, canvas.height / 2 + offset);
            
            for (let i = 0; i < wavePoints.length - 1; i++) {
                const point = wavePoints[i];
                const nextPoint = wavePoints[i + 1];
                
                const currentY = point.genre === genre ? point.y + offset : canvas.height / 2 + offset;
                const nextY = nextPoint.genre === genre ? nextPoint.y + offset : canvas.height / 2 + offset;
                
                const xc = (point.x + nextPoint.x) / 2;
                const yc = (currentY + nextY) / 2;
                ctx.quadraticCurveTo(point.x, currentY, xc, yc);
            }
            
            ctx.strokeStyle = `${color}80`; // Yarı şeffaf çizgi
            ctx.lineWidth = 1.5;
            ctx.stroke();
        });
    }
    
    function drawParticles() {
        particles.forEach(particle => {
            ctx.beginPath();
            ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(${particle.rgb[0]}, ${particle.rgb[1]}, ${particle.rgb[2]}, ${particle.opacity})`;
            ctx.fill();
            
            // Parlama efekti ekle
            ctx.beginPath();
            ctx.arc(particle.x, particle.y, particle.size * 2, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(${particle.rgb[0]}, ${particle.rgb[1]}, ${particle.rgb[2]}, ${particle.opacity * 0.2})`;
            ctx.fill();
        });
    }
    
    function drawVisualization(time) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        // Arka plan gradienti
        const gradient = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
        gradient.addColorStop(0, 'rgba(0, 20, 40, 0.1)');
        gradient.addColorStop(1, 'rgba(0, 5, 15, 0.2)');
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        // Dalga ve parçacıkları güncelle
        updateWavePoints(time);
        updateParticles();
        
        // Dalga ve parçacıkları çiz
        drawWaves();
        drawParticles();
    }
    
    function animate() {
        if (!isActive) return;
        
        const time = Date.now() * 0.001; // Saniye cinsinden zaman
        drawVisualization(time);
        
        // Animasyonu devam ettir
        requestAnimationFrame(animate);
    }
    
    function handleMouseMove(e) {
        // Fare pozisyonunu canvas koordinatlarına çevir
        const rect = canvas.getBoundingClientRect();
        const mouseX = e.clientX - rect.left;
        const mouseY = e.clientY - rect.top;
        
        // Fare yakınındaki dalga noktalarını etkile
        for (let i = 0; i < wavePoints.length; i++) {
            const point = wavePoints[i];
            const distance = Math.sqrt(Math.pow(mouseX - point.x, 2) + Math.pow(mouseY - point.originalY, 2));
            
            if (distance < 100) {
                // Fare yakınında ise amplitüdü artır
                point.amplitude = waveAmplitude * 2 * (1 - distance / 100);
            } else {
                // Fare uzaktaysa normal değere dön
                point.amplitude = waveAmplitude * (Math.random() * 0.5 + 0.8);
            }
        }
        
        // Fare pozisyonuna yeni parçacıklar ekle
        for (let i = 0; i < 3; i++) {
            const genre = genres[Math.floor(Math.random() * genres.length)];
            const color = genreColors[genre].color;
            const rgb = genreColors[genre].rgb;
            
            particles.push({
                x: mouseX,
                y: mouseY,
                size: Math.random() * 4 + 2,
                speedX: (Math.random() - 0.5) * 2,
                speedY: (Math.random() - 0.5) * 2,
                color: color,
                rgb: rgb,
                opacity: Math.random() * 0.7 + 0.3,
                genre: genre,
                life: 50 // Parçacık ömrü
            });
            
            // Maksimum parçacık sayısını korumak için gerekirse en eskiyi sil
            if (particles.length > maxParticles) {
                particles.shift();
            }
        }
    }
    
    function handleMouseLeave() {
        // Fare ayrıldığında tüm noktaları normale döndür
        for (let i = 0; i < wavePoints.length; i++) {
            wavePoints[i].amplitude = waveAmplitude * (Math.random() * 0.5 + 0.8);
        }
    }
    
    // Sayfada görsel olmadığında performans için durdur, tekrar görünür olduğunda başlat
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            isActive = false;
        } else {
            isActive = true;
            animate();
        }
    });
    
    // Görünüm modunu değiştirme
    const gridViewBtn = document.querySelector('.grid-view');
    const listViewBtn = document.querySelector('.list-view');
    const gridView = document.querySelector('.view-grid');
    const listView = document.querySelector('.view-list');
    
    gridViewBtn.addEventListener('click', function() {
        gridViewBtn.classList.add('active');
        listViewBtn.classList.remove('active');
        gridView.style.display = 'block';
        listView.style.display = 'none';
        localStorage.setItem('library_view', 'grid');
    });
    
    listViewBtn.addEventListener('click', function() {
        listViewBtn.classList.add('active');
        gridViewBtn.classList.remove('active');
        listView.style.display = 'block';
        gridView.style.display = 'none';
        localStorage.setItem('library_view', 'list');
    });
    
    // Kaydedilmiş görünüm modunu yükle
    const savedView = localStorage.getItem('library_view');
    if (savedView === 'list') {
        listViewBtn.click();
    }
    
    // Filtre değişikliklerini dinle
    const genreFilter = document.getElementById('filterGenre');
    const sortFilter = document.getElementById('sortBy');
    
    genreFilter.addEventListener('change', function() {
        applyFilters();
    });
    
    sortFilter.addEventListener('change', function() {
        applyFilters();
    });
    
    function applyFilters() {
        const genreValue = genreFilter.value;
        const sortValue = sortFilter.value;
        
        // Parametre dizisini oluştur
        let params = [];
        
        // Tür filtresi ekle
        if (genreValue && genreValue !== 'all') {
            params.push(`genre=${genreValue}`);
        }
        
        // Sıralama filtresi ekle
        if (sortValue) {
            params.push(`sort=${sortValue}`);
        }
        
        // Sorgu stringini oluştur
        const queryString = params.length > 0 ? `?${params.join('&')}` : '';
        
        // Sayfayı yönlendir
        window.location.href = `${document.querySelector('meta[name="urlroot"]').content}/music/library${queryString}`;
    }
    
    // Çalma listesine ekle modal kontrolü
    const modal = document.getElementById('playlistModal');
    const closeBtn = modal.querySelector('.close');
    const addToPlaylistBtns = document.querySelectorAll('.add-to-playlist');
    let currentTrackId = null;
    
    addToPlaylistBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            currentTrackId = this.dataset.id;
            modal.style.display = 'block';
        });
    });
    
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });
    
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
    
    // Çalma listesine ekle işlemi
    const addToPlaylistBtnsInModal = document.querySelectorAll('.add-to-playlist-btn');
    
    addToPlaylistBtnsInModal.forEach(btn => {
        btn.addEventListener('click', function() {
            const playlistId = this.closest('.playlist-option').dataset.id;
            
            // API isteği gönderme
            fetch(`${document.querySelector('meta[name="urlroot"]').content}/music/addToPlaylist`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    track_id: currentTrackId,
                    playlist_id: playlistId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Başarılı mesajı göster
                    btn.textContent = '✓';
                    btn.classList.add('success');
                    
                    setTimeout(() => {
                        modal.style.display = 'none';
                        // Sayfa yenilenmeden, butonları sıfırla
                        btn.textContent = document.querySelector('meta[name="add_text"]').content || 'Ekle';
                        btn.classList.remove('success');
                    }, 1500);
                } else {
                    alert(data.message || 'Bir hata oluştu');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('İşlem sırasında bir hata oluştu');
            });
        });
    });
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 