<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="share-container">
    <div class="share-header">
        <div class="container">
            <h1>Listen to AI-Generated Music</h1>
            <p>Experience the future of music creation with Octaverum AI</p>
        </div>
    </div>
    
    <div class="share-track-container">
        <div class="container">
            <div class="share-track-card">
                <div class="share-track-header">
                    <div class="share-track-art">
                        <img src="<?php echo URL_ROOT; ?>/public/img/track-art/<?php echo $data['track']->genre; ?>.jpg" alt="<?php echo $data['track']->title; ?>">
                    </div>
                    
                    <div class="share-track-info">
                        <h2><?php echo $data['track']->title; ?></h2>
                        <div class="track-meta-info">
                            <span class="track-genre"><i class="fas fa-music"></i> <?php echo ucfirst($data['track']->genre); ?></span>
                            <span class="track-duration"><i class="fas fa-clock"></i> <?php echo floor($data['track']->duration / 60) . ':' . str_pad($data['track']->duration % 60, 2, '0', STR_PAD_LEFT); ?></span>
                            <span class="track-date"><i class="fas fa-calendar-alt"></i> <?php echo date('M j, Y', strtotime($data['track']->created_at)); ?></span>
                        </div>
                        
                        <?php if(isset($data['owner'])): ?>
                        <div class="track-creator">
                            <p>Created by <?php echo $data['owner']->username; ?> with Octaverum AI</p>
                        </div>
                        <?php endif; ?>
                        
                        <div class="track-prompt">
                            <p><strong>Prompt:</strong> <?php echo $data['track']->prompt; ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="share-player">
                    <div class="share-player-controls">
                        <button id="sharePlay" class="share-play-btn">
                            <i class="fas fa-play"></i>
                        </button>
                        
                        <div class="share-progress-container">
                            <span id="shareCurrentTime" class="share-time">0:00</span>
                            <div class="share-progress-bar">
                                <div id="shareProgressCurrent" class="share-progress-current"></div>
                            </div>
                            <span id="shareTotalTime" class="share-time">0:00</span>
                        </div>
                        
                        <div class="share-volume">
                            <i class="fas fa-volume-up"></i>
                            <div class="share-volume-slider">
                                <div class="share-volume-level"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="share-waveform-container">
                        <canvas id="shareWaveform"></canvas>
                    </div>
                </div>
                
                <div class="share-actions">
                    <a href="<?php echo URL_ROOT; ?>/users/register" class="btn btn-primary btn-lg">Create Your Own AI Music</a>
                    <div class="share-social">
                        <span>Share:</span>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(URL_ROOT . '/music/share/' . $data['track']->id); ?>&text=<?php echo urlencode('Check out this AI-generated track: ' . $data['track']->title); ?>" target="_blank" class="social-btn twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(URL_ROOT . '/music/share/' . $data['track']->id); ?>" target="_blank" class="social-btn facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send?text=<?php echo urlencode('Check out this AI-generated track: ' . $data['track']->title . ' ' . URL_ROOT . '/music/share/' . $data['track']->id); ?>" target="_blank" class="social-btn whatsapp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="mailto:?subject=<?php echo urlencode('Check out this AI-generated track: ' . $data['track']->title); ?>&body=<?php echo urlencode('I found this amazing AI-generated music: ' . URL_ROOT . '/music/share/' . $data['track']->id); ?>" class="social-btn email">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="share-cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Create AI-Generated Music in Minutes</h2>
                <p>Describe the music you want, choose your genre and settings, and our AI will create unique tracks just for you.</p>
                <div class="cta-features">
                    <div class="cta-feature">
                        <div class="feature-icon">
                            <i class="fas fa-magic"></i>
                        </div>
                        <div class="feature-text">
                            <h3>Easy Creation</h3>
                            <p>Just describe what you want, and our AI does the rest</p>
                        </div>
                    </div>
                    <div class="cta-feature">
                        <div class="feature-icon">
                            <i class="fas fa-sliders-h"></i>
                        </div>
                        <div class="feature-text">
                            <h3>Full Control</h3>
                            <p>Adjust genre, tempo, key, and more to perfect your sound</p>
                        </div>
                    </div>
                    <div class="cta-feature">
                        <div class="feature-icon">
                            <i class="fas fa-download"></i>
                        </div>
                        <div class="feature-text">
                            <h3>Download & Share</h3>
                            <p>Use your music anywhere or share it with the world</p>
                        </div>
                    </div>
                </div>
                <div class="cta-buttons">
                    <a href="<?php echo URL_ROOT; ?>/users/register" class="btn btn-glow btn-lg">Sign Up Free</a>
                    <a href="<?php echo URL_ROOT; ?>/pages/pricing" class="btn btn-outline btn-lg">View Plans</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="sample-tracks-section">
        <div class="container">
            <h2>More AI-Generated Music</h2>
            <p>Listen to some example tracks created with Octaverum AI</p>
            
            <div class="sample-tracks">
                <div class="sample-track">
                    <div class="sample-art">
                        <img src="<?php echo URL_ROOT; ?>/public/img/track-art/electronic.jpg" alt="Electronic Sample">
                        <button class="sample-play" data-url="<?php echo URL_ROOT; ?>/public/audio/demo/sample_1.mp3">
                            <i class="fas fa-play"></i>
                        </button>
                    </div>
                    <div class="sample-info">
                        <h3>Electronic Dreams</h3>
                        <p>Electronic • 1:45</p>
                    </div>
                </div>
                <div class="sample-track">
                    <div class="sample-art">
                        <img src="<?php echo URL_ROOT; ?>/public/img/track-art/ambient.jpg" alt="Ambient Sample">
                        <button class="sample-play" data-url="<?php echo URL_ROOT; ?>/public/audio/demo/sample_2.mp3">
                            <i class="fas fa-play"></i>
                        </button>
                    </div>
                    <div class="sample-info">
                        <h3>Ambient Journey</h3>
                        <p>Ambient • 2:10</p>
                    </div>
                </div>
                <div class="sample-track">
                    <div class="sample-art">
                        <img src="<?php echo URL_ROOT; ?>/public/img/track-art/cinematic.jpg" alt="Cinematic Sample">
                        <button class="sample-play" data-url="<?php echo URL_ROOT; ?>/public/audio/demo/sample_3.mp3">
                            <i class="fas fa-play"></i>
                        </button>
                    </div>
                    <div class="sample-info">
                        <h3>Cinematic Horizon</h3>
                        <p>Cinematic • 1:30</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Main shared track player
