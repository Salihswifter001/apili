<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="page-container">
    <div class="container">
        <?php flash('playlist_success'); ?>
        
        <div class="playlist-header">
            <div class="playlist-details">
                <div class="back-link mb-3">
                    <a href="<?php echo URL_ROOT; ?>/music/playlists" class="btn-link">
                        <i class="fas fa-arrow-left"></i>
                        <?php echo __('back_to_playlists'); ?>
                    </a>
                </div>
                
                <div class="playlist-header-content">
                    <div class="playlist-image">
                        <div class="playlist-icon">
                            <i class="fas fa-music"></i>
                        </div>
                    </div>
                    
                    <div class="playlist-info">
                        <h1 class="playlist-title"><?php echo htmlspecialchars($data['playlist']->name); ?></h1>
                        
                        <?php if (!empty($data['playlist']->description)) : ?>
                            <p class="playlist-description"><?php echo htmlspecialchars($data['playlist']->description); ?></p>
                        <?php endif; ?>
                        
                        <div class="playlist-meta">
                            <div class="playlist-meta-item">
                                <i class="fas fa-music"></i>
                                <span><?php echo count($data['tracks']); ?> <?php echo __('tracks'); ?></span>
                            </div>
                            <div class="playlist-meta-item">
                                <i class="fas fa-clock"></i>
                                <span>
                                    <?php 
                                    $totalDuration = 0;
                                    foreach ($data['tracks'] as $track) {
                                        $totalDuration += $track->duration;
                                    }
                                    
                                    $minutes = floor($totalDuration / 60);
                                    $seconds = $totalDuration % 60;
                                    echo $minutes . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT);
                                    ?>
                                </span>
                            </div>
                            <div class="playlist-meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span><?php echo date('F j, Y', strtotime($data['playlist']->created_at)); ?></span>
                            </div>
                        </div>
                        
                        <div class="playlist-actions">
                            <?php if (!empty($data['tracks'])) : ?>
                                <button id="playAll" class="btn btn-primary with-icon">
                                    <i class="fas fa-play"></i>
                                    <span><?php echo __('play_all'); ?></span>
                                </button>
                                <button id="shuffleAll" class="btn btn-outline with-icon">
                                    <i class="fas fa-random"></i>
                                    <span><?php echo __('shuffle'); ?></span>
                                </button>
                            <?php endif; ?>
                            
                            <div class="dropdown">
                                <button class="btn btn-outline btn-icon dropdown-toggle" id="playlistOptionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end custom-dropdown" aria-labelledby="playlistOptionsDropdown">
                                    <li>
                                        <a class="dropdown-item" href="<?php echo URL_ROOT; ?>/music/editPlaylist/<?php echo $data['playlist']->id; ?>">
                                            <i class="fas fa-edit"></i> <?php echo __('edit_playlist'); ?>
                                        </a>
                                    </li>
                                    <?php if (!empty($data['tracks'])) : ?>
                                        <li>
                                            <a class="dropdown-item" href="#" id="downloadPlaylist">
                                                <i class="fas fa-download"></i> <?php echo __('download_all'); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <li>
                                        <a class="dropdown-item" href="#" id="sharePlaylist">
                                            <i class="fas fa-share-alt"></i> <?php echo __('share'); ?>
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="<?php echo URL_ROOT; ?>/music/deletePlaylist/<?php echo $data['playlist']->id; ?>">
                                            <i class="fas fa-trash"></i> <?php echo __('delete'); ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Add Tracks Button -->
        <div class="add-tracks-section">
            <button id="addTracksBtn" class="btn btn-outline with-icon">
                <i class="fas fa-plus"></i>
                <span><?php echo __('add_tracks'); ?></span>
            </button>
            
            <!-- Smart recommendations button -->
            <button id="getRecommendationsBtn" class="btn btn-outline with-icon" data-bs-toggle="modal" data-bs-target="#recommendationsModal">
                <i class="fas fa-magic"></i>
                <span><?php echo __('smart_recommendations'); ?></span>
            </button>
        </div>
        
        <!-- Tracks List -->
        <div class="tracks-container mt-4">
            <?php if (empty($data['tracks'])) : ?>
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-music"></i></div>
                    <h3><?php echo __('no_tracks_playlist'); ?></h3>
                    <p><?php echo __('start_adding_tracks'); ?></p>
                    <button id="emptyAddTracksBtn" class="btn btn-primary">
                        <?php echo __('add_tracks'); ?>
                    </button>
                </div>
            <?php else : ?>
                <div class="tracks-list-header">
                    <div class="track-header-item track-number">#</div>
                    <div class="track-header-item track-info"><?php echo __('title_column'); ?></div>
                    <div class="track-header-item track-genre"><?php echo __('genre_column'); ?></div>
                    <div class="track-header-item track-duration"><?php echo __('duration_column'); ?></div>
                    <div class="track-header-item track-added"><?php echo __('date_added_column'); ?></div>
                    <div class="track-header-item track-actions"></div>
                </div>
                
                <div class="tracks-list">
                    <?php foreach ($data['tracks'] as $index => $track) : ?>
                        <div class="track-item" data-track-id="<?php echo $track->id; ?>" data-audio-url="<?php echo $track->audio_url; ?>">
                            <div class="track-cell track-number">
                                <span class="track-index"><?php echo $index + 1; ?></span>
                                <button class="play-track-btn">
                                    <i class="fas fa-play"></i>
                                </button>
                            </div>
                            <div class="track-cell track-info">
                                <div class="track-art">
                                    <img src="<?php echo URL_ROOT; ?>/public/img/track-art/<?php echo $track->genre; ?>.jpg" alt="<?php echo $track->title; ?>">
                                </div>
                                <div class="track-details">
                                    <div class="track-title"><?php echo htmlspecialchars($track->title); ?></div>
                                    <div class="track-params">
                                        <span><?php echo $track->bpm; ?> BPM</span>
                                        <span><?php echo $track->key; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="track-cell track-genre"><?php echo ucfirst($track->genre); ?></div>
                            <div class="track-cell track-duration"><?php echo floor($track->duration / 60) . ':' . str_pad($track->duration % 60, 2, '0', STR_PAD_LEFT); ?></div>
                            <div class="track-cell track-added"><?php echo date('M j, Y', strtotime($track->added_at)); ?></div>
                            <div class="track-cell track-actions">
                                <div class="dropdown">
                                    <button class="btn-icon dropdown-toggle" id="trackDropdown<?php echo $track->id; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end custom-dropdown" aria-labelledby="trackDropdown<?php echo $track->id; ?>">
                                        <li>
                                            <a class="dropdown-item" href="<?php echo URL_ROOT; ?>/music/track/<?php echo $track->id; ?>">
                                                <i class="fas fa-info-circle"></i> <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Detaylar' : 'Details'; ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item toggle-favorite" href="<?php echo URL_ROOT; ?>/music/toggleFavorite/<?php echo $track->id; ?>" data-track-id="<?php echo $track->id; ?>">
                                                <i class="fas fa-heart"></i> <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Favorilere Ekle' : 'Add to Favorites'; ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="<?php echo URL_ROOT; ?>/music/download/<?php echo $track->id; ?>">
                                                <i class="fas fa-download"></i> <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'İndir' : 'Download'; ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#shareModal" data-track-id="<?php echo $track->id; ?>">
                                                <i class="fas fa-share-alt"></i> <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Paylaş' : 'Share'; ?>
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="<?php echo URL_ROOT; ?>/music/removeFromPlaylist" method="post" class="remove-track-form">
                                                <input type="hidden" name="track_id" value="<?php echo $track->id; ?>">
                                                <input type="hidden" name="playlist_id" value="<?php echo $data['playlist']->id; ?>">
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-trash"></i> <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Listeden Kaldır' : 'Remove from Playlist'; ?>
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Tracks Modal -->
<div class="modal fade" id="addTracksModal" tabindex="-1" aria-labelledby="addTracksModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTracksModalLabel">
                    <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Parça Ekle' : 'Add Tracks'; ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Search filters -->
                <div class="search-filters mb-4">
                    <div class="input-with-icon">
                        <i class="fas fa-search"></i>
                        <input type="text" id="trackSearchInput" class="form-control" placeholder="<?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Parça ara...' : 'Search tracks...'; ?>">
                    </div>
                    
                    <div class="filters mt-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="genreFilter"><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Tür' : 'Genre'; ?></label>
                                <select id="genreFilter" class="form-control">
                                    <option value=""><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Tüm Türler' : 'All Genres'; ?></option>
                                    <option value="electronic">Electronic</option>
                                    <option value="pop">Pop</option>
                                    <option value="rock">Rock</option>
                                    <option value="ambient">Ambient</option>
                                    <option value="jazz">Jazz</option>
                                    <option value="classical">Classical</option>
                                    <option value="hip-hop">Hip Hop</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="sortBy"><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Sırala' : 'Sort By'; ?></label>
                                <select id="sortBy" class="form-control">
                                    <option value="newest"><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'En Yeni' : 'Newest'; ?></option>
                                    <option value="oldest"><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'En Eski' : 'Oldest'; ?></option>
                                    <option value="title"><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Başlık' : 'Title'; ?></option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="filterType"><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Filtre' : 'Filter'; ?></label>
                                <select id="filterType" class="form-control">
                                    <option value="all"><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Tüm Parçalar' : 'All Tracks'; ?></option>
                                    <option value="favorites"><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Favoriler' : 'Favorites'; ?></option>
                                    <option value="recent"><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Son Oluşturulanlar' : 'Recently Generated'; ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Available tracks list (will be populated via AJAX) -->
                <div class="available-tracks-container">
                    <div class="loading-indicator text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2"><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Parçalar yükleniyor...' : 'Loading tracks...'; ?></p>
                    </div>
                    
                    <div id="availableTracksList" class="available-tracks-list d-none">
                        <!-- Tracks will be populated here -->
                    </div>
                    
                    <div id="noTracksFound" class="text-center py-5 d-none">
                        <i class="fas fa-search fa-3x mb-3 text-muted"></i>
                        <p><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Parça bulunamadı.' : 'No tracks found.'; ?></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-bs-dismiss="modal">
                    <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'İptal' : 'Cancel'; ?>
                </button>
                <button type="button" id="addSelectedTracksBtn" class="btn btn-primary">
                    <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Seçili Parçaları Ekle' : 'Add Selected Tracks'; ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Smart Recommendations Modal -->
