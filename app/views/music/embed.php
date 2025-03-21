<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['track']->title; ?> - Octaverum AI</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #12121f;
            color: #e0e0ff;
            line-height: 1.6;
            overflow: hidden;
        }
        
        .embed-player {
            width: 100%;
            height: 100vh;
            max-height: 180px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border-radius: 6px;
            border: 1px solid #343465;
        }
        
        .player-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            background-color: rgba(25, 25, 45, 0.7);
        }
        
        .track-art {
            width: 60px;
            height: 60px;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .track-art img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .track-info {
            flex: 1;
            overflow: hidden;
        }
        
        .track-title {
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-family: 'Arial', sans-serif;
            margin-bottom: 0.25rem;
        }
        
        .track-meta {
            display: flex;
            gap: 0.5rem;
            color: #a0a0d0;
            font-size: 0.8rem;
        }
        
        .branding {
            display: flex;
            align-items: center;
            font-size: 0.8rem;
            color: #7931ff;
            text-decoration: none;
        }
        
        .branding:hover {
            text-decoration: underline;
        }
        
        .controls-bar {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            background-color: rgba(25, 25, 45, 0.9);
        }
        
        .play-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #0ff7ef;
            color: #0a0a12;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 10px rgba(15, 247, 239, 0.5);
            font-size: 1rem;
        }
        
        .progress-container {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .progress-bar {
            flex: 1;
            height: 4px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            overflow: hidden;
            cursor: pointer;
        }
        
        .progress-fill {
            height: 100%;
            width: 0;
            background-color: #0ff7ef;
            border-radius: 2px;
            transition: width 0.1s linear;
        }
        
        .time {
            font-family: 'Arial', sans-serif;
            font-size: 0.75rem;
            color: #a0a0d0;
            min-width: 40px;
            text-align: center;
        }
        
        .volume-btn {
            background: none;
            border: none;
            color: #a0a0d0;
            cursor: pointer;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="embed-player">
        <div class="player-header">
            <div class="track-art">
                <img src="<?php echo URL_ROOT; ?>/public/img/track-art/<?php echo $data['track']->genre; ?>.jpg" alt="<?php echo $data['track']->title; ?>">
            </div>
            
            <div class="track-info">
                <div class="track-title"><?php echo $data['track']->title; ?></div>
                <div class="track-meta">
                    <span><?php echo ucfirst($data['track']->genre); ?></span>
                    <span>•</span>
                    <span><?php echo floor($data['track']->duration / 60) . ':' . str_pad($data['track']->duration % 60, 2, '0', STR_PAD_LEFT); ?></span>
                </div>
            </div>
            
            <a href="<?php echo URL_ROOT; ?>/music/share/<?php echo $data['track']->id; ?>" target="_blank" class="branding">
                Octaverum AI
            </a>
        </div>
        
        <div class="controls-bar">
            <button id="play-btn" class="play-btn">
                <i class="fas fa-play"></i>
            </button>
            
            <div class="progress-container">
                <span id="current-time" class="time">0:00</span>
                <div id="progress-bar" class="progress-bar">
                    <div id="progress-fill" class="progress-fill"></div>
                </div>
                <span id="total-time" class="time">0:00</span>
            </div>
            
            <button id="volume-btn" class="volume-btn">
                <i class="fas fa-volume-up"></i>
            </button>
        </div>
    </div>
    
    <script>
        // Simple player for embedded player
        document.addEventListener('DOMContentLoaded', function() {
            const playBtn = document.getElementById('play-btn');
            const progressBar = document.getElementById('progress-bar');
            const progressFill = document.getElementById('progress-fill');
            const currentTime = document.getElementById('current-time');
            const totalTime = document.getElementById('total-time');
            const volumeBtn = document.getElementById('volume-btn');
            
            const audio = new Audio('<?php echo $data['track']->audio_url; ?>');
            let isPlaying = false;
            
            // Initialize player
            audio.addEventListener('loadedmetadata', function() {
                const minutes = Math.floor(audio.duration / 60);
                const seconds = Math.floor(audio.duration % 60);
                totalTime.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            });
            
            // Update progress
            audio.addEventListener('timeupdate', function() {
                const progress = (audio.currentTime / audio.duration) * 100;
                progressFill.style.width = `${progress}%`;
                
                const minutes = Math.floor(audio.currentTime / 60);
                const seconds = Math.floor(audio.currentTime % 60);
                currentTime.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            });
            
            // Play/Pause toggle
            playBtn.addEventListener('click', function() {
                if (isPlaying) {
                    audio.pause();
                    playBtn.innerHTML = '<i class="fas fa-play"></i>';
                } else {
                    audio.play();
                    playBtn.innerHTML = '<i class="fas fa-pause"></i>';
                }
                
                isPlaying = !isPlaying;
            });
            
            // Seek functionality
            progressBar.addEventListener('click', function(e) {
                const rect = progressBar.getBoundingClientRect();
                const pos = (e.clientX - rect.left) / rect.width;
                audio.currentTime = pos * audio.duration;
            });
            
            // Volume toggle
            let isMuted = false;
            volumeBtn.addEventListener('click', function() {
                if (isMuted) {
                    audio.volume = 1;
                    volumeBtn.innerHTML = '<i class="fas fa-volume-up"></i>';
                } else {
                    audio.volume = 0;
                    volumeBtn.innerHTML = '<i class="fas fa-volume-mute"></i>';
                }
                
                isMuted = !isMuted;
            });
            
            // Track ended
            audio.addEventListener('ended', function() {
                isPlaying = false;
                playBtn.innerHTML = '<i class="fas fa-play"></i>';
                progressFill.style.width = '0%';
                audio.currentTime = 0;
            });
        });
    </script>
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <!-- Load Roboto font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</body>
</html>