document.addEventListener('DOMContentLoaded', function() {
    const playBtn = document.getElementById('sharePlay');
    const progressBar = document.querySelector('.share-progress-bar');
    const progressCurrent = document.getElementById('shareProgressCurrent');
    const currentTimeEl = document.getElementById('shareCurrentTime');
    const totalTimeEl = document.getElementById('shareTotalTime');
    const volumeSlider = document.querySelector('.share-volume-slider');
    const volumeLevel = document.querySelector('.share-volume-level');
    const volumeIcon = document.querySelector('.share-volume i');
    
    // Create audio element
    const audio = new Audio('<?php echo $data['track']->audio_url; ?>');
    
    // Initialize waveform visualization
    const canvas = document.getElementById('shareWaveform');
    const ctx = canvas.getContext('2d');
    let isPlaying = false;
    
    // Set canvas size
    function resizeCanvas() {
        canvas.width = canvas.parentElement.clientWidth;
        canvas.height = canvas.parentElement.clientHeight;
    }
    
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);
    
    // Update play/pause button
    function updatePlayButton() {
        if (isPlaying) {
            playBtn.innerHTML = '<i class="fas fa-pause"></i>';
        } else {
            playBtn.innerHTML = '<i class="fas fa-play"></i>';
        }
    }
    
    // Play/pause toggle
    playBtn.addEventListener('click', function() {
        if (isPlaying) {
            audio.pause();
        } else {
            audio.play();
        }
        
        isPlaying = !isPlaying;
        updatePlayButton();
    });
    
    // Progress bar
    audio.addEventListener('timeupdate', function() {
        const progress = (audio.currentTime / audio.duration) * 100;
        progressCurrent.style.width = `${progress}%`;
        
        // Update current time
        const minutes = Math.floor(audio.currentTime / 60);
        const seconds = Math.floor(audio.currentTime % 60);
        currentTimeEl.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
    });
    
    // Set total time when metadata loaded
    audio.addEventListener('loadedmetadata', function() {
        const minutes = Math.floor(audio.duration / 60);
        const seconds = Math.floor(audio.duration % 60);
        totalTimeEl.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
    });
    
    // Seek functionality
    progressBar.addEventListener('click', function(e) {
        const rect = progressBar.getBoundingClientRect();
        const clickPosition = (e.clientX - rect.left) / rect.width;
        audio.currentTime = clickPosition * audio.duration;
    });
    
    // Volume control
    volumeSlider.addEventListener('click', function(e) {
        const rect = volumeSlider.getBoundingClientRect();
        const clickPosition = (e.clientX - rect.left) / rect.width;
        
        // Set volume (between 0 and 1)
        audio.volume = Math.max(0, Math.min(1, clickPosition));
        volumeLevel.style.width = `${audio.volume * 100}%`;
        
        // Update volume icon
        if (audio.volume === 0) {
            volumeIcon.className = 'fas fa-volume-mute';
        } else if (audio.volume < 0.5) {
            volumeIcon.className = 'fas fa-volume-down';
        } else {
            volumeIcon.className = 'fas fa-volume-up';
        }
    });
    
    // Toggle mute on icon click
    volumeIcon.addEventListener('click', function() {
        if (audio.volume > 0) {
            // Store current volume
            volumeIcon.dataset.previousVolume = audio.volume;
            audio.volume = 0;
            volumeLevel.style.width = '0%';
            volumeIcon.className = 'fas fa-volume-mute';
        } else {
            // Restore previous volume
            const previousVolume = parseFloat(volumeIcon.dataset.previousVolume || 1);
            audio.volume = previousVolume;
            volumeLevel.style.width = `${previousVolume * 100}%`;
            
            if (previousVolume < 0.5) {
                volumeIcon.className = 'fas fa-volume-down';
            } else {
                volumeIcon.className = 'fas fa-volume-up';
            }
        }
    });
    
    // Track ended
    audio.addEventListener('ended', function() {
        isPlaying = false;
        updatePlayButton();
        progressCurrent.style.width = '0%';
        audio.currentTime = 0;
    });
    
    // Simple waveform visualization
    function drawWaveform() {
        requestAnimationFrame(drawWaveform);
        
        // Clear canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        // Only animate when playing
        if (isPlaying) {
            const centerY = canvas.height / 2;
            const barWidth = 4;
            const gap = 2;
            const numBars = Math.floor(canvas.width / (barWidth + gap));
            
            // Create gradient
            const gradient = ctx.createLinearGradient(0, 0, canvas.width, 0);
            gradient.addColorStop(0, 'rgba(15, 247, 239, 0.7)');
            gradient.addColorStop(0.5, 'rgba(193, 101, 221, 0.7)');
            gradient.addColorStop(1, 'rgba(247, 42, 138, 0.7)');
            
            // Draw bars
            for (let i = 0; i < numBars; i++) {
                // Amplitude calculations (simulating audio frequencies)
                let amplitude = Math.random() * 0.5 + 0.1; // Base randomness
                
                // Add some patterns based on position
                const positionFactor = Math.sin((i / numBars) * Math.PI) * 0.3;
                amplitude = amplitude * (0.7 + positionFactor);
                
                // Emphasize around the current playback position
                const posX = i / numBars;
                const playbackPos = audio.currentTime / audio.duration;
                const playbackEmphasis = Math.max(0, 1 - Math.abs(posX - playbackPos) * 10);
                amplitude = amplitude * (1 + playbackEmphasis);
                
                const height = amplitude * centerY;
                
                ctx.fillStyle = gradient;
                ctx.fillRect(i * (barWidth + gap), centerY - height, barWidth, height * 2);
            }
        }
    }
    
    // Start visualization
    drawWaveform();
    
    // Sample track players
    const sampleButtons = document.querySelectorAll('.sample-play');
    let currentSample = null;
    
    sampleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const url = this.dataset.url;
            
            // If we already have a sample playing
            if (currentSample) {
                currentSample.pause();
                currentSample.currentButton.innerHTML = '<i class="fas fa-play"></i>';
                
                // If clicking the same button, just stop playback
                if (currentSample.currentButton === this) {
                    currentSample = null;
                    return;
                }
            }
            
            // Create new audio for this sample
            const sampleAudio = new Audio(url);
            sampleAudio.currentButton = this;
            
            sampleAudio.addEventListener('ended', function() {
                this.currentButton.innerHTML = '<i class="fas fa-play"></i>';
                currentSample = null;
            });
            
            // Play the sample
            sampleAudio.play();
            this.innerHTML = '<i class="fas fa-pause"></i>';
            currentSample = sampleAudio;
        });
    });
});
</script>

