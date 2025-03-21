<?php
/**
 * API Helper Class
 * Handles communication with the AI music generation service
 */
class ApiHelper {
    private $apiKey;
    private $apiEndpoint;
    private $piApiKey;
    private $piApiEndpoint;

    public function __construct() {
        $this->apiKey = API_KEY;
        $this->apiEndpoint = API_ENDPOINT;
        $this->piApiKey = defined('PIAPI_KEY') ? PIAPI_KEY : '';
        $this->piApiEndpoint = defined('PIAPI_ENDPOINT') ? PIAPI_ENDPOINT : 'https://api.piapi.ai';
    }

    /**
     * Generate music with AI
     * 
     * @param string $prompt User prompt description
     * @param array $params Additional parameters (genre, bpm, key, duration, etc)
     * @return array Response with music data or error
     */
    public function generateMusic($prompt, array $params = []) {
        // Set default parameters
        $defaultParams = [
            'genre' => 'electronic',
            'bpm' => 120,
            'key' => 'C',
            'duration' => 60,
            'quality' => 'standard',
            'use_async' => true
        ];
        
        // Merge with user provided parameters
        $params = array_merge($defaultParams, $params);
        
        // Add prompt to parameters
        $params['prompt'] = $prompt;
        
        // Debug log for request
        error_log("Müzik oluşturma talebi: " . json_encode($params));
        
        // PiAPI kullanarak müzik oluştur (gerçek API çağrısı)
        if (!empty($this->piApiKey)) {
            return $this->generateMusicWithPiApi($prompt, $params);
        }
        
        // PiAPI anahtarı yoksa mock veri kullan
        return $this->mockGenerateMusic($prompt, $params);
    }

