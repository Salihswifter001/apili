<!-- Paylaşım Popup Yapısı -->
<div class="share-popup-overlay" id="sharePopupOverlay" style="display: none;">
    <div class="share-popup" id="sharePopup">
        <div class="popup-header">
            <h3>Parçayı Paylaş</h3>
            <button type="button" class="close-popup-btn" id="closeSharePopup">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="popup-body">
            <div class="share-track-info mb-4">
                <div class="track-artwork">
                    <i class="fas fa-music"></i>
                </div>
                <h4 id="shareTrackTitle">Parça Adı</h4>
                <div class="track-share-wave">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            
            <div class="share-link-section mb-4">
                <label for="shareLink">Bağlantı</label>
                <div class="input-group">
                    <input type="text" id="shareLink" class="form-control" readonly>
                    <button class="btn copy-link-btn" id="copyShareLink">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <small class="text-secondary">Bu bağlantıyı kopyalayıp paylaşabilirsiniz</small>
            </div>
            
            <div class="share-social mb-4">
                <label>Sosyal Medyada Paylaş</label>
                <div class="social-buttons">
                    <a href="#" id="shareTwitter" class="social-btn twitter" target="_blank">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" id="shareFacebook" class="social-btn facebook" target="_blank">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" id="shareWhatsapp" class="social-btn whatsapp" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="#" id="shareTelegram" class="social-btn telegram" target="_blank">
                        <i class="fab fa-telegram-plane"></i>
                    </a>
                </div>
            </div>
            
            <div class="embed-section">
                <label for="embedCode">Gömme Kodu</label>
                <div class="code-container">
                    <textarea id="embedCode" class="form-control" rows="4" readonly></textarea>
                    <div class="code-overlay">
                        <button class="btn btn-primary" id="copyEmbedCode">Kodu Kopyala</button>
                    </div>
                </div>
                <small class="text-secondary">Bu kodu web sitenize ekleyerek parçayı gösterebilirsiniz</small>
            </div>
            
            <div class="qr-code-section mt-4">
                <label>QR Kod</label>
                <div class="qr-container" id="qrCodeContainer">
                    <!-- QR kod burada oluşturulacak -->
                </div>
                <small class="text-secondary">QR kodu taratarak parçaya direkt erişilebilir</small>
            </div>
        </div>
    </div>
</div>

<style>
/* Share Popup Styles */
.share-popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.85);
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    animation: fadeIn 0.3s ease-out forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.share-popup {
    background: rgba(15, 16, 29, 0.9);
    border-radius: 20px;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.08);
    transform: translateY(20px);
    animation: slideUp 0.4s ease-out forwards;
}