<div class="modal fade" id="recommendationsModal" tabindex="-1" aria-labelledby="recommendationsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recommendationsModalLabel">
                    <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Akıllı Öneriler' : 'Smart Recommendations'; ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="recommendation-options mb-4">
                    <h6><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Öneri Tercihleri' : 'Recommendation Preferences'; ?></h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Benzerlik Kriteri' : 'Similarity Criteria'; ?></label>
                                <div class="custom-radio-group">
                                    <div class="custom-radio">
                                        <input type="radio" name="similarityType" id="similarByGenre" value="genre" checked>
                                        <label for="similarByGenre">
                                            <i class="fas fa-guitar"></i>
                                            <span><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Tür' : 'Genre'; ?></span>
                                        </label>
                                    </div>
                                    <div class="custom-radio">
                                        <input type="radio" name="similarityType" id="similarByMood" value="mood">
                                        <label for="similarByMood">
                                            <i class="fas fa-smile"></i>
                                            <span><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Ruh Hali' : 'Mood'; ?></span>
                                        </label>
                                    </div>
                                    <div class="custom-radio">
                                        <input type="radio" name="similarityType" id="similarByTempo" value="tempo">
                                        <label for="similarByTempo">
                                            <i class="fas fa-tachometer-alt"></i>
                                            <span><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Tempo' : 'Tempo'; ?></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Öneri Sayısı' : 'Number of Recommendations'; ?></label>
                                <select id="recommendationCount" class="form-control">
                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                </select>
                            </div>
                            
                            <div class="form-group mt-3">
                                <div class="custom-checkbox">
                                    <input type="checkbox" id="excludeExistingTracks" checked>
                                    <label for="excludeExistingTracks">
                                        <span><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Mevcut parçaları hariç tut' : 'Exclude tracks already in playlist'; ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <button id="generateRecommendationsBtn" class="btn btn-primary">
                            <i class="fas fa-magic me-2"></i>
                            <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Öneriler Oluştur' : 'Generate Recommendations'; ?>
                        </button>
                    </div>
                </div>
                
                <div id="recommendationsContainer" class="d-none">
                    <h6><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Önerilen Parçalar' : 'Recommended Tracks'; ?></h6>
                    
                    <div id="recommendationsTracksList" class="recommendations-tracks-list">
                        <!-- Recommendations will be loaded here -->
                    </div>
                </div>
                
                <div id="recommendationsLoading" class="text-center py-5 d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2"><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Öneriler oluşturuluyor...' : 'Generating recommendations...'; ?></p>
                </div>
                
                <div id="noRecommendationsFound" class="text-center py-5 d-none">
                    <i class="fas fa-exclamation-circle fa-3x mb-3 text-muted"></i>
                    <p><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Öneri bulunamadı.' : 'No recommendations found.'; ?></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-bs-dismiss="modal">
                    <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Kapat' : 'Close'; ?>
                </button>
                <button type="button" id="addRecommendedTracksBtn" class="btn btn-primary" disabled>
                    <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Seçili Önerileri Ekle' : 'Add Selected Recommendations'; ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareModalLabel">
                    <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Paylaş' : 'Share'; ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="share-options">
                    <p><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Çalma listesini paylaşın:' : 'Share this playlist:'; ?></p>
                    
                    <div class="share-url-container mb-3">
                        <label for="shareUrl"><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Paylaşım Bağlantısı' : 'Share Link'; ?></label>
                        <div class="input-group">
                            <input type="text" id="shareUrl" class="form-control" value="<?php echo URL_ROOT; ?>/music/shared/playlist/<?php echo $data['playlist']->id; ?>" readonly>
                            <button class="btn btn-outline-secondary copy-link-btn" type="button">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="social-share-buttons">
                        <button class="btn btn-social btn-facebook">
                            <i class="fab fa-facebook-f"></i>
                            <span>Facebook</span>
                        </button>
                        <button class="btn btn-social btn-twitter">
                            <i class="fab fa-twitter"></i>
                            <span>Twitter</span>
                        </button>
                        <button class="btn btn-social btn-whatsapp">
                            <i class="fab fa-whatsapp"></i>
                            <span>WhatsApp</span>
                        </button>
                        <button class="btn btn-social btn-email">
                            <i class="fas fa-envelope"></i>
                            <span>Email</span>
                        </button>
                    </div>
                    
                    <div class="embed-options mt-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="embedOption">
                            <label class="form-check-label" for="embedOption">
                                <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Yerleştirme Kodu Oluştur' : 'Generate Embed Code'; ?>
                            </label>
                        </div>
                        
                        <div id="embedCodeContainer" class="mt-3 d-none">
                            <label for="embedCode"><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Yerleştirme Kodu' : 'Embed Code'; ?></label>
                            <textarea id="embedCode" class="form-control" rows="4" readonly><iframe src="<?php echo URL_ROOT; ?>/music/embed/playlist/<?php echo $data['playlist']->id; ?>" width="100%" height="450" frameborder="0"></iframe></textarea>
                            
                            <div class="embed-options-controls mt-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="autoplayOption" checked>
                                    <label class="form-check-label" for="autoplayOption">
                                        <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Otomatik Oynat' : 'Autoplay'; ?>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="showPlaylistInfo" checked>
                                    <label class="form-check-label" for="showPlaylistInfo">
                                        <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Çalma Listesi Bilgisini Göster' : 'Show Playlist Info'; ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-bs-dismiss="modal">
                    <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Kapat' : 'Close'; ?>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Playlist Header Styles */
