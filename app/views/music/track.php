<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="page-container">
    <div class="track-detail-container">
        <div class="track-detail-header">
            <div class="track-header-info">
                <div class="track-detail-art">
                    <img src="<?php echo URL_ROOT; ?>/public/img/track-art/<?php echo $data['track']->genre; ?>.jpg" alt="<?php echo $data['track']->title; ?>">
                </div>
                <div class="track-detail-meta">
                    <h1><?php echo $data['track']->title; ?></h1>
                    <div class="track-meta-info">
                        <span class="track-genre"><i class="fas fa-music"></i> <?php echo ucfirst($data['track']->genre); ?></span>
                        <span class="track-duration"><i class="fas fa-clock"></i> <?php echo floor($data['track']->duration / 60) . ':' . str_pad($data['track']->duration % 60, 2, '0', STR_PAD_LEFT); ?></span>
                        <span class="track-date"><i class="fas fa-calendar-alt"></i> <?php echo date('M j, Y', strtotime($data['track']->created_at)); ?></span>
                    </div>
                    <div class="track-prompt">
                        <p><strong><?php echo __('prompt', 'Prompt'); ?>:</strong> <?php echo $data['track']->prompt; ?></p>
                    </div>
                </div>
            </div>
            <div class="track-actions-container">
                <div class="track-actions">
                    <a href="<?php echo URL_ROOT; ?>/music/toggleFavorite/<?php echo $data['track']->id; ?>" class="btn-icon <?php echo $data['isFavorite'] ? 'favorited' : ''; ?> btn-lg">
                        <i class="fas fa-heart"></i>
                    </a>
                    
                    <button class="btn-icon btn-lg" id="shareTrackBtn">
                        <i class="fas fa-share-alt"></i>
                    </button>
                    
                    <div class="dropdown">
                        <button class="btn-icon btn-lg dropdown-toggle">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="#" data-toggle="modal" data-target="#addToPlaylistModal"><i class="fas fa-list"></i> <?php echo __('add_to_playlist', 'Add to Playlist'); ?></a>
                            <a href="<?php echo URL_ROOT; ?>/music/editTrack/<?php echo $data['track']->id; ?>"><i class="fas fa-edit"></i> <?php echo __('edit', 'Edit Track'); ?></a>
                            <a href="<?php echo URL_ROOT; ?>/music/deleteTrack/<?php echo $data['track']->id; ?>"><i class="fas fa-trash"></i> <?php echo __('delete', 'Delete Track'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="track-player-container">
            <div class="track-player" 
                data-track-url="<?php echo $data['track']->audio_url; ?>"
                data-track-title="<?php echo $data['track']->title; ?>"
                data-track-genre="<?php echo $data['track']->genre; ?>"
                data-track-id="<?php echo $data['track']->id; ?>"
                data-auto-play="<?php echo $_SESSION['user_data']->auto_play ?? 'false'; ?>">
                
                <div class="player-controls-container">
                    <div class="main-controls">
                        <button id="trackPlay" class="play-btn btn-lg">
                            <i class="fas fa-play"></i>
                        </button>
                    </div>
                    
                    <div class="progress-container">
                        <span id="currentTime" class="time">0:00</span>
                        <div class="progress-bar-container">
                            <div class="progress-bar">
                                <div class="progress-current"></div>
                            </div>
                        </div>
                        <span id="totalTime" class="time">0:00</span>
                    </div>
                </div>
                
                <div class="visualization-container">
                    <canvas id="trackVisualization"></canvas>
                    
                    <div class="visualization-controls mt-3">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary active" data-viz="waveform">
                                <i class="fas fa-wave-square me-1"></i> <?php echo __('viz_waveform', 'Waveform'); ?>
                            </button>
                            <button class="btn btn-sm btn-outline-primary" data-viz="bars">
                                <i class="fas fa-bars me-1"></i> <?php echo __('viz_bars', 'Bars'); ?>
                            </button>
                            <button class="btn btn-sm btn-outline-primary" data-viz="circle">
                                <i class="fas fa-circle me-1"></i> <?php echo __('viz_circle', 'Circle'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="track-details-tabs">
            <div class="tabs-header">
                <button class="tab-btn active" data-tab="parameters"><?php echo __('parameters', 'Parameters'); ?></button>
                <button class="tab-btn" data-tab="lyrics"><?php echo __('lyrics', 'Lyrics'); ?></button>
                <button class="tab-btn" data-tab="comments"><?php echo __('comments', 'Comments'); ?></button>
            </div>
            
            <div class="tab-content">
                <div class="tab-pane active" id="parameters">
                    <div class="parameters-grid">
                        <div class="parameter-item">
                            <div class="parameter-label">Genre</div>
                            <div class="parameter-value"><?php echo ucfirst($data['track']->genre); ?></div>
                        </div>
                        <div class="parameter-item">
                            <div class="parameter-label">BPM</div>
                            <div class="parameter-value"><?php echo $data['track']->bpm; ?></div>
                        </div>
                        <div class="parameter-item">
                            <div class="parameter-label">Key</div>
                            <div class="parameter-value"><?php echo $data['track']->key; ?></div>
                        </div>
                        <div class="parameter-item">
                            <div class="parameter-label">Duration</div>
                            <div class="parameter-value"><?php echo floor($data['track']->duration / 60) . ':' . str_pad($data['track']->duration % 60, 2, '0', STR_PAD_LEFT); ?></div>
                        </div>
                        <div class="parameter-item">
                            <div class="parameter-label">Quality</div>
                            <div class="parameter-value"><?php echo ucfirst($data['parameters']->quality); ?></div>
                        </div>
                        <div class="parameter-item">
                            <div class="parameter-label">Created</div>
                            <div class="parameter-value"><?php echo date('M j, Y, g:i a', strtotime($data['track']->created_at)); ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="tab-pane" id="lyrics">
                    <?php if(isset($data['track']->lyrics) && !empty($data['track']->lyrics)): ?>
                        <div class="lyrics-content">
                            <?php echo nl2br($data['track']->lyrics); ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-microphone-slash"></i></div>
                            <h3>No lyrics available</h3>
                            <?php if($_SESSION['user_data']->subscription_tier !== 'free'): ?>
                                <p>Generate lyrics for this track using AI</p>
                                <a href="<?php echo URL_ROOT; ?>/music/generateLyrics?track=<?php echo $data['track']->id; ?>" class="btn btn-primary">Generate Lyrics</a>
                            <?php else: ?>
                                <p>Lyrics generation is available with Premium and Professional subscriptions</p>
                                <a href="<?php echo URL_ROOT; ?>/users/subscription" class="btn btn-primary">Upgrade Now</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="tab-pane" id="comments">
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-comments"></i></div>
                        <h3>Comments coming soon</h3>
                        <p>Share your thoughts about tracks with other users</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add to Playlist Modal -->
<div class="modal" id="addToPlaylistModal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Add to Playlist</h2>
        <div class="modal-body">
            <?php if(empty($data['playlists'])): ?>
                <div class="empty-state">
                    <p>You don't have any playlists yet</p>
                    <a href="<?php echo URL_ROOT; ?>/music/createPlaylist" class="btn btn-primary">Create Playlist</a>
                </div>
            <?php else: ?>
                <form action="<?php echo URL_ROOT; ?>/music/addToPlaylist" method="post">
                    <input type="hidden" name="track_id" value="<?php echo $data['track']->id; ?>">
                    
                    <div class="form-group">
                        <label for="playlist_id">Select Playlist</label>
                        <select name="playlist_id" id="playlist_id" required>
                            <?php foreach($data['playlists'] as $playlist): ?>
                                <option value="<?php echo $playlist->id; ?>"><?php echo $playlist->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Add to Playlist</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Share Track Modal -->
<div class="modal" id="shareModal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Share This Track</h2>
        <div class="modal-body">
            <div class="share-options">
                <div class="share-option">
                    <h3>Direct Link</h3>
                    <div class="copy-input">
                        <input type="text" id="directLinkInput" value="<?php echo URL_ROOT; ?>/music/share/<?php echo $data['track']->id; ?>" readonly>
                        <button class="btn-copy" data-target="directLinkInput">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    <small>Share this link with anyone to let them hear your track</small>
                </div>
                
                <div class="share-option">
                    <h3>Embed Code</h3>
                    <div class="copy-input">
                        <textarea id="embedCodeInput" readonly rows="4"><iframe src="<?php echo URL_ROOT; ?>/music/embed/<?php echo $data['track']->id; ?>" width="100%" height="180" frameborder="0"></iframe></textarea>
                        <button class="btn-copy" data-target="embedCodeInput">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    <small>Embed this player on your website or blog</small>
                </div>
                
                <div class="share-option">
                    <h3>Download Track</h3>
                    <a href="<?php echo URL_ROOT; ?>/music/download/<?php echo $data['track']->id; ?>" class="btn btn-primary">
                        <i class="fas fa-download"></i> Download MP3
                    </a>
                    <small>
                        <?php if($_SESSION['user_data']->subscription_tier === 'professional'): ?>
                            Download unlimited tracks in studio quality
                        <?php elseif($_SESSION['user_data']->subscription_tier === 'premium'): ?>
                            Premium members can download up to 20 tracks per month
                        <?php else: ?>
                            Free members can download up to 5 tracks per month
                        <?php endif; ?>
                    </small>
                </div>
                
                <div class="share-option">
                    <h3>Share on Social Media</h3>
                    <div class="social-share-buttons">
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(URL_ROOT . '/music/share/' . $data['track']->id); ?>&text=<?php echo urlencode('Check out this AI-generated track: ' . $data['track']->title); ?>" target="_blank" class="social-share-btn twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(URL_ROOT . '/music/share/' . $data['track']->id); ?>" target="_blank" class="social-share-btn facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send?text=<?php echo urlencode('Check out this AI-generated track: ' . $data['track']->title . ' ' . URL_ROOT . '/music/share/' . $data['track']->id); ?>" target="_blank" class="social-share-btn whatsapp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="mailto:?subject=<?php echo urlencode('Check out this AI-generated track: ' . $data['track']->title); ?>&body=<?php echo urlencode('I created this music with AI: ' . URL_ROOT . '/music/share/' . $data['track']->id); ?>" class="social-share-btn email">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </div>
                    <small>Share your creation on your favorite platform</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Tab functionality
document.querySelectorAll('.tab-btn').forEach(button => {
    button.addEventListener('click', function() {
        // Remove active class from all buttons and panes
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
        
        // Add active class to clicked button
        this.classList.add('active');
        
        // Show corresponding tab content
        const tabId = this.getAttribute('data-tab');
        document.getElementById(tabId).classList.add('active');
    });
});

// Play button functionality
document.getElementById('trackPlay').addEventListener('click', function() {
    const player = window.musicPlayer;
    
    if (this.classList.contains('playing')) {
        player.pause();
        this.classList.remove('playing');
        this.innerHTML = '<i class="fas fa-play"></i>';
    } else {
        // If no track is loaded yet, load from the track player data
        if (!player.currentTrack || player.currentTrack.id !== trackPlayer.dataset.trackId) {
            const trackPlayer = document.querySelector('.track-player');
            player.loadTrack({
                id: trackPlayer.dataset.trackId,
                url: trackPlayer.dataset.trackUrl,
                title: trackPlayer.dataset.trackTitle,
                genre: trackPlayer.dataset.trackGenre,
                art: `/public/img/track-art/${trackPlayer.dataset.trackGenre}.jpg`
            });
        }
        
        player.play();
        this.classList.add('playing');
        this.innerHTML = '<i class="fas fa-pause"></i>';
    }
});

// Share modal functionality
const shareModal = document.getElementById('shareModal');
const shareBtn = document.getElementById('shareTrackBtn');
const closeButtons = document.querySelectorAll('.close');

shareBtn.addEventListener('click', function() {
    shareModal.style.display = 'flex';
});

closeButtons.forEach(btn => {
    btn.addEventListener('click', function() {
        shareModal.style.display = 'none';
        document.getElementById('addToPlaylistModal').style.display = 'none';
    });
});

// Copy to clipboard functionality
document.querySelectorAll('.btn-copy').forEach(button => {
    button.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const inputElement = document.getElementById(targetId);
        
        inputElement.select();
        document.execCommand('copy');
        
        // Show copied feedback
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => {
            this.innerHTML = originalText;
        }, 2000);
    });
});

