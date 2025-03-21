<?php
/**
 * Music Controller
 * Handles music generation, library, and playlist functionality
 */
class Music extends Controller {
    private $userModel;
    private $musicModel;
    private $playlistModel;
    private $apiHelper;
    
    public function __construct() {
        // Check if user is logged in for most actions
        // Add share and embed to the public accessible routes
        if (!isLoggedIn() && !in_array($_GET['url'] ?? '', [
            'music/sample',
            'music/share',
            'music/embed',
            'music/download'
        ])) {
            redirect('users/login');
        }
        
        // Load models
        $this->userModel = $this->model('User');
        $this->musicModel = $this->model('MusicModel');
        $this->playlistModel = $this->model('Playlist');
        
        // Initialize API helper
        $this->apiHelper = new ApiHelper();
    }

    // Dashboard
    public function dashboard() {
        // Get user's recent tracks
        $recentTracks = $this->musicModel->getRecentTracks($_SESSION['user_id'], 6);
        
        // Get favorite tracks
        $favoriteTracks = $this->musicModel->getFavoriteTracks($_SESSION['user_id'], 6);
        
        // Get user's playlists
        $playlists = $this->playlistModel->getUserPlaylists($_SESSION['user_id']);
        
        // Get usage stats
        $usageStats = [
            'total_tracks' => $this->musicModel->countUserTracks($_SESSION['user_id']),
            'total_favorites' => $this->musicModel->countUserFavorites($_SESSION['user_id']),
            'total_playlists' => count($playlists),
            'monthly_generated' => $_SESSION['user_data']->monthly_generations ?? 0,
            'monthly_limit' => $_SESSION['user_data']->subscription_tier === 'professional' ? 'Unlimited' : 
                              ($_SESSION['user_data']->subscription_tier === 'premium' ? PREMIUM_GENERATION_LIMIT : FREE_GENERATION_LIMIT)
        ];
        
        $data = [
            'title' => 'Dashboard - Octaverum AI',
            'description' => 'Your Octaverum AI music dashboard',
            'recentTracks' => $recentTracks,
            'favoriteTracks' => $favoriteTracks,
            'playlists' => $playlists,
            'usageStats' => $usageStats,
            'user' => $_SESSION['user_data']
        ];
        
        $this->view('music/dashboard', $data);
    }

    // Generate new music
    public function generate() {
        // Check if user has reached monthly limit
        if (!checkGenerationLimit()) {
            flash('generation_error', 'You have reached your monthly generation limit. Upgrade your subscription to generate more music.', 'alert alert-danger');
            redirect('users/subscription');
        }
        
        // Check for POST
        if ($this->isPost()) {
            // Process form
            $data = $this->getSanitizedPostData();
            
            // Validate input
            $errors = [];
            
            // Validate prompt
            if (empty($data['prompt'])) {
                $errors['prompt'] = 'Please enter a prompt description';
            }
            
            // Make sure errors are empty
            if (empty($errors)) {
                // Prepare parameters
                $params = [
                    'genre' => $data['genre'] ?? 'electronic',
                    'bpm' => (int)($data['bpm'] ?? 120),
                    'key' => $data['key'] ?? 'C',
                    'duration' => (int)($data['duration'] ?? 60),
                    'quality' => $_SESSION['user_data']->subscription_tier === 'professional' ? 'studio' : 
                                ($_SESSION['user_data']->subscription_tier === 'premium' ? 'high' : 'standard'),
                    'title' => $data['title'] ?? 'Generated Track',
                    'use_async' => true // Mock asenkron API kullanımını aktif et
                ];
                
                // Prompt'a genre ve diğer bilgileri de ekleyelim
                $enhancedPrompt = $data['prompt'] . ' | Genre: ' . $params['genre'] . ' | BPM: ' . $params['bpm'] . ' | Key: ' . $params['key'];
                
                // Generate music using ApiHelper (mock implementation)
                $result = $this->apiHelper->generateMusic($enhancedPrompt, $params);
                
                if ($result['success']) {
                    // Check if we got a task ID (asynchronous API call)
                    if (isset($result['task_id'])) {
                        // Store task information in session for status checking
                        $_SESSION['pending_task'] = [
                            'task_id' => $result['task_id'],
                            'prompt' => $data['prompt'],
                            'params' => $params,
                            'title' => $data['title'] ?? 'Generated Track'
                        ];
                        
                        // Redirect to the task waiting page
                        redirect('music/waitForTask');
                    } else {
                        // Save track to database
                        $trackData = [
                            'user_id' => $_SESSION['user_id'],
                            'title' => $data['title'] ?? 'Generated Track',
                            'prompt' => $data['prompt'],
                            'audio_url' => $result['data']['audio_url'],
                            'parameters' => json_encode($params),
                            'genre' => $params['genre'],
                            'duration' => $params['duration'],
                            'bpm' => $params['bpm'],
                            'key' => $params['key']
                        ];
                        
                        $trackId = $this->musicModel->addTrack($trackData);
                        
                        if ($trackId) {
                            // Increment user's generation count
                            incrementGenerationCount();
                            $this->userModel->incrementGenerationCount($_SESSION['user_id']);
                            
                            // Redirect to the track page
                            redirect('music/track/' . $trackId);
                        } else {
                            die('Something went wrong saving the track');
                        }
                    }
                } else {
                    // API error
                    $errors['api'] = 'Error generating music: ' . ($result['error'] ?? 'Unknown error');
                    
                    $this->view('music/generate', [
                        'title' => 'Generate Music - Octaverum AI',
                        'description' => 'Create new music with AI.',
                        'errors' => $errors,
                        'prompt' => $data['prompt'],
                        'genre' => $data['genre'] ?? 'electronic',
                        'bpm' => $data['bpm'] ?? 120,
                        'key' => $data['key'] ?? 'C',
                        'duration' => $data['duration'] ?? 60,
                        'title' => $data['title'] ?? ''
                    ]);
                }
            } else {
                // Load view with errors
                $this->view('music/generate', [
                    'title' => 'Generate Music - Octaverum AI',
                    'description' => 'Create new music with AI.',
                    'errors' => $errors,
                    'prompt' => $data['prompt'] ?? '',
                    'genre' => $data['genre'] ?? 'electronic',
                    'bpm' => $data['bpm'] ?? 120,
                    'key' => $data['key'] ?? 'C',
                    'duration' => $data['duration'] ?? 60,
                    'title' => $data['title'] ?? ''
                ]);
            }
        } else {
            // Init data
            $data = [
                'title' => 'Generate Music - Octaverum AI',
                'description' => 'Create new music with AI.',
                'prompt' => '',
                'genre' => 'electronic',
                'bpm' => 120,
                'key' => 'C',
                'duration' => 60,
                'title' => '',
                'genres' => [
                    'electronic' => 'Electronic',
                    'pop' => 'Pop',
                    'rock' => 'Rock',
                    'hip-hop' => 'Hip Hop',
                    'jazz' => 'Jazz',
                    'classical' => 'Classical',
                    'ambient' => 'Ambient',
                    'folk' => 'Folk',
                    'metal' => 'Metal',
                    'funk' => 'Funk',
                    'blues' => 'Blues',
                    'country' => 'Country',
                    'reggae' => 'Reggae',
                    'cinematic' => 'Cinematic',
                    'experimental' => 'Experimental'
                ],
                'keys' => [
                    'C' => 'C Major',
                    'Cm' => 'C Minor',
                    'C#' => 'C# Major',
                    'C#m' => 'C# Minor',
                    'D' => 'D Major',
                    'Dm' => 'D Minor',
                    'D#' => 'D# Major',
                    'D#m' => 'D# Minor',
                    'E' => 'E Major',
                    'Em' => 'E Minor',
                    'F' => 'F Major',
                    'Fm' => 'F Minor',
                    'F#' => 'F# Major',
                    'F#m' => 'F# Minor',
                    'G' => 'G Major',
                    'Gm' => 'G Minor',
                    'G#' => 'G# Major',
                    'G#m' => 'G# Minor',
                    'A' => 'A Major',
                    'Am' => 'A Minor',
                    'A#' => 'A# Major',
                    'A#m' => 'A# Minor',
                    'B' => 'B Major',
                    'Bm' => 'B Minor'
                ],
                'durations' => [
                    30 => '30 seconds',
                    60 => '1 minute',
                    90 => '1.5 minutes',
                    120 => '2 minutes',
                    180 => '3 minutes',
                    240 => '4 minutes'
                ],
                'errors' => []
            ];
            
            // Load view
            $this->view('music/generate', $data);
        }
    }