.playlist-header {
    margin-bottom: 2rem;
}

.btn-link {
    color: var(--text-secondary);
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-link:hover {
    color: var(--accent-primary);
}

.playlist-header-content {
    display: flex;
    gap: 2rem;
    align-items: flex-start;
}

.playlist-image {
    width: 200px;
    height: 200px;
    background-color: var(--bg-tertiary);
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: var(--box-shadow);
    position: relative;
}

.playlist-icon {
    font-size: 5rem;
    color: var(--accent-primary);
    text-shadow: 0 0 15px var(--glow-primary);
}

.playlist-info {
    flex: 1;
}

.playlist-title {
    font-size: 2.5rem;
    margin-bottom: 0.75rem;
}

.playlist-description {
    color: var(--text-secondary);
    margin-bottom: 1.25rem;
    max-width: 700px;
}

.playlist-meta {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.playlist-meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-muted);
}

.playlist-meta-item i {
    color: var(--accent-primary);
}

.playlist-actions {
    display: flex;
    gap: 1rem;
}

/* Add Tracks Section */
.add-tracks-section {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

/* Tracks List Styles */
.tracks-container {
    background-color: var(--card-bg);
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid var(--border-color);
}

.tracks-list-header {
    display: flex;
    align-items: center;
    padding: 1rem 1.5rem;
    background-color: rgba(255, 255, 255, 0.05);
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
    border-bottom: 1px solid var(--border-color);
}

.track-header-item {
    display: flex;
    align-items: center;
}

.track-number {
    width: 50px;
    justify-content: center;
}

.track-info {
    flex: 2;
}

.track-genre {
    flex: 1;
}

.track-duration {
    width: 80px;
    text-align: center;
}

.track-added {
    flex: 1;
}

.track-actions {
    width: 50px;
    display: flex;
    justify-content: center;
}

.tracks-list {
    max-height: 600px;
    overflow-y: auto;
}

.track-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.track-item:hover {
    background-color: rgba(255, 255, 255, 0.05);
}

.track-item:last-child {
    border-bottom: none;
}

.track-cell {
    display: flex;
    align-items: center;
}

.track-number {
    width: 50px;
    justify-content: center;
    position: relative;
}

.track-index {
    position: absolute;
    transition: all 0.3s ease;
}

.play-track-btn {
    position: absolute;
    opacity: 0;
    background: none;
    border: none;
    color: var(--accent-primary);
    transition: all 0.3s ease;
    cursor: pointer;
}

.track-item:hover .track-index {
    opacity: 0;
}

.track-item:hover .play-track-btn {
    opacity: 1;
}

.track-info {
    flex: 2;
    gap: 1rem;
}

.track-art {
    width: 40px;
    height: 40px;
    border-radius: 4px;
    overflow: hidden;
}

.track-art img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.track-details {
    display: flex;
    flex-direction: column;
}

.track-params {
    display: flex;
    gap: 1rem;
    font-size: 0.8rem;
    color: var(--text-muted);
}

.track-genre {
    flex: 1;
}

.track-duration {
    width: 80px;
    justify-content: center;
}

.track-added {
    flex: 1;
    color: var(--text-muted);
    font-size: 0.9rem;
}

/* Dropdown Styles */
.custom-dropdown {
    background-color: var(--bg-tertiary);
    border: 1px solid var(--border-color);
    box-shadow: var(--box-shadow);
    border-radius: 8px;
    min-width: 180px;
    padding: 0.5rem;
}

.custom-dropdown .dropdown-item {
    color: var(--text-secondary);
    padding: 0.75rem 1rem;
    border-radius: 4px;
}

.custom-dropdown .dropdown-item:hover {
    background-color: rgba(255, 255, 255, 0.05);
    color: var(--accent-primary);
}

.custom-dropdown .dropdown-divider {
    border-top: 1px solid var(--border-color);
    margin: 0.25rem 0;
}

.custom-dropdown .text-danger:hover {
    color: var(--danger) !important;
}

/* Modal Styles */
.modal-content {
    background-color: var(--bg-tertiary);
    border: 1px solid var(--border-color);
    border-radius: 12px;
}

.modal-header {
    border-bottom: 1px solid var(--border-color);
    padding: 1.5rem;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    border-top: 1px solid var(--border-color);
    padding: 1.5rem;
}

.btn-close {
    color: var(--text-secondary);
    text-shadow: none;
    opacity: 1;
}

/* Custom Radio Group */
.custom-radio-group {
    display: flex;
    gap: 1rem;
    margin-top: 0.5rem;
}

.custom-radio {
    position: relative;
    flex: 1;
}

.custom-radio input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.custom-radio label {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1rem;
    border-radius: 8px;
    background-color: rgba(255, 255, 255, 0.05);
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
}

.custom-radio label i {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    color: var(--text-secondary);
}

.custom-radio input[type="radio"]:checked + label {
    background-color: rgba(15, 247, 239, 0.1);
    border: 1px solid var(--accent-primary);
}

.custom-radio input[type="radio"]:checked + label i {
    color: var(--accent-primary);
}

/* Share Options */
.social-share-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-top: 1rem;
}