// Dropdown toggle
document.querySelector('.dropdown-toggle').addEventListener('click', function() {
    this.nextElementSibling.classList.toggle('show');
});

// Close dropdowns when clicking outside
window.addEventListener('click', function(e) {
    if (!e.target.matches('.dropdown-toggle') && !e.target.closest('.dropdown-menu')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if (menu.classList.contains('show')) {
                menu.classList.remove('show');
            }
        });
    }
    
    if (!e.target.closest('.modal-content') && !e.target.matches('#shareTrackBtn') && !e.target.matches('[data-toggle="modal"]')) {
        shareModal.style.display = 'none';
        document.getElementById('addToPlaylistModal').style.display = 'none';
    }
});

// Initialize track visualization if not handled by global player
if (typeof initTrackVisualization === 'function') {
    initTrackVisualization('trackVisualization');
}

// Visualization type switching
document.querySelectorAll('[data-viz]').forEach(button => {
    button.addEventListener('click', function() {
        // Remove active class from all buttons
        document.querySelectorAll('[data-viz]').forEach(btn => btn.classList.remove('active'));
        
        // Add active class to clicked button
        this.classList.add('active');
        
        // Get visualization type
        const vizType = this.getAttribute('data-viz');
        
        // Update visualization if player exists
        if (window.musicPlayer) {
            window.musicPlayer.setVisualizationType(vizType);
        }
        
        // Save preference in localStorage for future sessions
        localStorage.setItem('preferredVisualization', vizType);
    });
});

// Load saved visualization preference if exists
document.addEventListener('DOMContentLoaded', function() {
    const savedViz = localStorage.getItem('preferredVisualization');
    if (savedViz) {
        const vizButton = document.querySelector(`[data-viz="${savedViz}"]`);
        if (vizButton) {
            vizButton.click(); // Trigger the click event to set this visualization
        }
    }
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>