    /**
     * Wait for PiAPI task to complete
     * Shows progress bar and checks task status via AJAX
     */
    public function waitForTask() {
        // Ensure task ID is available in session
        if (!isset($_SESSION['pending_task']) || !isset($_SESSION['pending_task']['task_id'])) {
            redirect('music/generate');
        }
        
        // Load view
        $this->view('music/wait_for_task');
    }
    
    /**
     * Görev durumunu kontrol et (AJAX çağrısı)
     * 
     * @param string $taskId Kontrol edilecek görev ID'si
     * @return void
     */
    public function checkTaskStatus($taskId = '')
    {
        // Başlıkları ayarla - her zaman JSON yanıt dönüyoruz
        header('Content-Type: application/json');
        
        try {
            // Gelen verileri logla
            error_log("checkTaskStatus çağrıldı, taskId: $taskId");
            
            // Görev ID'si boş ise hata döndür
            if (empty($taskId)) {
                return $this->outputJson([
                    'success' => false,
                    'error' => 'Görev ID\'si belirtilmedi'
                ]);
            }
            
            // API yardımcısını yükle
            $apiHelper = new ApiHelper();
            
            // Görev durumunu kontrol et
            $taskStatus = $apiHelper->checkPiApiTaskStatus($taskId);
            
            // Yanıtı logla
            error_log("Task durum yanıtı: " . json_encode($taskStatus));
            
            // Yanıtı döndür
            return $this->outputJson($taskStatus);
        } catch (Throwable $e) {
            // Hatayı logla
            error_log("checkTaskStatus hatası: " . $e->getMessage() . " - " . $e->getTraceAsString());
            
            // Hata durumunda JSON hata mesajı döndür
            return $this->outputJson([
                'success' => false,
                'error' => 'Sunucu hatası: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * JSON çıktısı oluştur
     * 
     * @param array $data JSON'a dönüştürülecek veri
     * @return void
     */
    private function outputJson($data)
    {
        try {
            // JSON'a dönüştür
            $json = json_encode($data, JSON_PRETTY_PRINT);
            
            // JSON dönüştürme hatası olursa işle
            if ($json === false) {
                error_log("JSON dönüştürme hatası: " . json_last_error_msg());
                echo json_encode([
                    'success' => false,
                    'error' => 'JSON dönüştürme hatası: ' . json_last_error_msg()
                ]);
                return;
            }
            
            // Başarılı JSON çıktısı
            echo $json;
        } catch (Throwable $e) {
            // Son çare olarak basit bir JSON döndür
            error_log("JSON çıktı hatası: " . $e->getMessage());
            echo '{"success":false,"error":"JSON işleme hatası"}';
        }
    }
    
    // Complete task and save to library
    public function taskComplete() {
        // Ensure task info is available in session
        if (!isset($_SESSION['pending_task']) || !isset($_SESSION['pending_task']['task_id'])) {
            redirect('music/generate');
        }
        
        // Get task ID and info
        $taskId = $_SESSION['pending_task']['task_id'];
        $prompt = $_SESSION['pending_task']['prompt'];
        $params = $_SESSION['pending_task']['params'];
        $title = $_SESSION['pending_task']['title'];
        
        // Get final task status
        $result = $this->apiHelper->checkTaskStatus($taskId);
        
        if ($result['success'] && $result['completed'] && isset($result['data'])) {
            // Prepare track data
            $trackData = [
                'user_id' => $_SESSION['user_id'],
                'title' => $title,
                'prompt' => $prompt,
                'audio_url' => $result['data']['audio_url'],
                'parameters' => json_encode($params),
                'genre' => $params['genre'] ?? 'electronic',
                'duration' => $params['duration'] ?? 60,
                'bpm' => $params['bpm'] ?? 120,
                'key' => $params['key'] ?? 'C'
            ];
            
            // Save track to database
            $trackId = $this->musicModel->addTrack($trackData);
            
            if ($trackId) {
                // Increment user's generation count
                incrementGenerationCount();
                $this->userModel->incrementGenerationCount($_SESSION['user_id']);
                
                // Clear pending task
                unset($_SESSION['pending_task']);
                
                // Redirect to the track page
                redirect('music/track/' . $trackId);
            } else {
                die('Parça veritabanına kaydedilirken hata oluştu');
            }
        } else {
            // Error handling
            flash('task_error', 'Müzik oluşturma işlemi tamamlanamadı: ' . ($result['error'] ?? 'Bilinmeyen hata'), 'alert alert-danger');
            redirect('music/generate');
        }
    }

    /**
     * Generate image with Flux
     * Uses PiAPI's Flux model to generate images
     */
    public function generateImage() {
        // Only available for premium and professional tiers
        if ($_SESSION['user_data']->subscription_tier === 'free') {
            flash('image_error', 'Image generation is only available for Premium and Professional subscriptions.', 'alert alert-danger');
            redirect('users/subscription');
        }
        
        // Check for POST
        if ($this->isPost()) {
            // Process form
            $data = $this->getSanitizedPostData();
            
            // Validate input
            $errors = [];
            
            // Validate prompt
            if (empty($data['prompt'])) {
                $errors['prompt'] = 'Please enter a prompt description';
            }
            
            // Make sure errors are empty
            if (empty($errors)) {
                // Additional params if needed
                $params = [];
                if (!empty($data['width']) && !empty($data['height'])) {
                    $params['width'] = (int)$data['width'];
                    $params['height'] = (int)$data['height'];
                }
                
                // Generate image with Flux
                $result = $this->apiHelper->generateImageWithFlux($data['prompt'], $params);
                
                if ($result['success']) {
                    // Store task information in session for status checking
                    $_SESSION['pending_image_task'] = [
                        'task_id' => $result['task_id'],
                        'prompt' => $data['prompt'],
                        'title' => $data['title'] ?? 'Generated Image'
                    ];
                    
                    // Redirect to the image task waiting page
                    redirect('music/waitForImageTask');
                } else {
                    // API error
                    $errors['api'] = 'Error generating image: ' . $result['error'];
                    
                    $this->view('music/generate_image', [
                        'title' => 'Generate Image - Octaverum AI',
                        'description' => 'Create images with AI.',
                        'errors' => $errors,
                        'prompt' => $data['prompt'],
                        'width' => $data['width'] ?? 512,
                        'height' => $data['height'] ?? 512,
                        'title' => $data['title'] ?? ''
                    ]);
                }
            } else {
                // Load view with errors
                $this->view('music/generate_image', [
                    'title' => 'Generate Image - Octaverum AI',
                    'description' => 'Create images with AI.',
                    'errors' => $errors,
                    'prompt' => $data['prompt'] ?? '',
                    'width' => $data['width'] ?? 512,
                    'height' => $data['height'] ?? 512,
                    'title' => $data['title'] ?? ''
                ]);
            }
        } else {
            // Init data
            $data = [
                'title' => 'Generate Image - Octaverum AI',
                'description' => 'Create images with AI.',
                'prompt' => '',
                'width' => 512,
                'height' => 512,
                'title' => '',
                'errors' => []
            ];
            
            // Load view
            $this->view('music/generate_image', $data);
        }
    }
    
    /**
     * Wait for PiAPI Image task to complete
     */
    public function waitForImageTask() {
        // Similar to waitForTask but for images
        // Implementation would be similar to waitForTask
        // with adjustments for image display
    }

    // Generate lyrics
    public function generateLyrics() {
        // Check if user has premium or professional subscription
        if ($_SESSION['user_data']->subscription_tier === 'free') {
            flash('lyrics_error', 'Lyrics generation is only available for Premium and Professional subscriptions.', 'alert alert-danger');
            redirect('users/subscription');
        }
        
        // Check for POST
        if ($this->isPost()) {
            // Process form
            $data = $this->getSanitizedPostData();
            
            // Validate input
            $errors = [];
            
            // Validate prompt
            if (empty($data['prompt'])) {
                $errors['prompt'] = 'Please enter a prompt description';
            }
            
            // Make sure errors are empty
            if (empty($errors)) {
                // Prepare parameters
                $params = [
                    'genre' => $data['genre'] ?? 'pop',
                    'structure' => $data['structure'] ?? 'verse,chorus,verse,chorus,bridge,chorus',
                    'language' => $data['language'] ?? 'en',
                    'style' => $data['style'] ?? 'modern'
                ];
                
                // Generate lyrics
                $result = $this->apiHelper->generateLyrics($data['prompt'], $params);
                
                if ($result['success']) {
                    // Redirect to display lyrics
                    $_SESSION['generated_lyrics'] = [
                        'lyrics' => $result['data']['lyrics'],
                        'title' => $data['title'] ?? 'Generated Lyrics',
                        'prompt' => $data['prompt'],
                        'parameters' => $params
                    ];
                    
                    redirect('music/lyrics');
                } else {
                    // API error
                    $errors['api'] = 'Error generating lyrics: ' . $result['error'];
                    
                    $this->view('music/generate_lyrics', [
                        'title' => 'Generate Lyrics - Octaverum AI',
                        'description' => 'Create lyrics with AI.',
                        'errors' => $errors,
                        'prompt' => $data['prompt'],
                        'genre' => $data['genre'] ?? 'pop',
                        'structure' => $data['structure'] ?? 'verse,chorus,verse,chorus,bridge,chorus',
                        'language' => $data['language'] ?? 'en',
                        'style' => $data['style'] ?? 'modern',
                        'title' => $data['title'] ?? ''
                    ]);
                }
            } else {
                // Load view with errors
                $this->view('music/generate_lyrics', [
                    'title' => 'Generate Lyrics - Octaverum AI',
                    'description' => 'Create lyrics with AI.',
                    'errors' => $errors,
                    'prompt' => $data['prompt'] ?? '',
                    'genre' => $data['genre'] ?? 'pop',
                    'structure' => $data['structure'] ?? 'verse,chorus,verse,chorus,bridge,chorus',
                    'language' => $data['language'] ?? 'en',
                    'style' => $data['style'] ?? 'modern',
                    'title' => $data['title'] ?? ''
                ]);
            }
        } else {
            // Init data
            $data = [
                'title' => 'Generate Lyrics - Octaverum AI',
                'description' => 'Create lyrics with AI.',
                'prompt' => '',
                'genre' => 'pop',
                'structure' => 'verse,chorus,verse,chorus,bridge,chorus',
                'language' => 'en',
                'style' => 'modern',
                'title' => '',
                'genres' => [
                    'pop' => 'Pop',
                    'rock' => 'Rock',
                    'hip-hop' => 'Hip Hop',
                    'jazz' => 'Jazz',
                    'folk' => 'Folk',
                    'metal' => 'Metal',
                    'country' => 'Country',
                    'reggae' => 'Reggae',
                    'blues' => 'Blues'
                ],
                'structures' => [
                    'verse,chorus,verse,chorus,bridge,chorus' => 'Standard',
                    'verse,chorus,verse,chorus,chorus' => 'Simple',
                    'verse,verse,bridge,verse' => 'Poetic',
                    'intro,verse,chorus,verse,chorus,outro' => 'Full Song',
                    'verse,pre-chorus,chorus,verse,pre-chorus,chorus,bridge,chorus' => 'Expanded'
                ],
                'languages' => [
                    'en' => 'English',
                    'tr' => 'Turkish',
                    'es' => 'Spanish',
                    'fr' => 'French',
                    'de' => 'German',
                    'it' => 'Italian',
                    'ja' => 'Japanese',
                    'ko' => 'Korean',
                    'zh' => 'Chinese'
                ],
                'styles' => [
                    'modern' => 'Modern',
                    'classic' => 'Classic',
                    'poetic' => 'Poetic',
                    'storytelling' => 'Storytelling',
                    'abstract' => 'Abstract',
                    'romantic' => 'Romantic',
                    'inspirational' => 'Inspirational'
                ],
                'errors' => []
            ];
            
            // Load view
            $this->view('music/generate_lyrics', $data);
        }
    }

    // Display generated lyrics
    public function lyrics() {
        // Check if lyrics are in session
        if (!isset($_SESSION['generated_lyrics'])) {
            redirect('music/generateLyrics');
        }
        
        $data = [
            'title' => 'Generated Lyrics - Octaverum AI',
            'description' => 'Your AI generated lyrics.',
            'lyrics' => $_SESSION['generated_lyrics']
        ];
        
        // Load view
        $this->view('music/lyrics', $data);
    }

    // View single track
    public function track($id = null) {
        if ($id === null) {
            redirect('music/dashboard');
        }
        
        // Get track details
        $track = $this->musicModel->getTrackById($id);
        
        // Make sure track exists and belongs to user
        if (!$track || $track->user_id != $_SESSION['user_id']) {
            redirect('music/dashboard');
        }
        
        // Get user's playlists for adding to playlist
        $playlists = $this->playlistModel->getUserPlaylists($_SESSION['user_id']);
        
        // Check if track is favorited
        $isFavorite = $this->musicModel->isTrackFavorite($_SESSION['user_id'], $id);
        
        $data = [
            'title' => $track->title . ' - Octaverum AI',
            'description' => 'Listen to your AI generated music.',
            'track' => $track,
            'isFavorite' => $isFavorite,
            'playlists' => $playlists,
            'parameters' => json_decode($track->parameters)
        ];
        
        $this->view('music/track', $data);
    }

    /**
     * Müzik kütüphanesi sayfasını gösterir
     * Sayfalama, tür filtresi ve sıralama özellikleri içerir
     */
    public function library() {
        // Sayfa parametresini al
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page); // Minimum sayfa 1 olmalı
        
        // Limit ve offset hesapla
        $limit = 12; // Sayfa başına 12 parça
        $offset = ($page - 1) * $limit;
        
        // Tür filtresi kontrolü
        $genre = isset($_GET['genre']) && $_GET['genre'] !== 'all' ? $_GET['genre'] : null;
        
        // Sıralama seçeneği
        $sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
        $validSortOptions = ['newest', 'oldest', 'title_asc', 'title_desc', 'duration_asc', 'duration_desc'];
        $sortBy = in_array($sortBy, $validSortOptions) ? $sortBy : 'newest';
        
        // Kullanıcının parçalarını getir
        $tracks = $this->musicModel->getUserTracksWithPagination($_SESSION['user_id'], $limit, $offset, $genre, $sortBy);
        
        // Toplam parça sayısını hesapla
        $totalTracks = $this->musicModel->countUserTracks($_SESSION['user_id'], $genre);
        
        // Toplam sayfa sayısını hesapla
        $totalPages = ceil($totalTracks / $limit);
        
        // Kullanıcı playlistlerini getir
        $playlists = $this->playlistModel->getUserPlaylists($_SESSION['user_id']);
        
        // Müzik türleri
        $genres = [
            'electronic' => 'Electronic',
            'pop' => 'Pop',
            'rock' => 'Rock',
            'hip-hop' => 'Hip Hop',
            'jazz' => 'Jazz',
            'classical' => 'Classical',
            'ambient' => 'Ambient',
            'folk' => 'Folk',
            'metal' => 'Metal',
            'funk' => 'Funk',
            'blues' => 'Blues',
            'country' => 'Country',
            'reggae' => 'Reggae',
            'cinematic' => 'Cinematic',
            'experimental' => 'Experimental'
        ];
        
        // Görünüm için veri
        $data = [
            'title' => 'Müzik Kütüphanesi - Octaverum AI',
            'description' => 'Tüm müzik parçalarını görüntüle ve yönet',
            'tracks' => $tracks,
            'totalTracks' => $totalTracks,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'selectedGenre' => $genre,
            'genres' => $genres,
            'sortBy' => $sortBy,
            'playlists' => $playlists
        ];
        
        $this->view('music/library', $data);
    }
    
    /**
     * Genre Page - Belirli bir türdeki parçaları göster
     *
     * @param string $genre Müzik türü
     * @return void
     */
    public function genre($genre = null) {
        if ($genre === null) {
            redirect('music/library');
        }
        
        // Kategori adını düzgün formata dönüştür
        $genreMapping = [
            'workout' => 'electronic', // Workout parçaları elektronik olarak kabul edelim
            'techno' => 'electronic',
            'quiet' => 'ambient',
            'rap' => 'hip-hop',
            'focus' => 'ambient',
            'beach' => 'electronic',
            'pop' => 'pop',
            'movie' => 'cinematic',
            'folk' => 'folk',
            'travel' => 'ambient',
            'kids' => 'pop',
            '80s' => 'pop'
        ];
        
        // Veritabanında aranan gerçek tür
        $dbGenre = $genreMapping[$genre] ?? $genre;
        
        // Kullanıcının bu türdeki parçalarını al
        $tracks = $this->musicModel->getTracksByGenre($_SESSION['user_id'], $dbGenre);
        
        // Kullanıcının favori parçalarını al
        $favoriteTracks = $this->musicModel->getFavoriteTracks($_SESSION['user_id']);
        
        // Kullanıcının çalma listelerini al 
        $playlists = $this->playlistModel->getUserPlaylists($_SESSION['user_id']);
        
        // Kategori başlıklarını format
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
            'classical' => 'Classical'
        ];
        
        $title = $genreTitles[$genre] ?? ucfirst($genre);
        
        $data = [
            'title' => $title . ' - Music Library - Octaverum AI',
            'description' => 'Browse your ' . $title . ' music.',
            'genre' => $genre,
            'genreName' => $title,
            'tracks' => $tracks,
            'favoriteTracks' => $favoriteTracks,
            'playlists' => $playlists
        ];
        
        $this->view('music/genre', $data);
    }

    // Playlists
    public function playlists() {
        // Get user's playlists
        $playlists = $this->playlistModel->getUserPlaylists($_SESSION['user_id']);
        
        $data = [
            'title' => 'Playlists - Octaverum AI',
            'description' => 'Manage your music playlists.',
            'playlists' => $playlists
        ];
        
        $this->view('music/playlists', $data);
    }

    // View single playlist
    public function playlist($id = null) {
        if ($id === null) {
            redirect('music/playlists');
        }
        
        // Get playlist details
        $playlist = $this->playlistModel->getPlaylistById($id);
        
        // Make sure playlist exists and belongs to user
        if (!$playlist || $playlist->user_id != $_SESSION['user_id']) {
            redirect('music/playlists');
        }
        
        // Get playlist tracks
        $tracks = $this->playlistModel->getPlaylistTracks($id);
        
        $data = [
            'title' => $playlist->name . ' - Octaverum AI',
            'description' => 'View your playlist.',
            'playlist' => $playlist,
            'tracks' => $tracks
        ];
        
        $this->view('music/playlist', $data);
    }

    // Create new playlist
    public function createPlaylist() {
        // Check for POST
        if ($this->isPost()) {
            // Process form
            $data = $this->getSanitizedPostData();
            
            // Validate input
            $errors = [];
            
            // Validate name
            if (empty($data['name'])) {
                $errors['name'] = 'Please enter a playlist name';
            }
            
            // Make sure errors are empty
            if (empty($errors)) {
                // Add playlist
                $playlistData = [
                    'user_id' => $_SESSION['user_id'],
                    'name' => $data['name'],
                    'description' => $data['description'] ?? ''
                ];
                
                if ($this->playlistModel->addPlaylist($playlistData)) {
                    flash('playlist_success', 'Playlist created successfully');
                    redirect('music/playlists');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('music/create_playlist', [
                    'title' => 'Create Playlist - Octaverum AI',
                    'description' => 'Create a new playlist.',
                    'errors' => $errors,
                    'name' => $data['name'] ?? '',
                    'description' => $data['description'] ?? ''
                ]);
            }
        } else {
            // Init data
            $data = [
                'title' => 'Create Playlist - Octaverum AI',
                'description' => 'Create a new playlist.',
                'name' => '',
                'description' => '',
                'errors' => []
            ];
            
            // Load view
            $this->view('music/create_playlist', $data);
        }
    }

    // Edit playlist
    public function editPlaylist($id = null) {
        if ($id === null) {
            redirect('music/playlists');
        }
        
        // Get playlist details
        $playlist = $this->playlistModel->getPlaylistById($id);
        
        // Make sure playlist exists and belongs to user
        if (!$playlist || $playlist->user_id != $_SESSION['user_id']) {
            redirect('music/playlists');
        }
        
        // Check for POST
        if ($this->isPost()) {
            // Process form
            $data = $this->getSanitizedPostData();
            
            // Validate input
            $errors = [];
            
            // Validate name
            if (empty($data['name'])) {
                $errors['name'] = 'Please enter a playlist name';
            }
            
            // Make sure errors are empty
            if (empty($errors)) {
                // Update playlist
                $playlistData = [
                    'id' => $id,
                    'name' => $data['name'],
                    'description' => $data['description'] ?? ''
                ];
                
                if ($this->playlistModel->updatePlaylist($playlistData)) {
                    flash('playlist_success', 'Playlist updated successfully');
                    redirect('music/playlist/' . $id);
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('music/edit_playlist', [
                    'title' => 'Edit Playlist - Octaverum AI',
                    'description' => 'Edit your playlist.',
                    'errors' => $errors,
                    'playlist' => $playlist
                ]);
            }
        } else {
            // Init data
            $data = [
                'title' => 'Edit Playlist - Octaverum AI',
                'description' => 'Edit your playlist.',
                'playlist' => $playlist,
                'errors' => []
            ];
            
            // Load view
            $this->view('music/edit_playlist', $data);
        }
    }

    // Delete playlist
    public function deletePlaylist($id = null) {
        if ($id === null) {
            redirect('music/playlists');
        }
        
        // Get playlist details
        $playlist = $this->playlistModel->getPlaylistById($id);
        
        // Make sure playlist exists and belongs to user
        if (!$playlist || $playlist->user_id != $_SESSION['user_id']) {
            redirect('music/playlists');
        }
        
        // Check for POST
        if ($this->isPost()) {
            // Delete playlist
            if ($this->playlistModel->deletePlaylist($id)) {
                flash('playlist_success', 'Playlist deleted successfully');
                redirect('music/playlists');
            } else {
                die('Something went wrong');
            }
        } else {
            // Init data
            $data = [
                'title' => 'Delete Playlist - Octaverum AI',
                'description' => 'Confirm playlist deletion.',
                'playlist' => $playlist
            ];
            
            // Load view
            $this->view('music/delete_playlist', $data);
        }
    }

    // Add track to playlist
    public function addToPlaylist() {
        // Check for POST
        if ($this->isPost()) {
            // Process form
            $data = $this->getSanitizedPostData();
            
            // Validate input
            if (empty($data['track_id']) || empty($data['playlist_id'])) {
                flash('playlist_error', 'Invalid track or playlist', 'alert alert-danger');
                redirect('music/track/' . $data['track_id']);
            }
            
            // Check if track and playlist belong to user
            $track = $this->musicModel->getTrackById($data['track_id']);
            $playlist = $this->playlistModel->getPlaylistById($data['playlist_id']);
            
            if (!$track || !$playlist || $track->user_id != $_SESSION['user_id'] || $playlist->user_id != $_SESSION['user_id']) {
                flash('playlist_error', 'Invalid track or playlist', 'alert alert-danger');
                redirect('music/track/' . $data['track_id']);
            }
            
            // Check if track is already in playlist
            if ($this->playlistModel->isTrackInPlaylist($data['track_id'], $data['playlist_id'])) {
                flash('playlist_error', 'Track is already in this playlist', 'alert alert-danger');
                redirect('music/track/' . $data['track_id']);
            }
            
            // Add track to playlist
            if ($this->playlistModel->addTrackToPlaylist($data['track_id'], $data['playlist_id'])) {
                flash('playlist_success', 'Track added to playlist');
                redirect('music/track/' . $data['track_id']);
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('music/dashboard');
        }
    }

    // Remove track from playlist
    public function removeFromPlaylist() {
        // Check for POST
        if ($this->isPost()) {
            // Process form
            $data = $this->getSanitizedPostData();
            
            // Validate input
            if (empty($data['track_id']) || empty($data['playlist_id'])) {
                flash('playlist_error', 'Invalid track or playlist', 'alert alert-danger');
                redirect('music/playlist/' . $data['playlist_id']);
            }
            
            // Check if playlist belongs to user
            $playlist = $this->playlistModel->getPlaylistById($data['playlist_id']);
            
            if (!$playlist || $playlist->user_id != $_SESSION['user_id']) {
                flash('playlist_error', 'Invalid playlist', 'alert alert-danger');
                redirect('music/playlists');
            }
            
            // Remove track from playlist
            if ($this->playlistModel->removeTrackFromPlaylist($data['track_id'], $data['playlist_id'])) {
                flash('playlist_success', 'Track removed from playlist');
                redirect('music/playlist/' . $data['playlist_id']);
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('music/playlists');
        }
    }

    // Toggle favorite track
    public function toggleFavorite($id = null) {
        if ($id === null) {
            redirect('music/dashboard');
        }
        
        // Get track details
        $track = $this->musicModel->getTrackById($id);
        
        // Make sure track exists and belongs to user
        if (!$track || $track->user_id != $_SESSION['user_id']) {
            redirect('music/dashboard');
        }
        
        // Check if track is already favorited
        $isFavorite = $this->musicModel->isTrackFavorite($_SESSION['user_id'], $id);
        
        if ($isFavorite) {
            // Remove from favorites
            if ($this->musicModel->removeFromFavorites($_SESSION['user_id'], $id)) {
                // AJAX request
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    echo json_encode(['success' => true, 'isFavorite' => false]);
                    exit;
                }
                
                flash('favorite_success', 'Track removed from favorites');
                redirect('music/track/' . $id);
            } else {
                die('Something went wrong');
            }
        } else {
            // Add to favorites
            if ($this->musicModel->addToFavorites($_SESSION['user_id'], $id)) {
                // AJAX request
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    echo json_encode(['success' => true, 'isFavorite' => true]);
                    exit;
                }
                
                flash('favorite_success', 'Track added to favorites');
                redirect('music/track/' . $id);
            } else {
                die('Something went wrong');
            }
        }
    }

    // Delete track
    public function deleteTrack($id = null) {
        if ($id === null) {
            redirect('music/dashboard');
        }
        
        // Get track details
        $track = $this->musicModel->getTrackById($id);
        
        // Make sure track exists and belongs to user
        if (!$track || $track->user_id != $_SESSION['user_id']) {
            redirect('music/dashboard');
        }
        
        // Check for POST
        if ($this->isPost()) {
            // Delete track
            if ($this->musicModel->deleteTrack($id)) {
                flash('track_success', 'Track deleted successfully');
                redirect('music/library');
            } else {
                die('Something went wrong');
            }
        } else {
            // Init data
            $data = [
                'title' => 'Delete Track - Octaverum AI',
                'description' => 'Confirm track deletion.',
                'track' => $track
            ];
            
            // Load view
            $this->view('music/delete_track', $data);
        }
    }

    // Edit track info
    public function editTrack($id = null) {
        if ($id === null) {
            redirect('music/dashboard');
        }
        
        // Get track details
        $track = $this->musicModel->getTrackById($id);
        
        // Make sure track exists and belongs to user
        if (!$track || $track->user_id != $_SESSION['user_id']) {
            redirect('music/dashboard');
        }
        
        // Check for POST
        if ($this->isPost()) {
            // Process form
            $data = $this->getSanitizedPostData();
            
            // Validate input
            $errors = [];
            
            // Validate title
            if (empty($data['title'])) {
                $errors['title'] = 'Please enter a track title';
            }
            
            // Make sure errors are empty
            if (empty($errors)) {
                // Update track
                $trackData = [
                    'id' => $id,
                    'title' => $data['title'],
                    'genre' => $data['genre'] ?? $track->genre
                ];
                
                if ($this->musicModel->updateTrack($trackData)) {
                    flash('track_success', 'Track updated successfully');
                    redirect('music/track/' . $id);
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('music/edit_track', [
                    'title' => 'Edit Track - Octaverum AI',
                    'description' => 'Edit your track information.',
                    'errors' => $errors,
                    'track' => $track
                ]);
            }
        } else {
            // Init data
            $data = [
                'title' => 'Edit Track - Octaverum AI',
                'description' => 'Edit your track information.',
                'track' => $track,
                'errors' => [],
                'genres' => [
                    'electronic' => 'Electronic',
                    'pop' => 'Pop',
                    'rock' => 'Rock',
                    'hip-hop' => 'Hip Hop',
                    'jazz' => 'Jazz',
                    'classical' => 'Classical',
                    'ambient' => 'Ambient',
                    'folk' => 'Folk',
                    'metal' => 'Metal',
                    'funk' => 'Funk',
                    'blues' => 'Blues',
                    'country' => 'Country',
                    'reggae' => 'Reggae',
                    'cinematic' => 'Cinematic',
                    'experimental' => 'Experimental'
                ]
            ];
            
            // Load view
            $this->view('music/edit_track', $data);
        }
    }

    // Play sample tracks for non-logged-in users
    public function sample() {
        $data = [
            'title' => 'Sample Tracks - Octaverum AI',
            'description' => 'Listen to sample AI-generated music tracks.',
            'samples' => [
                [
                    'id' => 1,
                    'title' => 'Electronic Dreams',
                    'genre' => 'Electronic',
                    'duration' => '1:45',
                    'audio_url' => URL_ROOT . '/public/audio/demo/sample_1.mp3'
                ],
                [
                    'id' => 2,
                    'title' => 'Ambient Journey',
                    'genre' => 'Ambient',
                    'duration' => '2:10',
                    'audio_url' => URL_ROOT . '/public/audio/demo/sample_2.mp3'
                ],
                [
                    'id' => 3,
                    'title' => 'Cinematic Horizon',
                    'genre' => 'Cinematic',
                    'duration' => '1:30',
                    'audio_url' => URL_ROOT . '/public/audio/demo/sample_3.mp3'
                ],
                [
                    'id' => 4,
                    'title' => 'Jazz Exploration',
                    'genre' => 'Jazz',
                    'duration' => '2:00',
                    'audio_url' => URL_ROOT . '/public/audio/demo/sample_4.mp3'
                ],
                [
                    'id' => 5,
                    'title' => 'Rock Energy',
                    'genre' => 'Rock',
                    'duration' => '1:55',
                    'audio_url' => URL_ROOT . '/public/audio/demo/sample_5.mp3'
                ]
            ]
        ];
        
        $this->view('music/sample', $data);
    }

    // Demo functionality has been removed
    
    /**
     * Share Track
     * Public page for sharing tracks with non-users
     *
     * @param int $id Track ID
     * @return void
     */
    public function share($id = null) {
        if ($id === null) {
            redirect('pages/index');
        }
        
        // Get track details
        $track = $this->musicModel->getTrackById($id);
        
        // Make sure track exists
        if (!$track) {
            redirect('pages/index');
        }
        
        // Get track owner details (limited info)
        $owner = $this->userModel->getUserById($track->user_id);
        
        // Increment share count
        $this->musicModel->incrementShareCount($id);
        
        $data = [
            'title' => $track->title . ' - Octaverum AI',
            'description' => 'Listen to AI-generated music: ' . $track->title,
            'track' => $track,
            'owner' => $owner ? (object)[
                'username' => $owner->username
            ] : null,
            'parameters' => json_decode($track->parameters)
        ];
        
        // Load public sharing view
        $this->view('music/share', $data);
    }
    
    /**
     * Embed Track
     * Embeddable player for external websites
     *
     * @param int $id Track ID
     * @return void
     */
    public function embed($id = null) {
        if ($id === null) {
            exit('Track not found');
        }
        
        // Get track details
        $track = $this->musicModel->getTrackById($id);
        
        // Make sure track exists
        if (!$track) {
            exit('Track not found');
        }
        
        // Increment embed count
        $this->musicModel->incrementEmbedCount($id);
        
        $data = [
            'track' => $track
        ];
        
        // Load minimal embed view (without header/footer)
        $this->view('music/embed', $data, false);
    }
    
    /**
     * Download Track
     * Handle track downloads with limits based on subscription tier
     *
     * @param int $id Track ID
     * @return void
     */
    public function download($id = null) {
        try {
            if ($id === null) {
                redirect('music/dashboard');
            }
            
            // Get track details
            $track = $this->musicModel->getTrackById($id);
            
            // Make sure track exists
            if (!$track) {
                flash('download_error', 'Track not found.', 'alert alert-danger');
                redirect('music/dashboard');
            }
            
            // Check if user is logged in
            if (isLoggedIn()) {
                // Check if track belongs to user
                $isOwner = ($track->user_id == $_SESSION['user_id']);
                
                // If not owner, check download limits based on tier
                if (!$isOwner) {
                    $monthlyLimit = $_SESSION['user_data']->subscription_tier === 'professional' ? 999 :
                                   ($_SESSION['user_data']->subscription_tier === 'premium' ? 20 : 5);
                    
                    $currentDownloads = $this->userModel->getMonthlyDownloads($_SESSION['user_id']);
                    
                    if ($currentDownloads >= $monthlyLimit) {
                        flash('download_error', 'You have reached your monthly download limit. Upgrade your subscription to download more tracks.', 'alert alert-danger');
                        redirect('music/track/' . $id);
                    }
                    
                    // Increment download count
                    $this->userModel->incrementDownloadCount($_SESSION['user_id']);
                }
            } else {
                // Redirect non-logged users to login
                redirect('users/login');
            }
            
            // Record download in database
            $this->musicModel->incrementDownloadCount($id);
            
            // Generate proper filename
            $filename = slugify($track->title) . '.mp3';
            
            // Debug audio URL
            debug_log("Audio URL for track {$id}", $track->audio_url);
            
            // Use the audio helper to get a safe audio path
            $fullPath = getSafeAudioPath($track->audio_url);
            
            if (!$fullPath) {
                debug_log("No audio file found for", $track->audio_url);
                flash('download_error', 'Audio file not found. Please try again later.', 'alert alert-danger');
                redirect('music/track/' . $id);
            }
            
            debug_log("Using audio file", $fullPath);
            
            // Set headers for download
            header('Content-Description: File Transfer');
            header('Content-Type: audio/mpeg');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fullPath));
            
            // Output file and exit
            readfile($fullPath);
            exit;
        } catch (Exception $e) {
            error_log("Download error for track {$id}: " . $e->getMessage());
            flash('download_error', 'An error occurred during download. Please try again.', 'alert alert-danger');
            redirect('music/track/' . $id);
        }
    }

    // Settings
    public function settings() {
        // Check for POST
        if ($this->isPost()) {
            // Process form
            $data = $this->getSanitizedPostData();
            
            // Update user preferences
            $updateData = [
                'id' => $_SESSION['user_id'],
                'theme' => $data['theme'] ?? 'dark',
                'player_visualization' => $data['player_visualization'] ?? 'waveform',
                'auto_play' => isset($data['auto_play']) ? 1 : 0,
                'language' => $data['language'] ?? 'en'
            ];
            
            if ($this->userModel->updatePreferences($updateData)) {
                // Update session data
                $_SESSION['user_data']->theme = $updateData['theme'];
                $_SESSION['user_data']->player_visualization = $updateData['player_visualization'];
                $_SESSION['user_data']->auto_play = $updateData['auto_play'];
                $_SESSION['user_data']->language = $updateData['language'];
                
                flash('settings_success', 'Settings updated successfully');
                redirect('music/settings');
            } else {
                die('Something went wrong');
            }
        } else {
            // Init data
            $data = [
                'title' => 'Settings - Octaverum AI',
                'description' => 'Customize your Octaverum AI experience.',
                'user' => $_SESSION['user_data'],
                'themes' => [
                    'dark' => 'Dark Cyberpunk',
                    'light' => 'Light Cyberpunk',
                    'neon' => 'Neon',
                    'retrowave' => 'Retrowave',
                    'minimal' => 'Minimal'
                ],
                'visualizations' => [
                    'waveform' => 'Waveform',
                    'bars' => 'Frequency Bars',
                    'circles' => 'Circular',
                    'particles' => 'Particles',
                    'none' => 'None'
                ],
                'languages' => getAvailableLanguages()
            ];
            
            // Load view
            $this->view('music/settings', $data);
        }
    }
}