.btn-social {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border-radius: 6px;
    color: white;
    border: none;
    transition: all 0.3s ease;
    flex: 1;
    min-width: 120px;
}

.btn-facebook {
    background-color: #1877F2;
}

.btn-twitter {
    background-color: #1DA1F2;
}

.btn-whatsapp {
    background-color: #25D366;
}

.btn-email {
    background-color: #D44638;
}

.btn-social:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

/* Custom Checkbox */
.custom-checkbox {
    position: relative;
    display: flex;
    align-items: flex-start;
}

.custom-checkbox input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.custom-checkbox label {
    display: flex;
    align-items: center;
    cursor: pointer;
    padding-left: 30px;
    position: relative;
}

.custom-checkbox label:before {
    content: '';
    position: absolute;
    left: 0;
    top: 2px;
    width: 20px;
    height: 20px;
    border: 2px solid var(--border-color);
    border-radius: 4px;
    transition: all 0.3s ease;
}

.custom-checkbox input[type="checkbox"]:checked + label:before {
    background-color: var(--accent-primary);
    border-color: var(--accent-primary);
}

.custom-checkbox input[type="checkbox"]:checked + label:after {
    content: '\f00c';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    left: 4px;
    top: 2px;
    color: var(--bg-primary);
    font-size: 0.75rem;
}

