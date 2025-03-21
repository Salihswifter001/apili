<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="welcome-section">
            <h1><?php echo __('welcome'); ?>, <?php echo htmlspecialchars($_SESSION['user_data']->username); ?></h1>
            <p class="last-login"><?php echo __('last_login'); ?>: <?php echo date('F j, Y, g:i a'); ?></p>
            
            <div class="quick-actions">
                <a href="<?php echo URL_ROOT; ?>/music/generate" class="btn btn-primary with-icon">
                    <i class="fas fa-plus"></i>
                    <span><?php echo __('generate_music_btn'); ?></span>
                </a>
                
                <?php if($_SESSION['user_data']->subscription_tier !== 'free'): ?>
                <a href="<?php echo URL_ROOT; ?>/music/generateLyrics" class="btn btn-outline with-icon">
                    <i class="fas fa-microphone"></i>
                    <span><?php echo __('generate_lyrics_btn'); ?></span>
                </a>
                <?php endif; ?>
                
                <a href="<?php echo URL_ROOT; ?>/music/playlists" class="btn btn-outline with-icon">
                    <i class="fas fa-list"></i>
                    <span><?php echo __('playlists'); ?></span>
                </a>
            </div>
        </div>
        
        <div class="account-summary">
            <div class="subscription-info">
                <div class="subscription-badge <?php echo $_SESSION['user_data']->subscription_tier; ?>">
                    <i class="fas <?php echo $_SESSION['user_data']->subscription_tier === 'professional' ? 'fa-crown' : ($_SESSION['user_data']->subscription_tier === 'premium' ? 'fa-star' : 'fa-user'); ?>"></i>
                    <span><?php echo ucfirst($_SESSION['user_data']->subscription_tier); ?></span>
                </div>
                
                <?php if($_SESSION['user_data']->subscription_tier !== 'professional'): ?>
                <a href="<?php echo URL_ROOT; ?>/users/subscription" class="upgrade-link"><?php echo __('upgrade'); ?> <i class="fas fa-arrow-right"></i></a>
                <?php endif; ?>
            </div>
            
            <div class="usage-counter">
                <div class="counter-label"><?php echo __('monthly_generations'); ?></div>
                <div class="counter-value">
                    <?php 
                    $monthlyLimit = $_SESSION['user_data']->subscription_tier === 'professional' ? 'Unlimited' : 
                                   ($_SESSION['user_data']->subscription_tier === 'premium' ? PREMIUM_GENERATION_LIMIT : FREE_GENERATION_LIMIT);
                    echo $data['usageStats']['monthly_generated'] . ' / ' . ($monthlyLimit === 'Unlimited' ? '∞' : $monthlyLimit);
                    ?>
                </div>
                <?php if($monthlyLimit !== 'Unlimited'): ?>
                <div class="usage-progress-bar">
                    <div class="progress-fill" style="width: <?php echo ($data['usageStats']['monthly_generated'] / $monthlyLimit * 100); ?>%"></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-music"></i></div>
            <div class="stat-value"><?php echo $data['usageStats']['total_tracks']; ?></div>
            <div class="stat-label"><?php echo __('total_tracks'); ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-heart"></i></div>
            <div class="stat-value"><?php echo $data['usageStats']['total_favorites']; ?></div>
            <div class="stat-label"><?php echo __('favorites'); ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-list"></i></div>
            <div class="stat-value"><?php echo $data['usageStats']['total_playlists']; ?></div>
            <div class="stat-label"><?php echo __('playlists'); ?></div>
        </div>
    </div>
    
    <div class="dashboard-content">
        <div class="dashboard-section recent-tracks">
            <div class="section-header">
                <h2><?php echo __('recently_generated'); ?></h2>
                <a href="<?php echo URL_ROOT; ?>/music/library/recent" class="view-all"><?php echo __('view_all'); ?> <i class="fas fa-chevron-right"></i></a>
            </div>
            
            <div class="tracks-grid">
                <?php if(empty($data['recentTracks'])): ?>
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-music"></i></div>
                    <h3><?php echo __('no_tracks_yet'); ?></h3>
                    <p><?php echo __('start_generating'); ?></p>
                    <a href="<?php echo URL_ROOT; ?>/music/generate" class="btn btn-primary"><?php echo __('generate_first_track'); ?></a>
                </div>
                <?php else: ?>
                <?php foreach($data['recentTracks'] as $track): ?>
                <div class="track-card">
                    <div class="track-art">
                        <img src="<?php echo URL_ROOT; ?>/public/img/track-art/<?php echo $track->genre; ?>.jpg" alt="<?php echo $track->title; ?>">
                        <div class="track-overlay">
                            <a href="<?php echo URL_ROOT; ?>/music/track/<?php echo $track->id; ?>" class="play-btn">
                                <i class="fas fa-play"></i>
                            </a>
                        </div>
                    </div>
                    <div class="track-info">
                        <h3 class="track-title"><?php echo $track->title; ?></h3>
                        <p class="track-meta">
                            <span class="track-genre"><?php echo ucfirst($track->genre); ?></span>
                            <span class="track-duration"><?php echo floor($track->duration / 60) . ':' . str_pad($track->duration % 60, 2, '0', STR_PAD_LEFT); ?></span>
                        </p>
                        <div class="track-actions">
                            <a href="<?php echo URL_ROOT; ?>/music/track/<?php echo $track->id; ?>" class="btn btn-sm"><?php echo __('details_btn'); ?></a>
                            <a href="<?php echo URL_ROOT; ?>/music/toggleFavorite/<?php echo $track->id; ?>" class="btn-icon <?php echo $this->musicModel->isTrackFavorite($_SESSION['user_id'], $track->id) ? 'favorited' : ''; ?>">
                                <i class="fas fa-heart"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="dashboard-section favorites">
            <div class="section-header">
                <h2><?php echo __('favorites'); ?></h2>
                <a href="<?php echo URL_ROOT; ?>/music/library/favorites" class="view-all"><?php echo __('view_all'); ?> <i class="fas fa-chevron-right"></i></a>
            </div>
            
            <div class="tracks-grid">
                <?php if(empty($data['favoriteTracks'])): ?>
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-heart"></i></div>
                    <h3><?php echo __('no_favorites_yet'); ?></h3>
                    <p><?php echo __('mark_favorites'); ?></p>
                    <?php if(!empty($data['recentTracks'])): ?>
                    <a href="<?php echo URL_ROOT; ?>/music/library" class="btn btn-primary"><?php echo __('browse_tracks'); ?></a>
                    <?php else: ?>
                    <a href="<?php echo URL_ROOT; ?>/music/generate" class="btn btn-primary"><?php echo __('generate_first'); ?></a>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <?php foreach($data['favoriteTracks'] as $track): ?>
                <div class="track-card">
                    <div class="track-art">
                        <img src="<?php echo URL_ROOT; ?>/public/img/track-art/<?php echo $track->genre; ?>.jpg" alt="<?php echo $track->title; ?>">
                        <div class="track-overlay">
                            <a href="<?php echo URL_ROOT; ?>/music/track/<?php echo $track->id; ?>" class="play-btn">
                                <i class="fas fa-play"></i>
                            </a>
                        </div>
                    </div>
                    <div class="track-info">
                        <h3 class="track-title"><?php echo $track->title; ?></h3>
                        <p class="track-meta">
                            <span class="track-genre"><?php echo ucfirst($track->genre); ?></span>
                            <span class="track-duration"><?php echo floor($track->duration / 60) . ':' . str_pad($track->duration % 60, 2, '0', STR_PAD_LEFT); ?></span>
                        </p>
                        <div class="track-actions">
                            <a href="<?php echo URL_ROOT; ?>/music/track/<?php echo $track->id; ?>" class="btn btn-sm"><?php echo __('details_btn'); ?></a>
                            <a href="<?php echo URL_ROOT; ?>/music/toggleFavorite/<?php echo $track->id; ?>" class="btn-icon favorited">
                                <i class="fas fa-heart"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="dashboard-section playlists">
            <div class="section-header">
                <h2><?php echo __('playlists'); ?></h2>
                <a href="<?php echo URL_ROOT; ?>/music/playlists" class="view-all"><?php echo __('view_all'); ?> <i class="fas fa-chevron-right"></i></a>
            </div>
            
            <div class="playlists-grid">
                <?php if(empty($data['playlists'])): ?>
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-list"></i></div>
                    <h3><?php echo __('no_playlists_yet'); ?></h3>
                    <p><?php echo __('create_playlists_organize'); ?></p>
                    <a href="<?php echo URL_ROOT; ?>/music/createPlaylist" class="btn btn-primary"><?php echo __('create_first_playlist'); ?></a>
                </div>
                <?php else: ?>
                <?php foreach($data['playlists'] as $playlist): ?>
                <div class="playlist-card">
                    <div class="playlist-art">
                        <div class="playlist-icon"><i class="fas fa-music"></i></div>
                        <div class="playlist-overlay">
                            <a href="<?php echo URL_ROOT; ?>/music/playlist/<?php echo $playlist->id; ?>" class="view-btn">
                                <i class="fas fa-list"></i>
                            </a>
                        </div>
                    </div>
                    <div class="playlist-info">
                        <h3 class="playlist-title"><?php echo $playlist->name; ?></h3>
                        <p class="playlist-meta">
                            <span class="track-count"><?php echo $playlist->track_count; ?> <?php echo __('tracks'); ?></span>
                        </p>
                        <a href="<?php echo URL_ROOT; ?>/music/playlist/<?php echo $playlist->id; ?>" class="btn btn-sm"><?php echo __('view_btn'); ?></a>
                    </div>
                </div>
                <?php endforeach; ?>
                <div class="playlist-card create-playlist">
                    <a href="<?php echo URL_ROOT; ?>/music/createPlaylist" class="create-link">
                        <div class="create-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                        <span><?php echo __('create_new_playlist'); ?></span>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>