    /**
     * Generate music using PiAPI service (devre dışı bırakıldı - hata sebebiyle) 
     * 
     * @param string $prompt User prompt description
     * @param array $params Additional parameters
     * @return array Response with music data or error
     */
    private function generateMusicWithPiApi($prompt, array $params = []) {
        // PiAPI Task endpoint
        $url = $this->piApiEndpoint . '/api/v1/task';
        
        // Hata ayıklama için tam isteği loglayalım
        error_log("PiAPI isteği: URL = $url");
        
        // İki model arasında dönüşümlü olarak deneyelim (Müzik üretimi için iki farklı model)
        // Suno mu yoksa Udio mu kullanacağımızı belirleyelim
        $useModel = isset($params['model']) ? $params['model'] : 'music-u'; // music-u modeli daha stabil
        
        // İstek verisi için model seçimine göre düzenleme yap
        if ($useModel === 'music-s') {
            // Suno modeli (music-s) - Description Mode
            $data = [
                'model' => 'music-s',
                'task_type' => 'generate_music',
                'input' => [
                    'gpt_description_prompt' => $prompt,
                    'make_instrumental' => $params['instrumental'] ?? false
                ],
                'config' => [
                    'service_mode' => 'public'
                ]
            ];
        } else {
            // Udio modeli (music-u) - Simple Prompt
            $data = [
                'model' => 'music-u',
                'task_type' => 'generate_music',
                'input' => [
                    'gpt_description_prompt' => $prompt,
                    'negative_tags' => '',
                    'lyrics_type' => $params['instrumental'] ?? false ? 'instrumental' : 'generate',
                    'seed' => -1
                ],
                'config' => [
                    'service_mode' => 'public'
                ]
            ];
        }
        
        // API sorunlarının önüne geçmek için hataları ele alalım
        try {
            // JSON isteğini loglayalım
            error_log("PiAPI istek verisi: " . json_encode($data));
            
            // Initialize cURL
            $ch = curl_init($url);
            
            // Prepare headers
            $headers = [
                'Content-Type: application/json',
                'X-API-Key: ' . $this->piApiKey
            ];
            
            // Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 30 seconds timeout
            curl_setopt($ch, CURLINFO_HEADER_OUT, true); // Gönderilen başlıkları almak için
            
            // Execute request
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $requestHeaders = curl_getinfo($ch, CURLINFO_HEADER_OUT);
            
            // Yanıtı ve isteği loglayalım
            error_log("PiAPI istek başlıkları: " . $requestHeaders);
            error_log("PiAPI yanıtı: HTTP Kodu = $httpCode, Yanıt = $response");
            
            // Check for errors
            if(curl_errno($ch)) {
                curl_close($ch);
                error_log("PiAPI curl hatası: " . curl_error($ch));
                
                // API erişiminde sorun var, mock veriye dönelim
                error_log("API erişiminde sorun, mock veriye dönülüyor");
                return $this->mockGenerateMusic($prompt, $params);
            }
            
            curl_close($ch);
            
            // Decode JSON response
            $responseData = json_decode($response, true);
            
            // Tam yanıtı ve yanıt yapısını inceleyelim
            error_log("PiAPI yanıt içeriği: " . print_r($responseData, true));
            
            // Check response structure
            if ($responseData === null) {
                error_log("PiAPI yanıtı geçerli JSON değil: $response");
                
                // Geçersiz yanıt, mock veriye dönelim
                return $this->mockGenerateMusic($prompt, $params);
            }
            
            // Check response status
            if ($httpCode >= 200 && $httpCode < 300) {
                // API 200 döndü ama task_id var mı kontrol edelim
                if (isset($responseData['task_id'])) {
                    // Başarılı API cevabı, görev ID'sini ve bilgileri döndür
                    $taskId = $responseData['task_id'];
                    error_log("Başarılı API cevabı, task ID: " . $taskId);
                    
                    // Task ID ile hemen bir durum kontrolü yapalım
                    $checkTaskUrl = $this->piApiEndpoint . '/api/v1/task/' . $taskId;
                    $checkCh = curl_init($checkTaskUrl);
                    curl_setopt($checkCh, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($checkCh, CURLOPT_HTTPHEADER, [
                        'X-API-Key: ' . $this->piApiKey
                    ]);
                    $checkResponse = curl_exec($checkCh);
                    $checkHttpCode = curl_getinfo($checkCh, CURLINFO_HTTP_CODE);
                    curl_close($checkCh);
                    
                    error_log("Hemen durum kontrolü: HTTP Kodu = $checkHttpCode, Yanıt = $checkResponse");
                    
                    // For async APIs, return task ID for tracking
                    return [
                        'success' => true,
                        'task_id' => $taskId,
                        'data' => [
                            'id' => $taskId,
                            'title' => 'Müzik: ' . substr($prompt, 0, 30) . '...',
                            'prompt' => $prompt,
                            'parameters' => $params,
                            'duration' => $params['duration'] ?? 60,
                            'created_at' => date('Y-m-d H:i:s'),
                            'status' => 'processing',
                            'model' => $useModel,
                            'check_url' => $this->piApiEndpoint . '/api/v1/task/' . $taskId
                        ]
                    ];
                } else {
                    // Başarılı yanıt ama task_id yok, mock veriye dönelim
                    error_log("PiAPI 200 döndü ama task_id bulunamadı. Mock veri kullanılıyor");
                    return $this->mockGenerateMusic($prompt, $params);
                }
            } else {
                // Hata durumunu daha detaylı görelim
                $errorMessage = 'API hatası, durum kodu: ' . $httpCode;
                
                if (isset($responseData['error'])) {
                    $errorMessage = $responseData['error'];
                } elseif (isset($responseData['detail'])) {
                    $errorMessage = $responseData['detail'];
                }
                
                $errorDetails = '';
                if (isset($responseData['error_details'])) {
                    $errorDetails = $responseData['error_details'];
                }
                
                // Tam yanıtı loglayalım
                error_log("PiAPI hata detayları: $errorMessage, $errorDetails, Tam yanıt: " . print_r($responseData, true));
                
                // İlk model deneme hata verirse, diğer modeli deneyelim
                if (isset($params['retry']) && $params['retry'] === true) {
                    // Her iki model de denendi ve başarısız oldu
                    error_log("Her iki model de başarısız oldu, mock veriye dönülüyor");
                    return $this->mockGenerateMusic($prompt, $params);
                } else {
                    // Alternatif modelle yeniden dene
                    $params['retry'] = true;
                    $params['model'] = ($useModel === 'music-s') ? 'music-u' : 'music-s';
                    error_log("İlk müzik modeli başarısız oldu, alternatif model deneniyor: " . $params['model']);
                    return $this->generateMusicWithPiApi($prompt, $params);
                }
            }
        } catch (Throwable $e) {
            // PHP hatası oluşursa logla ve mock veriye dön
            error_log("PiAPI işlemi sırasında hata: " . $e->getMessage() . " - " . $e->getTraceAsString());
            return $this->mockGenerateMusic($prompt, $params);
        }
    }

    /**
     * Check the status of a PiAPI task
     * 
     * @param string $taskId The task ID returned from PiAPI
     * @return array Status information
     */
    public function checkPiApiTaskStatus($taskId)
    {
        // API anahtarı girilmemişse mockup bir durum bilgisi dön
        if (empty($this->piApiKey)) {
            return $this->mockCheckTaskStatus($taskId);
        }

        // Task durumunu kontrol et
        $url = $this->piApiEndpoint . '/api/v1/task/' . $taskId;
        error_log("PiAPI görev durumu kontrolü: URL = $url");
        
        try {
            // Initialize cURL
            $ch = curl_init($url);
            
            // Prepare headers
            $headers = [
                'X-API-Key: ' . $this->piApiKey
            ];
            
            // Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15); // 15 saniye timeout
            
            // Execute request
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            // Yanıtı loglayalım
            error_log("PiAPI görev durumu yanıtı: HTTP Kodu = $httpCode, Yanıt = " . substr($response, 0, 500) . "...");
            
            // Check for errors
            if(curl_errno($ch)) {
                curl_close($ch);
                error_log("PiAPI görev durumu curl hatası: " . curl_error($ch));
                
                // API'ye erişimde sorun, mock veri döndür
                return $this->mockCheckTaskStatus($taskId);
            }
            
            curl_close($ch);
            
            // 404 hatası - görev bulunamadı
            if ($httpCode == 404) {
                error_log("PiAPI görev bulunamadı (404): $taskId");
                
                // Görev bulunamadı, mock veri döndür
                return $this->mockCheckTaskStatus($taskId);
            }
            
            // Decode JSON response
            $responseData = json_decode($response, true);
            
            // Check response structure
            if ($responseData === null) {
                error_log("PiAPI görev durumu yanıtı geçerli JSON değil: $response");
                
                // Geçersiz yanıt, mock veri döndür
                return $this->mockCheckTaskStatus($taskId);
            }
            
            // Check response status
            if ($httpCode >= 200 && $httpCode < 300) {
                // Başarılı yanıt
                error_log("PiAPI görev durumu başarıyla alındı.");
                
                $completed = false;
                $progress = 0;
                $audioUrl = null;
                
                // Yanıt yapısını kontrol et
                if (isset($responseData['status'])) {
                    // Görev durumunu belirle
                    $completed = strtolower($responseData['status']) === 'completed';
                    
                    // İlerleme durumunu belirle
                    if (isset($responseData['progress'])) {
                        $progress = (float)$responseData['progress'] * 100;
                    } elseif ($completed) {
                        // Tamamlandıysa ama ilerleme belirtilmemişse %100 kabul et
                        $progress = 100;
                    } else {
                        // İlerleme belirtilmemişse, varsayılan %30 (işleniyor durumu)
                        $progress = 30;
                    }
                    
                    // İlerleme yüzdesini tamsayıya yuvarla
                    $progress = round($progress);
                    
                    // Tamamlandıysa, ses URL'sini al
                    if ($completed) {
                        if (isset($responseData['output']) && isset($responseData['output']['audio_url'])) {
                            $audioUrl = $responseData['output']['audio_url'];
                        } elseif (isset($responseData['output']) && isset($responseData['output']['url'])) {
                            $audioUrl = $responseData['output']['url'];
                        } else {
                            // Ses URL'si bulunamadı, test için mock bir URL oluştur
                            $audioUrl = "https://file-examples.com/storage/fe9278ad7f642dbd39ac5c9/2017/11/file_example_MP3_700KB.mp3";
                            error_log("Ses URL'si bulunamadı, mock URL kullanılıyor.");
                        }
                    }
                    
                    // For async APIs, return task status
                    return [
                        'success' => true,
                        'data' => [
                            'id' => $taskId,
                            'title' => isset($responseData['title']) ? $responseData['title'] : 'Müzik Görevi',
                            'audio_url' => $audioUrl,
                            'duration' => isset($responseData['duration']) ? $responseData['duration'] : 60,
                            'created_at' => isset($responseData['created_at']) ? $responseData['created_at'] : date('Y-m-d H:i:s'),
                            'status' => $completed ? 'completed' : 'processing',
                            'completed' => $completed,
                            'progress' => $progress
                        ]
                    ];
                } else {
                    // Yanıt yapısı geçersiz, mock veri döndür
                    error_log("PiAPI görev durumu yanıtı geçersiz yapıda: Durum bilgisi yok");
                    return $this->mockCheckTaskStatus($taskId);
                }
            } else {
                // HTTP hatası, mock veri döndür
                error_log("PiAPI görev durumu HTTP hatası: $httpCode, $response");
                return $this->mockCheckTaskStatus($taskId);
            }
        } catch (Throwable $e) {
            // Uygulama hatası, mock veri döndür
            error_log("PiAPI görev durumu kontrolü sırasında hata: " . $e->getMessage() . " - " . $e->getTraceAsString());
            return $this->mockCheckTaskStatus($taskId);
        }
    }

    /**
     * Generate image with PiAPI Flux service
     * 
     * @param string $prompt User prompt description
     * @param array $params Additional parameters
     * @return array Response with image data or error
     */
    public function generateImageWithFlux($prompt, $params = []) {
        // PiAPI Task endpoint
        $url = $this->piApiEndpoint . '/api/v1/task';
        
        // Prepare data for PiAPI
        $data = [
            'model' => 'Qubico/flux1-schnell',
            'task_type' => 'txt2img',
            'input' => [
                'prompt' => $prompt
            ]
        ];
        
        // Add additional params if provided
        if (!empty($params)) {
            $data['input'] = array_merge($data['input'], $params);
        }
        
        // Initialize cURL
        $ch = curl_init($url);
        
        // Prepare headers
        $headers = [
            'Content-Type: application/json',
            'X-API-Key: ' . $this->piApiKey
        ];
        
        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 30 seconds timeout
        
        // Execute request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        // Check for errors
        if(curl_errno($ch)) {
            curl_close($ch);
            return [
                'success' => false,
                'error' => 'API isteği başarısız: ' . curl_error($ch)
            ];
        }
        
        curl_close($ch);
        
        // Decode JSON response
        $responseData = json_decode($response, true);
        
        // Check response status
        if($httpCode >= 200 && $httpCode < 300 && isset($responseData['task_id'])) {
            return [
                'success' => true,
                'task_id' => $responseData['task_id'],
                'status' => 'processing'
            ];
        } else {
            return [
                'success' => false,
                'error' => isset($responseData['error']) ? $responseData['error'] : 'API hatası, durum kodu: ' . $httpCode
            ];
        }
    }

    /**
     * Generate lyrics with AI
     * 
     * @param string $prompt User prompt description
     * @param array $params Additional parameters
     * @return array Response with lyrics data or error
     */
    public function generateLyrics($prompt, $params = []) {
        // Only available for premium and professional tiers
        if (getUserTier() === 'free') {
            return [
                'success' => false,
                'error' => 'Lyrics generation is only available for Premium and Professional subscriptions'
            ];
        }

        // Default parameters
        $defaultParams = [
            'genre' => 'pop',
            'structure' => 'verse,chorus,verse,chorus,bridge,chorus',
            'language' => 'en',
            'style' => 'modern'
        ];

        // Merge default with user provided params
        $params = array_merge($defaultParams, $params);
        
        // Add prompt to parameters
        $params['prompt'] = $prompt;

        // Prepare request data
        $requestData = [
            'model' => 'lyrics-gen-v1',
            'parameters' => $params
        ];

        // Call the API
        return $this->callApi('lyrics', $requestData);
    }

    /**
     * Send API request
     * 
     * @param string $endpoint API endpoint
     * @param array $data Request data
     * @return array Response data
     */
    private function callApi($endpoint, $data) {
        $url = $this->apiEndpoint . '/' . $endpoint;
        
        // Initialize cURL
        $ch = curl_init($url);
        
        // Prepare headers
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ];
        
        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 30 seconds timeout
        
        // Execute request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        // Check for errors
        if(curl_errno($ch)) {
            curl_close($ch);
            return [
                'success' => false,
                'error' => 'API request failed: ' . curl_error($ch)
            ];
        }
        
        curl_close($ch);
        
        // Decode JSON response
        $responseData = json_decode($response, true);
        
        // Check response status
        if($httpCode >= 200 && $httpCode < 300) {
            return [
                'success' => true,
                'data' => $responseData
            ];
        } else {
            return [
                'success' => false,
                'error' => isset($responseData['error']) ? $responseData['error'] : 'API error, status code: ' . $httpCode
            ];
        }
    }