/* Recommendations Tracks List */
.recommendations-tracks-list {
    max-height: 400px;
    overflow-y: auto;
}

.recommendation-track-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    background-color: rgba(255, 255, 255, 0.05);
    transition: all 0.3s ease;
}

.recommendation-track-item:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.recommendation-checkbox {
    margin-right: 1rem;
}

.recommendation-track-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

.recommendation-track-art {
    width: 40px;
    height: 40px;
    border-radius: 4px;
    overflow: hidden;
}

.recommendation-track-art img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.recommendation-track-details {
    flex: 1;
}

.recommendation-track-title {
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.recommendation-track-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.8rem;
    color: var(--text-muted);
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-icon {
    font-size: 3rem;
    color: var(--text-muted);
    margin-bottom: 1rem;
}

.empty-state h3 {
    font-size: 1.5rem;
    margin-bottom: 0.75rem;
}

.empty-state p {
    color: var(--text-secondary);
    margin-bottom: 1.5rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

@media (max-width: 992px) {
    .playlist-header-content {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .playlist-meta {
        justify-content: center;
    }
    
    .playlist-actions {
        justify-content: center;
    }
    
    .track-genre, .track-added {
        display: none;
    }
}

@media (max-width: 768px) {
    .tracks-list-header {
        padding: 1rem;
    }
    
    .track-item {
        padding: 0.75rem 1rem;
    }
    
    .track-duration {
        display: none;
    }
    
    .add-tracks-section {
        flex-direction: column;
    }
    
    .custom-radio-group {
        flex-direction: column;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add Tracks Modal
    const addTracksBtn = document.getElementById('addTracksBtn');
    const emptyAddTracksBtn = document.getElementById('emptyAddTracksBtn');
    
    if (addTracksBtn) {
        addTracksBtn.addEventListener('click', function() {
            const addTracksModal = new bootstrap.Modal(document.getElementById('addTracksModal'));
            addTracksModal.show();
            
            // Simulate loading tracks (in a real app, this would be an AJAX call)
            setTimeout(() => {
                document.querySelector('.loading-indicator').classList.add('d-none');
                document.getElementById('availableTracksList').classList.remove('d-none');
                
                // Populate with mock data (would be replaced with real data from AJAX)
                const tracksList = document.getElementById('availableTracksList');
                tracksList.innerHTML = '';
                
                // Example tracks
                const mockTracks = [
                    { id: 101, title: 'Electronic Dreams', genre: 'electronic', duration: '3:45' },
                    { id: 102, title: 'Ambient Journey', genre: 'ambient', duration: '4:20' },
                    { id: 103, title: 'Future Pop', genre: 'pop', duration: '2:55' },
                    { id: 104, title: 'Digital Horizon', genre: 'electronic', duration: '3:10' },
                    { id: 105, title: 'Neon City', genre: 'synthwave', duration: '4:05' }
                ];
                
                mockTracks.forEach(track => {
                    const trackItem = document.createElement('div');
                    trackItem.className = 'recommendation-track-item';
                    trackItem.innerHTML = `
                        <div class="recommendation-checkbox">
                            <input type="checkbox" id="track-${track.id}" class="track-checkbox" data-track-id="${track.id}">
                            <label for="track-${track.id}"></label>
                        </div>
                        <div class="recommendation-track-info">
                            <div class="recommendation-track-art">
                                <img src="${URL_ROOT}/public/img/track-art/${track.genre}.jpg" alt="${track.title}">
                            </div>
                            <div class="recommendation-track-details">
                                <div class="recommendation-track-title">${track.title}</div>
                                <div class="recommendation-track-meta">
                                    <span>${track.genre}</span>
                                    <span>${track.duration}</span>
                                </div>
                            </div>
                        </div>
                    `;
                    tracksList.appendChild(trackItem);
                });
            }, 1500);
        });
    }
    
    if (emptyAddTracksBtn) {
        emptyAddTracksBtn.addEventListener('click', function() {
            addTracksBtn.click();
        });
    }
    
    // Smart Recommendations
    const generateRecommendationsBtn = document.getElementById('generateRecommendationsBtn');
    
    if (generateRecommendationsBtn) {
        generateRecommendationsBtn.addEventListener('click', function() {
            document.getElementById('recommendationsLoading').classList.remove('d-none');
            document.getElementById('recommendationsContainer').classList.add('d-none');
            document.getElementById('noRecommendationsFound').classList.add('d-none');
            
            // Simulate generating recommendations
            setTimeout(() => {
                document.getElementById('recommendationsLoading').classList.add('d-none');
                document.getElementById('recommendationsContainer').classList.remove('d-none');
                document.getElementById('addRecommendedTracksBtn').disabled = false;
                
                // Populate with mock data
                const recommendationsList = document.getElementById('recommendationsTracksList');
                recommendationsList.innerHTML = '';
                
                // Example recommendations
                const mockRecommendations = [
                    { id: 201, title: 'Cyber Dreams', genre: 'electronic', match: '95%' },
                    { id: 202, title: 'Night Drive', genre: 'synthwave', match: '92%' },
                    { id: 203, title: 'Digital Soul', genre: 'electronic', match: '88%' },
                    { id: 204, title: 'Neon Lights', genre: 'synthwave', match: '85%' },
                    { id: 205, title: 'Future Memories', genre: 'ambient', match: '82%' }
                ];
                
                mockRecommendations.forEach(track => {
                    const trackItem = document.createElement('div');
                    trackItem.className = 'recommendation-track-item';
                    trackItem.innerHTML = `
                        <div class="recommendation-checkbox">
                            <input type="checkbox" id="rec-track-${track.id}" class="track-checkbox" data-track-id="${track.id}">
                            <label for="rec-track-${track.id}"></label>
                        </div>
                        <div class="recommendation-track-info">
                            <div class="recommendation-track-art">
                                <img src="${URL_ROOT}/public/img/track-art/${track.genre}.jpg" alt="${track.title}">
                            </div>
                            <div class="recommendation-track-details">
                                <div class="recommendation-track-title">${track.title}</div>
                                <div class="recommendation-track-meta">
                                    <span>${track.genre}</span>
                                    <span>Match: ${track.match}</span>
                                </div>
                            </div>
                        </div>
                    `;
                    recommendationsList.appendChild(trackItem);
                });
            }, 2000);
        });
    }
    
    // Embed Code Toggle
    const embedOption = document.getElementById('embedOption');
    const embedCodeContainer = document.getElementById('embedCodeContainer');
    
    if (embedOption && embedCodeContainer) {
        embedOption.addEventListener('change', function() {
            if (this.checked) {
                embedCodeContainer.classList.remove('d-none');
            } else {
                embedCodeContainer.classList.add('d-none');
            }
        });
    }
    
    // Copy Link Button
    const copyLinkBtn = document.querySelector('.copy-link-btn');
    
    if (copyLinkBtn) {
        copyLinkBtn.addEventListener('click', function() {
            const shareUrl = document.getElementById('shareUrl');
            shareUrl.select();
            document.execCommand('copy');
            
            // Show feedback
            this.innerHTML = '<i class="fas fa-check"></i>';
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-copy"></i>';
            }, 2000);
        });
    }
    
    // Play All Button
    const playAllBtn = document.getElementById('playAll');
    
    if (playAllBtn) {
        playAllBtn.addEventListener('click', function() {
            alert('Play all functionality would be implemented here');
            // In a real implementation, this would trigger the audio player to start playing the playlist
        });
    }
    
    // Share Playlist
    const sharePlaylistBtn = document.getElementById('sharePlaylist');
    
    if (sharePlaylistBtn) {
        sharePlaylistBtn.addEventListener('click', function() {
            const shareModal = new bootstrap.Modal(document.getElementById('shareModal'));
            shareModal.show();
        });
    }
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>