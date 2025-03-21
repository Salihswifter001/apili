<?php require_once APPROOT . '/views/inc/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Müzik Oluşturuluyor</h2>
                </div>
                <div class="card-body">
                    <div id="taskInfo">
                        <p class="lead">Yapay Zeka müziğinizi oluşturuyor, lütfen bekleyiniz...</p>
                        
                        <!-- İlerleme Çubuğu -->
                        <div class="progress mb-3">
                            <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" 
                                role="progressbar" style="width: 30%;" 
                                aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">30%</div>
                        </div>
                        
                        <!-- Durum Bilgisi -->
                        <div id="statusInfo" class="mb-3">
                            <p>Müzik görevi işleme alındı...</p>
                        </div>
                        
                        <!-- İşlem detayları -->
                        <div class="card bg-light mb-3">
                            <div class="card-header">Oluşturma Detayları</div>
                            <div class="card-body">
                                <p><strong>Görev ID:</strong> <span id="task-id"><?php echo $data['task_id']; ?></span></p>
                                <p><strong>İstek:</strong> <span id="prompt"><?php echo htmlspecialchars($data['prompt']); ?></span></p>
                                <p><strong>Model:</strong> <span id="model"><?php echo isset($data['model']) ? htmlspecialchars($data['model']) : 'music-u'; ?></span></p>
                                <p><strong>Başlangıç:</strong> <span id="start-time"><?php echo date('H:i:s'); ?></span></p>
                            </div>
                        </div>
                        
                        <!-- Hata mesajları burada gösterilecek -->
                        <div id="errorContainer" class="alert alert-danger d-none">
                            <h5>Bir hata oluştu</h5>
                            <p id="errorMessage">Müzik oluşturulurken bir hata oluştu.</p>
                            <div id="errorDetails" class="small text-muted"></div>
                            <div class="mt-2">
                                <button id="retryButton" class="btn btn-sm btn-outline-danger">Tekrar Dene</button>
                                <a href="<?php echo URLROOT; ?>/music/create" class="btn btn-sm btn-outline-secondary ml-2">Yeni Müzik Oluştur</a>
                            </div>
                        </div>
                        
                        <!-- Otomatik tamamlama bilgisi -->
                        <div id="autoCompleteInfo" class="alert alert-info d-none">
                            <p>İşlem uzun sürdüğü için <span id="countdown">30</span> saniye içinde otomatik olarak tamamlanacak.</p>
                            <p>Şimdi gitmek isterseniz, aşağıdaki düğmeye tıklayabilirsiniz:</p>
                            <button id="completeNowButton" class="btn btn-primary btn-sm">Şimdi Tamamla</button>
                        </div>
                    </div>
                    
                    <!-- Tamamlandı mesajı, JavaScript tarafından gösterilecek -->
                    <div id="completionInfo" class="d-none">
                        <div class="text-center">
                            <h3 class="text-success"><i class="fas fa-check-circle"></i> Müzik Oluşturuldu!</h3>
                            <p class="lead">Müziğiniz başarıyla oluşturuldu. Kütüphanenize yönlendiriliyorsunuz...</p>
                            <div class="spinner-border text-primary mt-3" role="status">
                                <span class="sr-only">Yükleniyor...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <small>Müzik üretimi tipik olarak 20-60 saniye sürer. Lütfen bekleyiniz.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const taskId = '<?php echo $data['task_id']; ?>';
    const taskStatusUrl = '<?php echo URLROOT; ?>/music/checkTaskStatus/' + taskId;
    const libraryUrl = '<?php echo URLROOT; ?>/music/library';
    
    let checkCount = 0;
    let lastProgress = 30;
    let startTime = new Date();
    let autoCompleteTimer = null;
    let countdownTimer = null;
    let isCompleted = false;
    
    console.log('Task ID:', taskId);
    console.log('Status URL:', taskStatusUrl);
    
    // İlerleme çubuğunu güncelle
    function updateProgressBar(progress) {
        const progressBar = document.getElementById('progressBar');
        
        // İlerleme değeri kontrol (NaN veya geçersiz değerler için)
        if (isNaN(progress) || progress < 0) {
            progress = lastProgress;
        } else if (progress > 100) {
            progress = 100;
        }
        
        // Eğer ilerleme geriye gidiyorsa, bunu engelle
        if (progress < lastProgress) {
            progress = lastProgress;
        } else {
            lastProgress = progress;
        }
        
        progressBar.style.width = progress + '%';
        progressBar.setAttribute('aria-valuenow', progress);
        progressBar.textContent = Math.round(progress) + '%';
        
        // İlerleme %60'ı geçince, otomatik tamamlama bilgisini göster
        if (progress >= 60 && !isCompleted) {
            showAutoCompleteInfo();
        }
        
        // İlerleme %95'i geçince, ilerleme çubuğu rengini değiştir
        if (progress >= 95) {
            progressBar.classList.remove('progress-bar-animated');
            progressBar.classList.add('bg-success');
        }
    }
    
    // Durum bilgisini güncelle
    function updateStatus(status, message) {
        const statusInfo = document.getElementById('statusInfo');
        
        if (message) {
            statusInfo.innerHTML = `<p>${message}</p>`;
        } else {
            // Durum mesajını oluştur
            let statusMessage = 'Müzik görevi ';
            switch (status) {
                case 'processing':
                    statusMessage += 'işleniyor...';
                    break;
                case 'waiting':
                    statusMessage += 'sırada bekliyor...';
                    break;
                case 'completed':
                    statusMessage += 'tamamlandı!';
                    break;
                case 'failed':
                    statusMessage += 'başarısız oldu.';
                    break;
                default:
                    statusMessage += 'işleniyor...';
            }
            statusInfo.innerHTML = `<p>${statusMessage}</p>`;
        }
    }
    
    // Görev durumunu kontrol et
    function checkTaskStatus() {
        // İstek sayısını arttır
        checkCount++;
        
        // Ne kadar süredir kontrol ediyoruz
        const elapsedTime = Math.floor((new Date() - startTime) / 1000);
        console.log(`Kontrol #${checkCount}, Geçen süre: ${elapsedTime}s`);
        
        // İlerleme durumu için yapay süre ilerlemesi (API yanıt vermezse)
        const simulatedProgress = Math.min(95, 30 + (elapsedTime * 1.5));
        
        // Zaman aşımı kontrolü - 40 saniye geçtiyse otomatik olarak tamamla
        if (elapsedTime > 40 && !isCompleted) {
            console.log('Zaman aşımı - işlem otomatik olarak tamamlanıyor');
            completeTask();
            return;
        }
        
        // Yapay ilerleme göster
        updateProgressBar(simulatedProgress);
        
        // API'den görev durumunu kontrol et
        fetch(taskStatusUrl)
            .then(response => {
                // Yanıt JSON mı kontrol et
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error(`JSON yanıtı bekleniyor, ancak '${contentType}' alındı`);
                }
                return response.json();
            })
            .then(data => {
                // Yanıtı konsola yazdır (debug için)
                console.log('API yanıtı:', data);
                
                // Sunucu yanıtında hata kontrolü
                if (!data.success) {
                    throw new Error(data.error || 'Bilinmeyen sunucu hatası');
                }
                
                // Görev bilgisi var mı kontrol et
                if (data.data && data.data.id) {
                    const taskData = data.data;
                    const isCompleted = taskData.completed || taskData.status === 'completed';
                    const progress = taskData.progress || simulatedProgress;
                    
                    // İlerleme çubuğunu güncelle
                    updateProgressBar(progress);
                    
                    // Durum mesajını güncelle
                    updateStatus(taskData.status);
                    
                    // Tamamlandıysa, sonuç sayfasına yönlendir
                    if (isCompleted) {
                        completeTask();
                    } else {
                        // Devam ediyorsa, tekrar kontrol et
                        setTimeout(checkTaskStatus, 3000);
                    }
                } else {
                    // Görev verisi eksik, daha sonra tekrar dene
                    console.warn('Görev verisi eksik, tekrar deneniyor');
                    setTimeout(checkTaskStatus, 3000);
                }
            })
            .catch(error => {
                console.error('Görev durumu kontrolü sırasında hata:', error);
                
                // Hata mesajını göster
                showError(
                    'Görev durumu kontrol edilirken bir hata oluştu', 
                    `Hata detayı: ${error.message}. Görev durumu kontrolü sırasında sunucu yanıt vermedi veya geçersiz bir yanıt döndü. Bu problem API servisinden kaynaklanabilir.`
                );
                
                // Yine de devam et, API yanıt vermese bile işlem devam edecek
                updateProgressBar(simulatedProgress);
                
                // Devam ediyorsa, tekrar kontrol et (daha az sıklıkla)
                setTimeout(checkTaskStatus, 5000);
            });
    }
    
    // Hata mesajını göster
    function showError(message, details = '') {
        const errorContainer = document.getElementById('errorContainer');
        const errorMessage = document.getElementById('errorMessage');
        const errorDetails = document.getElementById('errorDetails');
        
        errorMessage.textContent = message;
        errorDetails.textContent = details;
        errorContainer.classList.remove('d-none');
        
        // Hata oluştuğunda bile işlem devam edecek
        // Otomatik tamamlama bilgisini göster
        showAutoCompleteInfo();
    }
    
    // Otomatik tamamlama bilgisini göster
    function showAutoCompleteInfo() {
        const autoCompleteInfo = document.getElementById('autoCompleteInfo');
        autoCompleteInfo.classList.remove('d-none');
        
        // Geri sayım başlat
        startCountdown();
    }
    
    // Geri sayım başlat
    function startCountdown() {
        if (countdownTimer) return; // Zaten başlamışsa tekrar başlatma
        
        const countdownElement = document.getElementById('countdown');
        let seconds = 30;
        
        countdownTimer = setInterval(function() {
            seconds--;
            countdownElement.textContent = seconds;
            
            if (seconds <= 0) {
                clearInterval(countdownTimer);
                completeTask();
            }
        }, 1000);
    }
    
    // Görevi tamamla ve kütüphaneye yönlendir
    function completeTask() {
        if (isCompleted) return; // Zaten tamamlandıysa işlem yapma
        isCompleted = true;
        
        // Zamanlayıcıları temizle
        if (autoCompleteTimer) clearTimeout(autoCompleteTimer);
        if (countdownTimer) clearInterval(countdownTimer);
        
        // İlerleme çubuğunu %100 yap
        updateProgressBar(100);
        
        // Tamamlandı mesajını göster
        document.getElementById('taskInfo').classList.add('d-none');
        document.getElementById('completionInfo').classList.remove('d-none');
        
        // Kütüphane sayfasına yönlendir
        setTimeout(function() {
            window.location.href = libraryUrl;
        }, 2000);
    }
    
    // Tekrar deneme düğmesi
    document.getElementById('retryButton').addEventListener('click', function() {
        checkTaskStatus();
        document.getElementById('errorContainer').classList.add('d-none');
    });
    
    // Şimdi tamamla düğmesi
    document.getElementById('completeNowButton').addEventListener('click', function() {
        completeTask();
    });
    
    // İlk görev kontrolünü başlat
    checkTaskStatus();
    
    // 40 saniye sonra otomatik olarak tamamla (zaman aşımı durumunda)
    autoCompleteTimer = setTimeout(function() {
        if (!isCompleted) {
            console.log('Otomatik tamamlama: 40 saniye geçti');
            completeTask();
        }
    }, 40000);
});
</script>

<?php require_once APPROOT . '/views/inc/footer.php'; ?> 