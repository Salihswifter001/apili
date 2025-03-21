/**
 * Octaverum AI - Music Player
 * Handles audio playback, track management and player UI functionality
 */

class MusicPlayer {
    constructor() {
        // Core player elements
        this.audioContext = null;
        this.audioElement = null;
        this.analyserNode = null;
        
        // Current track info
        this.currentTrack = null;
        this.playlist = [];
        this.currentIndex = 0;
        
        // Player UI elements
        this.player = document.getElementById('global-player');
        this.playBtn = document.getElementById('player-play');
        this.prevBtn = document.getElementById('player-prev');
        this.nextBtn = document.getElementById('player-next');
        this.progressBar = document.querySelector('.progress-current');
        this.currentTimeEl = document.querySelector('.current-time');
        this.totalTimeEl = document.querySelector('.total-time');
        this.volumeSlider = document.querySelector('.volume-slider');
        this.volumeLevel = document.querySelector('.volume-current');
        this.albumArt = document.querySelector('.player-album-art img');
        this.trackTitle = document.querySelector('.player-track-details .track-title');
        this.trackGenre = document.querySelector('.player-track-details .track-genre');
        
        // Initialize player
        this.init();
    }
    
    init() {
        // Create audio element
        this.audioElement = new Audio();
        
        // Setup event listeners
        this.setupEventListeners();
        
        // Try to initialize Web Audio API
        try {
            window.AudioContext = window.AudioContext || window.webkitAudioContext;
            this.audioContext = new AudioContext();
            
            // Create analyser for visualizations
            this.analyserNode = this.audioContext.createAnalyser();
            this.analyserNode.fftSize = 256;
            
            // Connect audio element to analyser
            const source = this.audioContext.createMediaElementSource(this.audioElement);
            source.connect(this.analyserNode);
            this.analyserNode.connect(this.audioContext.destination);
            
            // Initialize visualizer if exists
            if (typeof initVisualizer === 'function') {
                initVisualizer(this.analyserNode);
            }
        } catch (e) {
            console.warn('Web Audio API not supported in this browser');
        }
        
        // Check for page specific track info
        this.checkForPageTrack();
        
        // Set initial volume
        this.setVolume(0.7);
    }
    