<style>
/* Share page specific styles */
.share-container {
    margin-top: var(--header-height);
}

.share-header {
    background-color: var(--bg-tertiary);
    padding: 3rem 0;
    text-align: center;
    border-bottom: 1px solid var(--border-color);
}

.share-header h1 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.share-header p {
    font-size: 1.2rem;
    color: var(--text-secondary);
}

.share-track-container {
    padding: 3rem 0;
    background-color: var(--bg-secondary);
}

.share-track-card {
    background-color: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 2rem;
    box-shadow: var(--box-shadow);
}

.share-track-header {
    display: flex;
    gap: 2rem;
    margin-bottom: 2rem;
}

.share-track-art {
    width: 200px;
    height: 200px;
    border-radius: 8px;
    overflow: hidden;
}

.share-track-art img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.share-track-info {
    flex: 1;
}

.share-track-info h2 {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.track-meta-info {
    display: flex;
    gap: 1.5rem;
    color: var(--text-secondary);
    margin-bottom: 1rem;
}

.track-creator {
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border-color);
}

.track-prompt {
    font-style: italic;
    color: var(--text-secondary);
}

.share-player {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background-color: rgba(15, 15, 25, 0.3);
    border-radius: 8px;
}

.share-player-controls {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.share-play-btn {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color: var(--accent-primary);
    border: none;
    color: var(--bg-primary);
    font-size: 1.5rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 15px var(--glow-primary);
}

.share-progress-container {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.share-progress-bar {
    flex: 1;
    height: 6px;
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
    overflow: hidden;
    cursor: pointer;
}

.share-progress-current {
    height: 100%;
    width: 0;
    background-color: var(--accent-primary);
    border-radius: 3px;
    box-shadow: 0 0 5px var(--glow-primary);
}

.share-time {
    font-family: 'Orbitron', sans-serif;
    color: var(--text-secondary);
    min-width: 40px;
}

.share-volume {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--text-secondary);
}

.share-volume-slider {
    width: 100px;
    height: 6px;
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
    overflow: hidden;
    cursor: pointer;
}

.share-volume-level {
    height: 100%;
    width: 70%;
    background-color: var(--accent-tertiary);
    border-radius: 3px;
}

.share-waveform-container {
    height: 100px;
    width: 100%;
}

.share-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.share-social {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.social-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-primary);
    background-color: rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.social-btn:hover {
    transform: translateY(-3px);
}

.social-btn.twitter:hover {
    background-color: #1DA1F2;
}

.social-btn.facebook:hover {
    background-color: #4267B2;
}

.social-btn.whatsapp:hover {
    background-color: #25D366;
}

.social-btn.email:hover {
    background-color: #EA4335;
}

.share-cta-section {
    padding: 5rem 0;
    background: linear-gradient(135deg, rgba(15, 247, 239, 0.1) 0%, rgba(247, 42, 138, 0.1) 100%);
    border-top: 1px solid var(--border-color);
}

.cta-content {
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
}

.cta-content h2 {
    font-size: 2.2rem;
    margin-bottom: 1.5rem;
}

.cta-content > p {
    font-size: 1.2rem;
    color: var(--text-secondary);
    margin-bottom: 3rem;
}

.cta-features {
    display: flex;
    justify-content: center;
    gap: 3rem;
    margin-bottom: 3rem;
}

.cta-feature {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    max-width: 200px;
}

.feature-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background-color: rgba(15, 247, 239, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
    font-size: 2rem;
    color: var(--accent-primary);
    box-shadow: 0 0 15px var(--glow-primary);
}

.feature-text h3 {
    margin-bottom: 0.5rem;
}

.feature-text p {
    color: var(--text-secondary);
}

.cta-buttons {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
}

.sample-tracks-section {
    padding: 5rem 0;
    background-color: var(--bg-tertiary);
    text-align: center;
}

.sample-tracks-section h2 {
    margin-bottom: 1rem;
}

.sample-tracks-section > p {
    color: var(--text-secondary);
    margin-bottom: 3rem;
}

.sample-tracks {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-top: 2rem;
}

.sample-track {
    width: 200px;
}

.sample-art {
    width: 200px;
    height: 200px;
    border-radius: 8px;
    overflow: hidden;
    position: relative;
    margin-bottom: 1rem;
}

.sample-art img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.3s ease;
}

.sample-play {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color: rgba(15, 247, 239, 0.8);
    border: none;
    color: var(--bg-primary);
    font-size: 1.5rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.sample-art:hover img {
    filter: brightness(0.7);
}

.sample-art:hover .sample-play {
    opacity: 1;
}

.sample-info h3 {
    margin-bottom: 0.25rem;
}

.sample-info p {
    color: var(--text-secondary);
}

@media (max-width: 992px) {
    .share-track-header {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .cta-features {
        flex-direction: column;
        align-items: center;
    }
    
    .sample-tracks {
        flex-direction: column;
        align-items: center;
    }
}

@media (max-width: 768px) {
    .share-actions {
        flex-direction: column;
        gap: 2rem;
    }
    
    .cta-buttons {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?>