@keyframes slideUp {
    from {
        transform: translateY(30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.popup-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    background: rgba(10, 10, 20, 0.5);
}

.popup-header h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    background: linear-gradient(to right, #fff, #0ff7ef);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

.close-popup-btn {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: rgba(255, 255, 255, 0.6);
    width: 36px;
    height: 36px;
    border-radius: 50%;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.close-popup-btn:hover {
    color: #fff;
    background: rgba(255, 255, 255, 0.2);
    transform: rotate(90deg);
}

.popup-body {
    padding: 25px;
}

.share-track-info {
    margin-bottom: 25px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.track-artwork {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, rgba(15, 247, 239, 0.2), rgba(247, 42, 160, 0.2));
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

.track-artwork:before {
    content: '';
    position: absolute;
    top: -30%;
    left: -30%;
    width: 160%;
    height: 160%;
    background: linear-gradient(45deg, rgba(15, 247, 239, 0.3), rgba(247, 42, 160, 0.3));
    animation: rotate 8s linear infinite;
}

.track-artwork i {
    position: relative;
    z-index: 2;
    font-size: 30px;
    color: rgba(255, 255, 255, 0.9);
}

@keyframes rotate {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.share-track-info h4 {
    margin: 10px 0;
    font-size: 20px;
    font-weight: 600;
    color: #fff;
}

.track-share-wave {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 20px;
    margin-top: 10px;
}

.track-share-wave span {
    width: 3px;
    height: 100%;
    background: linear-gradient(to bottom, #0ff7ef, #f72aa0);
    margin: 0 2px;
    border-radius: 3px;
    animation: wave 1.2s ease-in-out infinite;
}

.track-share-wave span:nth-child(2) {
    animation-delay: 0.1s;
}
.track-share-wave span:nth-child(3) {
    animation-delay: 0.2s;
}
.track-share-wave span:nth-child(4) {
    animation-delay: 0.3s;
}
.track-share-wave span:nth-child(5) {
    animation-delay: 0.4s;
}
.track-share-wave span:nth-child(6) {
    animation-delay: 0.5s;
}
.track-share-wave span:nth-child(7) {
    animation-delay: 0.6s;
}

@keyframes wave {
    0%, 100% {
        height: 5px;
    }
    50% {
        height: 20px;
    }
}

.share-link-section label,
.share-social label,
.embed-section label,
.qr-code-section label {
    display: block;
    margin-bottom: 10px;
    font-size: 14px;
    color: rgba(255, 255, 255, 0.7);
    letter-spacing: 0.5px;
}

.input-group {
    display: flex;
    margin-bottom: 8px;
    border-radius: 12px;
    overflow: hidden;
    background: rgba(10, 10, 20, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.input-group:focus-within {
    border-color: rgba(15, 247, 239, 0.4);
    box-shadow: 0 0 0 3px rgba(15, 247, 239, 0.1);
}

#shareLink {
    background: transparent;
    border: none;
    padding: 14px 16px;
    color: #fff;
    flex: 1;
    font-size: 14px;
}

.copy-link-btn {
    background: rgba(15, 247, 239, 0.1);
    border: none;
    color: #0ff7ef;
    padding: 0 16px;
    transition: all 0.3s ease;
}

.copy-link-btn:hover {
    background: rgba(15, 247, 239, 0.2);
}

small.text-secondary {
    color: rgba(255, 255, 255, 0.5);
    font-size: 12px;
}

.social-buttons {
    display: flex;
    gap: 15px;
    margin-top: 15px;
}

.social-btn {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 20px;
    transition: all 0.3s ease;
    text-decoration: none;
    position: relative;
    overflow: hidden;
}

.social-btn:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(100%);
    transition: transform 0.3s ease;
}

.social-btn:hover {
    transform: translateY(-5px);
}

.social-btn:hover:before {
    transform: translateY(0);
}

.social-btn.twitter {
    background: #1da1f2;
    box-shadow: 0 5px 15px rgba(29, 161, 242, 0.3);
}

.social-btn.facebook {
    background: #4267B2;
    box-shadow: 0 5px 15px rgba(66, 103, 178, 0.3);
}

.social-btn.whatsapp {
    background: #25D366;
    box-shadow: 0 5px 15px rgba(37, 211, 102, 0.3);
}

.social-btn.telegram {
    background: #0088cc;
    box-shadow: 0 5px 15px rgba(0, 136, 204, 0.3);
}

.code-container {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 10px;
}

#embedCode {
    background: rgba(10, 10, 20, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.7);
    resize: none;
    font-family: monospace;
    font-size: 12px;
    padding: 14px;
    border-radius: 12px;
    height: auto;
    transition: all 0.3s ease;
}

.code-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(10, 10, 20, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.code-container:hover .code-overlay {
    opacity: 1;
}

.code-overlay .btn {
    background: linear-gradient(90deg, #0ff7ef, #f72aa0);
    border: none;
    padding: 10px 20px;
    font-weight: 600;
    box-shadow: 0 5px 15px rgba(15, 247, 239, 0.3);
    transition: all 0.3s ease;
}

.code-overlay .btn:hover {
    transform: scale(1.05);
}

.qr-code-section {
    margin-top: 25px;
    text-align: center;
}

.qr-container {
    background: rgba(255, 255, 255, 0.9);
    width: 150px;
    height: 150px;
    margin: 0 auto 10px;
    padding: 10px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.qr-container:before {
    content: '';
    position: absolute;
    top: -10px;
    left: -10px;
    right: -10px;
    bottom: -10px;
    background: linear-gradient(45deg, #0ff7ef, #f72aa0, #0ff7ef, #f72aa0);
    background-size: 400% 400%;
    animation: gradient 8s ease infinite;
    z-index: -1;
    border-radius: 12px;
}

@keyframes gradient {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

/* Responsive Styles */
@media (max-width: 576px) {
    .share-popup {
        width: 95%;
        max-width: none;
    }
    
    .social-buttons {
        flex-wrap: wrap;
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Paylaşım popup'ını aç/kapat
    const shareButtons = document.querySelectorAll('.share-track-btn');
    const sharePopupOverlay = document.getElementById('sharePopupOverlay');
    const closeSharePopupBtn = document.getElementById('closeSharePopup');
    const shareTrackTitle = document.getElementById('shareTrackTitle');
    const shareLink = document.getElementById('shareLink');
    const embedCode = document.getElementById('embedCode');
    const copyShareLinkBtn = document.getElementById('copyShareLink');
    const copyEmbedCodeBtn = document.getElementById('copyEmbedCode');
    const shareTwitter = document.getElementById('shareTwitter');
    const shareFacebook = document.getElementById('shareFacebook');
    const shareWhatsapp = document.getElementById('shareWhatsapp');
    const shareTelegram = document.getElementById('shareTelegram');
    const qrCodeContainer = document.getElementById('qrCodeContainer');
    
    // Paylaş butonlarına tıklama fonksiyonu
    if (shareButtons.length > 0) {
        shareButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const trackId = this.getAttribute('data-id');
                const trackTitle = this.getAttribute('data-title');
                
                // Share popup içeriğini güncelle
                shareTrackTitle.textContent = trackTitle;
                
                // Paylaşım bağlantısını oluştur
                const shareUrl = `${window.location.origin}/music/share/${trackId}`;
                shareLink.value = shareUrl;
                
                // Gömme kodunu oluştur
                const embedUrl = `${window.location.origin}/music/embed/${trackId}`;
                embedCode.value = `<iframe src="${embedUrl}" width="100%" height="150" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>`;
                
                // Sosyal medya bağlantılarını güncelle
                shareTwitter.href = `https://twitter.com/intent/tweet?text=${encodeURIComponent('Bu müziği dinleyin: ' + trackTitle)}&url=${encodeURIComponent(shareUrl)}`;
                shareFacebook.href = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl)}`;
                shareWhatsapp.href = `https://wa.me/?text=${encodeURIComponent(trackTitle + ' ' + shareUrl)}`;
                shareTelegram.href = `https://t.me/share/url?url=${encodeURIComponent(shareUrl)}&text=${encodeURIComponent('Bu müziği dinleyin: ' + trackTitle)}`;
                
                // QR Kodu oluştur
                generateQRCode(shareUrl);
                
                // Popup'ı göster
                sharePopupOverlay.style.display = 'flex';
                
                // Animasyon için sesi görselleştirme simülasyonu
                animateSoundWaves();
                
                // Scroll'u engelle
                document.body.style.overflow = 'hidden';
            });
        });
    }
    
    // Kapat butonuna tıklama
    if (closeSharePopupBtn) {
        closeSharePopupBtn.addEventListener('click', function() {
            sharePopupOverlay.style.display = 'none';
            document.body.style.overflow = '';
        });
    }
    
    // Overlay dışına tıklama
    if (sharePopupOverlay) {
        sharePopupOverlay.addEventListener('click', function(e) {
            if (e.target === sharePopupOverlay) {
                sharePopupOverlay.style.display = 'none';
                document.body.style.overflow = '';
            }
        });
    }
    
    // Paylaşım bağlantısını kopyala
    if (copyShareLinkBtn) {
        copyShareLinkBtn.addEventListener('click', function() {
            shareLink.select();
            document.execCommand('copy');
            showCopiedMessage(this);
        });
    }
    
    // Gömme kodunu kopyala
    if (copyEmbedCodeBtn) {
        copyEmbedCodeBtn.addEventListener('click', function() {
            embedCode.select();
            document.execCommand('copy');
            this.textContent = 'Kopyalandı!';
            
            // Başarılı animasyonu
            this.classList.add('copied');
            
            setTimeout(() => {
                this.textContent = 'Kodu Kopyala';
                this.classList.remove('copied');
            }, 2000);
        });
    }
    
    // Kopyalandı mesajı göster
    function showCopiedMessage(button) {
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i>';
        button.style.background = 'rgba(15, 247, 239, 0.3)';
        
        setTimeout(() => {
            button.innerHTML = originalContent;
            button.style.background = '';
        }, 2000);
    }
    
    // QR Kodu oluştur
    function generateQRCode(url) {
        // QR kod kütüphanesi yüklü değilse, basit bir görsel göster
        if (typeof QRCode === 'undefined') {
            qrCodeContainer.innerHTML = `
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: white; color: black;">
                    <i class="fas fa-qrcode fa-3x"></i>
                </div>
            `;
            return;
        }
        
        // QR kod kütüphanesi varsa, QR kodu oluştur
        qrCodeContainer.innerHTML = '';
        new QRCode(qrCodeContainer, {
            text: url,
            width: 130,
            height: 130,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    }
    
    // Ses dalgası animasyonu
    function animateSoundWaves() {
        const waves = document.querySelectorAll('.track-share-wave span');
        waves.forEach(wave => {
            const randomHeight = Math.floor(Math.random() * 15) + 5;
            wave.style.height = `${randomHeight}px`;
        });
        
        setTimeout(animateSoundWaves, 500);
    }
    
    // QR kod kütüphanesini dinamik olarak yükle
    function loadQRCodeLibrary() {
        if (typeof QRCode === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js';
            script.async = true;
            script.onload = function() {
                const shareButtons = document.querySelectorAll('.share-track-btn');
                if (shareButtons.length > 0 && shareButtons[0].getAttribute('data-id')) {
                    const trackId = shareButtons[0].getAttribute('data-id');
                    const shareUrl = `${window.location.origin}/music/share/${trackId}`;
                    if (sharePopupOverlay.style.display === 'flex') {
                        generateQRCode(shareUrl);
                    }
                }
            };
            document.head.appendChild(script);
        }
    }
    
    // Sayfa yüklendiğinde QR kod kütüphanesini yükle
    loadQRCodeLibrary();
});
</script> 