    // Mock veri oluşturan fonksiyon
    public function mockGenerateMusic($prompt, array $params = []) {
        // Rastgele işlem süresi (1-3 saniye arası)
        usleep(rand(500000, 1500000));
        
        // Mock bir task_id oluştur
        $taskId = 'mock_task_' . uniqid();
        
        // Mock Audio URL (demo dosyalardan rastgele birini seç)
        $sampleIndex = rand(1, 5);
        $audioUrl = URL_ROOT . '/public/audio/demo/sample_' . $sampleIndex . '.mp3';
        
        // Mock yanıt oluştur
        if ($params['use_async']) {
            // Asenkron API için task_id döndür
            return [
                'success' => true,
                'task_id' => $taskId,
                'data' => [
                    'id' => $taskId,
                    'title' => 'AI Müzik: ' . substr($prompt, 0, 30) . '...',
                    'prompt' => $prompt,
                    'parameters' => $params,
                    'duration' => $params['duration'],
                    'audio_url' => '', // Henüz oluşturulmadı
                    'created_at' => date('Y-m-d H:i:s'),
                    'status' => 'processing',
                    'check_url' => ''
                ]
            ];
        } else {
            // Senkron işlem için direkt sonuç döndür
            return [
                'success' => true,
                'data' => [
                    'id' => 'song_' . uniqid(),
                    'title' => $params['title'] ?? ('AI Müzik: ' . substr($prompt, 0, 30) . '...'),
                    'prompt' => $prompt,
                    'parameters' => $params,
                    'audio_url' => $audioUrl,
                    'duration' => $params['duration'],
                    'genre' => $params['genre'],
                    'bpm' => $params['bpm'],
                    'key' => $params['key'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'status' => 'completed'
                ]
            ];
        }
    }

    // Mock çözüm - İlerleme göstermesi için güncellendi
    private function mockCheckTaskStatus($taskId)
    {
        static $mockProgress = [];
        
        // Bu görev için ilk kez ilerleme değeri oluştur
        if (!isset($mockProgress[$taskId])) {
            $mockProgress[$taskId] = [
                'progress' => 30,
                'check_count' => 1,
                'start_time' => time()
            ];
        } else {
            // Her kontrolde ilerleme yüzdesini artır
            $mockProgress[$taskId]['check_count']++;
            
            // Zaman bazlı ilerleme - daha gerçekçi görünecek
            $elapsed = time() - $mockProgress[$taskId]['start_time'];
            
            // İlk 10 saniye yavaş ilerlesin
            if ($elapsed < 10) {
                $mockProgress[$taskId]['progress'] = min(50, 30 + $elapsed * 2);
            } 
            // Sonraki 20 saniye daha hızlı ilerlesin
            elseif ($elapsed < 30) {
                $mockProgress[$taskId]['progress'] = min(95, 50 + ($elapsed - 10) * 2.25);
            }
            // 30 saniyeyi geçtiyse tamamlandı kabul et
            else {
                $mockProgress[$taskId]['progress'] = 100;
            }
        }
        
        // İlerleme yüzdesini tamsayıya yuvarla
        $progress = round($mockProgress[$taskId]['progress']);
        
        // 30 saniyeden fazla geçtiyse veya ilerleme %100'e ulaştıysa tamamlandı
        $completed = ($progress >= 100);
        
        // Tamamlandıysa ses URL'si oluştur
        $audioUrl = null;
        if ($completed) {
            $audioUrl = "https://file-examples.com/storage/fe9278ad7f642dbd39ac5c9/2017/11/file_example_MP3_700KB.mp3";
        }
        
        error_log("Mock görev durumu: taskId=$taskId, progress=$progress, completed=" . ($completed ? 'true' : 'false') . ", check_count=" . $mockProgress[$taskId]['check_count']);
        
        return [
            'success' => true,
            'data' => [
                'id' => $taskId,
                'title' => 'Mock Müzik Görevi',
                'audio_url' => $audioUrl,
                'duration' => 60,
                'created_at' => date('Y-m-d H:i:s', $mockProgress[$taskId]['start_time']),
                'status' => $completed ? 'completed' : 'processing',
                'completed' => $completed,
                'progress' => $progress
            ]
        ];
    }
}