    setupEventListeners() {
        // Play/Pause button
        this.playBtn.addEventListener('click', () => {
            if (!this.currentTrack) return;
            
            if (this.audioElement.paused) {
                this.play();
            } else {
                this.pause();
            }
        });
        
        // Previous track button
        this.prevBtn.addEventListener('click', () => {
            this.playPrevious();
        });
        
        // Next track button
        this.nextBtn.addEventListener('click', () => {
            this.playNext();
        });
        
        // Progress bar seeking
        const progressContainer = document.querySelector('.player-progress .progress-bar');
        progressContainer.addEventListener('click', (e) => {
            if (!this.currentTrack) return;
            
            const rect = progressContainer.getBoundingClientRect();
            const clickPosition = (e.clientX - rect.left) / rect.width;
            this.seekToPosition(clickPosition);
        });
        
        // Volume control
        this.volumeSlider.addEventListener('click', (e) => {
            const rect = this.volumeSlider.getBoundingClientRect();
            const clickPosition = (e.clientX - rect.left) / rect.width;
            this.setVolume(clickPosition);
        });
        
        // Audio element events
        this.audioElement.addEventListener('timeupdate', () => {
            this.updateProgress();
        });
        
        this.audioElement.addEventListener('ended', () => {
            this.playNext();
        });
        
        this.audioElement.addEventListener('loadedmetadata', () => {
            this.updateTotalTime();
        });
        
        // Track card play buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.play-btn')) {
                const trackCard = e.target.closest('.track-card');
                if (trackCard) {
                    const trackId = trackCard.dataset.trackId;
                    const trackUrl = trackCard.dataset.trackUrl;
                    const trackTitle = trackCard.dataset.trackTitle;
                    const trackGenre = trackCard.dataset.trackGenre;
                    const trackArt = trackCard.dataset.trackArt || '/public/img/track-art/' + trackGenre + '.jpg';
                    
                    if (trackUrl) {
                        this.loadTrack({
                            id: trackId,
                            url: trackUrl,
                            title: trackTitle,
                            genre: trackGenre,
                            art: trackArt
                        });
                        
                        this.play();
                        
                        // Show player if collapsed
                        if (this.player.classList.contains('player-collapsed')) {
                            this.player.classList.remove('player-collapsed');
                            const handleIcon = document.querySelector('.player-handle i');
                            handleIcon.classList.remove('fa-chevron-up');
                            handleIcon.classList.add('fa-chevron-down');
                        }
                    }
                }
            }
        });
    }
    
    checkForPageTrack() {
        // Check if we're on a track detail page
        const trackPlayer = document.querySelector('.track-player');
        if (trackPlayer) {
            const trackUrl = trackPlayer.dataset.trackUrl;
            const trackTitle = trackPlayer.dataset.trackTitle;
            const trackGenre = trackPlayer.dataset.trackGenre;
            const trackId = trackPlayer.dataset.trackId;
            const trackArt = trackPlayer.dataset.trackArt || '/public/img/track-art/' + trackGenre + '.jpg';
            
            if (trackUrl) {
                this.loadTrack({
                    id: trackId,
                    url: trackUrl,
                    title: trackTitle,
                    genre: trackGenre,
                    art: trackArt
                });
                
                // Auto-play if user preference is set
                const autoPlay = trackPlayer.dataset.autoPlay === 'true';
                if (autoPlay) {
                    this.play();
                }
            }
        }
        
        // Add all page tracks to playlist
        const trackCards = document.querySelectorAll('.track-card[data-track-url]');
        if (trackCards.length > 0) {
            this.playlist = [];
            
            trackCards.forEach(card => {
                this.playlist.push({
                    id: card.dataset.trackId,
                    url: card.dataset.trackUrl,
                    title: card.dataset.trackTitle,
                    genre: card.dataset.trackGenre,
                    art: card.dataset.trackArt || '/public/img/track-art/' + card.dataset.trackGenre + '.jpg'
                });
                
                // Set current index if we already loaded a track
                if (this.currentTrack && this.currentTrack.id === card.dataset.trackId) {
                    this.currentIndex = this.playlist.length - 1;
                }
            });
        }
    }
    
    loadTrack(track) {
        // Set current track data
        this.currentTrack = track;
        
        // Update audio source
        this.audioElement.src = track.url;
        this.audioElement.load();
        
        // Update player UI
        this.updatePlayerUI();
        
        // Reset progress
        this.progressBar.style.width = '0%';
        this.currentTimeEl.textContent = '0:00';
    }
    
    play() {
        if (!this.currentTrack) return;
        
        // Resume AudioContext if it was suspended (autoplay policy)
        if (this.audioContext && this.audioContext.state === 'suspended') {
            this.audioContext.resume();
        }
        
        // Play audio
        this.audioElement.play();
        
        // Update button icon
        this.playBtn.innerHTML = '<i class="fas fa-pause"></i>';
        
        // Add 'playing' class to the card if it exists
        if (this.currentTrack.id) {
            const activeCard = document.querySelector(`.track-card[data-track-id="${this.currentTrack.id}"]`);
            if (activeCard) {
                document.querySelectorAll('.track-card').forEach(card => {
                    card.classList.remove('playing');
                });
                activeCard.classList.add('playing');
            }
        }
    }
    
    pause() {
        this.audioElement.pause();
        this.playBtn.innerHTML = '<i class="fas fa-play"></i>';
    }
    
    playNext() {
        if (this.playlist.length === 0) return;
        
        this.currentIndex = (this.currentIndex + 1) % this.playlist.length;
        this.loadTrack(this.playlist[this.currentIndex]);
        this.play();
    }
    
    playPrevious() {
        if (this.playlist.length === 0) return;
        
        // If we're more than 3 seconds into the track, restart it instead of going to previous
        if (this.audioElement.currentTime > 3) {
            this.audioElement.currentTime = 0;
            return;
        }
        
        this.currentIndex = (this.currentIndex - 1 + this.playlist.length) % this.playlist.length;
        this.loadTrack(this.playlist[this.currentIndex]);
        this.play();
    }
    
    seekToPosition(position) {
        if (!this.audioElement.duration) return;
        this.audioElement.currentTime = position * this.audioElement.duration;
    }
    
    setVolume(level) {
        // Clamp volume between 0 and 1
        level = Math.max(0, Math.min(1, level));
        
        // Set audio volume
        this.audioElement.volume = level;
        
        // Update volume bar
        this.volumeLevel.style.width = (level * 100) + '%';
        
        // Update volume icon
        const volumeIcon = document.querySelector('.player-volume i');
        if (volumeIcon) {
            if (level === 0) {
                volumeIcon.className = 'fas fa-volume-mute';
            } else if (level < 0.5) {
                volumeIcon.className = 'fas fa-volume-down';
            } else {
                volumeIcon.className = 'fas fa-volume-up';
            }
        }
    }
    
    updateProgress() {
        if (!this.audioElement.duration) return;
        
        const progress = (this.audioElement.currentTime / this.audioElement.duration) * 100;
        this.progressBar.style.width = `${progress}%`;
        
        // Update current time display
        const minutes = Math.floor(this.audioElement.currentTime / 60);
        const seconds = Math.floor(this.audioElement.currentTime % 60);
        this.currentTimeEl.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
    }
    
    updateTotalTime() {
        if (!this.audioElement.duration) return;
        
        const minutes = Math.floor(this.audioElement.duration / 60);
        const seconds = Math.floor(this.audioElement.duration % 60);
        this.totalTimeEl.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
    }
    
    updatePlayerUI() {
        if (!this.currentTrack) return;
        
        // Update track info
        this.trackTitle.textContent = this.currentTrack.title || 'Unknown Track';
        this.trackGenre.textContent = this.currentTrack.genre || '-';
        
        // Update album art
        this.albumArt.src = this.currentTrack.art || '/public/img/default-album.png';
    }
    
    addToPlaylist(track) {
        this.playlist.push(track);
    }
    
    clearPlaylist() {
        this.playlist = [];
        this.currentIndex = 0;
    }
    
    toggleShuffle() {
        // Simple Fisher-Yates shuffle algorithm
        for (let i = this.playlist.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [this.playlist[i], this.playlist[j]] = [this.playlist[j], this.playlist[i]];
        }
        
        // Find the current track in the shuffled playlist
        this.currentIndex = this.playlist.findIndex(track => 
            track.id === this.currentTrack.id
        );
        
        // If not found, reset to beginning
        if (this.currentIndex === -1) {
            this.currentIndex = 0;
        }
    }
}

// Initialize player when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.musicPlayer = new MusicPlayer();
});