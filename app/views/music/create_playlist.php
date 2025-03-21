<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="page-container">
    <div class="container">
        <div class="page-header text-center">
            <h1><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Yeni Çalma Listesi Oluştur' : 'Create New Playlist'; ?></h1>
            <p class="text-secondary"><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Müzik koleksiyonunuzu düzenlemek için çalma listesi oluşturun' : 'Create a playlist to organize your music collection'; ?></p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card-bg p-4 rounded animate-card">
                    <form action="<?php echo URL_ROOT; ?>/music/createPlaylist" method="post">
                        <div class="form-group">
                            <label for="name">
                                <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Çalma Listesi Adı' : 'Playlist Name'; ?> 
                                <span class="required">*</span>
                            </label>
                            <input type="text" name="name" id="name" class="form-control <?php echo (!empty($data['errors']['name'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>" required>
                            <?php if (!empty($data['errors']['name'])) : ?>
                                <div class="invalid-feedback"><?php echo $data['errors']['name']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="description"><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Açıklama' : 'Description'; ?></label>
                            <textarea name="description" id="description" rows="4" class="form-control" placeholder="<?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Çalma listeniz hakkında bilgi verin...' : 'Tell us about your playlist...'; ?>"><?php echo $data['description']; ?></textarea>
                            <div class="form-text"><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'İsteğe bağlı: Çalma listeniz hakkında kısa bir açıklama ekleyin' : 'Optional: Add a short description about your playlist'; ?></div>
                        </div>

                        <div class="form-group">
                            <label><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Çalma Listesi Teması' : 'Playlist Theme'; ?></label>
                            <div class="playlist-themes">
                                <div class="theme-options">
                                    <div class="theme-option">
                                        <input type="radio" name="theme" id="theme-default" value="default" checked>
                                        <label for="theme-default" class="theme-label theme-default">
                                            <div class="theme-color"></div>
                                            <span><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Varsayılan' : 'Default'; ?></span>
                                        </label>
                                    </div>
                                    <div class="theme-option">
                                        <input type="radio" name="theme" id="theme-neon" value="neon">
                                        <label for="theme-neon" class="theme-label theme-neon">
                                            <div class="theme-color"></div>
                                            <span><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Neon' : 'Neon'; ?></span>
                                        </label>
                                    </div>
                                    <div class="theme-option">
                                        <input type="radio" name="theme" id="theme-retrowave" value="retrowave">
                                        <label for="theme-retrowave" class="theme-label theme-retrowave">
                                            <div class="theme-color"></div>
                                            <span><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Retrowave' : 'Retrowave'; ?></span>
                                        </label>
                                    </div>
                                    <div class="theme-option">
                                        <input type="radio" name="theme" id="theme-cyberpunk" value="cyberpunk">
                                        <label for="theme-cyberpunk" class="theme-label theme-cyberpunk">
                                            <div class="theme-color"></div>
                                            <span><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Siberpunk' : 'Cyberpunk'; ?></span>
                                        </label>
                                    </div>
                                    <div class="theme-option">
                                        <input type="radio" name="theme" id="theme-minimal" value="minimal">
                                        <label for="theme-minimal" class="theme-label theme-minimal">
                                            <div class="theme-color"></div>
                                            <span><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Minimal' : 'Minimal'; ?></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Oynatma Sırası Tercihi' : 'Playback Order Preference'; ?></label>
                            <div class="playback-options">
                                <div class="custom-radio">
                                    <input type="radio" name="playback_order" id="order-default" value="default" checked>
                                    <label for="order-default">
                                        <i class="fas fa-list"></i>
                                        <span><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Varsayılan Sıra' : 'Default Order'; ?></span>
                                    </label>
                                </div>
                                <div class="custom-radio">
                                    <input type="radio" name="playback_order" id="order-shuffle" value="shuffle">
                                    <label for="order-shuffle">
                                        <i class="fas fa-random"></i>
                                        <span><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Karıştır' : 'Shuffle'; ?></span>
                                    </label>
                                </div>
                                <div class="custom-radio">
                                    <input type="radio" name="playback_order" id="order-loop" value="loop">
                                    <label for="order-loop">
                                        <i class="fas fa-sync-alt"></i>
                                        <span><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Döngü' : 'Loop'; ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Görünürlük' : 'Visibility'; ?></label>
                            <div class="visibility-options">
                                <div class="custom-radio">
                                    <input type="radio" name="visibility" id="visibility-private" value="private" checked>
                                    <label for="visibility-private">
                                        <i class="fas fa-lock"></i>
                                        <span><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Özel' : 'Private'; ?></span>
                                        <small><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Sadece siz görebilirsiniz' : 'Only you can see it'; ?></small>
                                    </label>
                                </div>
                                <div class="custom-radio">
                                    <input type="radio" name="visibility" id="visibility-public" value="public">
                                    <label for="visibility-public">
                                        <i class="fas fa-globe"></i>
                                        <span><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Herkese Açık' : 'Public'; ?></span>
                                        <small><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Paylaşılabilir' : 'Can be shared'; ?></small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mt-4">
                            <div class="custom-checkbox mb-3">
                                <input type="checkbox" name="auto_add_favorites" id="auto_add_favorites">
                                <label for="auto_add_favorites">
                                    <span><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Favorileri otomatik ekle' : 'Automatically add favorites'; ?></span>
                                </label>
                                <small class="d-block mt-1 text-muted"><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Favori parçalarınızı bu çalma listesine otomatik olarak ekleyin' : 'Automatically add your favorite tracks to this playlist'; ?></small>
                            </div>
                            
                            <div class="custom-checkbox">
                                <input type="checkbox" name="enable_auto_update" id="enable_auto_update">
                                <label for="enable_auto_update">
                                    <span><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Otomatik güncelleme' : 'Enable auto-update'; ?></span>
                                </label>
                                <small class="d-block mt-1 text-muted"><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Yeni oluşturulan parçaları belirli kriterlere göre otomatik olarak ekleyin' : 'Automatically add newly generated tracks based on certain criteria'; ?></small>
                            </div>
                        </div>

                        <hr class="border-light my-4">

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary btn-lg pulse-btn">
                                <i class="fas fa-plus-circle me-2"></i>
                                <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Çalma Listesi Oluştur' : 'Create Playlist'; ?>
                            </button>
                            <a href="<?php echo URL_ROOT; ?>/music/playlists" class="btn btn-outline ms-2">
                                <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'İptal' : 'Cancel'; ?>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="help-section animate-card">
                    <h3>
                        <i class="fas fa-lightbulb text-primary"></i>
                        <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'İpuçları' : 'Tips'; ?>
                    </h3>
                    <div class="help-content">
                        <ul>
                            <li><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Benzer türlerin parçalarını bir araya getirin' : 'Group tracks of similar genres together'; ?></li>
                            <li><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Belirli ruh hali veya etkinlikler için çalma listeleri oluşturun' : 'Create playlists for specific moods or activities'; ?></li>
                            <li><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Çalma listesi adı için tür veya tema belirtin' : 'Specify genre or theme in the playlist name'; ?></li>
                            <li><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Özel anlar için özel çalma listeleri planlayın' : 'Curate special playlists for memorable moments'; ?></li>
                        </ul>
                        
                        <div class="example-prompt mt-4">
                            <h4><?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Örnek Çalma Listeleri' : 'Example Playlists'; ?></h4>
                            <p class="mb-1"><strong>Odaklanma Zonu</strong> - <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'Çalışmaya veya odaklanmaya yardımcı olan ortam parçaları' : 'Ambient tracks to help you work or focus'; ?></p>
                            <p class="mb-1"><strong>Elektronik Yolculuk</strong> - <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? 'En iyi elektronik müzik oluşumlarınız' : 'Your best electronic music creations'; ?></p>
                            <p class="mb-1"><strong>Retrowave Mix</strong> - <?php echo isset($_SESSION['lang']) && $_SESSION['lang'] === 'tr' ? '80\'lerin sentetik hislerini yansıtan parçalar' : 'Tracks that mirror the synthetic feel of the 80s'; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styles for the create playlist page */
.card-bg {
    background-color: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    box-shadow: var(--box-shadow);
}

.animate-card {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

.playlist-themes {
    margin-top: 0.5rem;
}

.theme-options {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 0.5rem;
}

.theme-option {
    position: relative;
}

.theme-option input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.theme-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0.75rem;
    border-radius: 8px;
    background-color: rgba(255, 255, 255, 0.05);
    cursor: pointer;
    transition: all 0.3s ease;
    width: 80px;
}

.theme-color {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-bottom: 0.5rem;
}

.theme-default .theme-color {
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
}

.theme-neon .theme-color {
    background: linear-gradient(135deg, #0ff7ef, #ff41c7);
}

.theme-retrowave .theme-color {
    background: linear-gradient(135deg, #ff6ec7, #7873f5);
}

.theme-cyberpunk .theme-color {
    background: linear-gradient(135deg, #f72a8a, #ffde59);
}

.theme-minimal .theme-color {
    background: linear-gradient(135deg, #f5f5f5, #a0a0d0);
}

.theme-option input[type="radio"]:checked + .theme-label {
    background-color: rgba(15, 247, 239, 0.1);
    border: 1px solid var(--accent-primary);
    box-shadow: 0 0 10px var(--glow-primary);
}

.playback-options, .visibility-options {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 0.5rem;
}

.custom-radio {
    position: relative;
    flex: 1;
    min-width: 150px;
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

.custom-radio label small {
    display: block;
    margin-top: 0.5rem;
    color: var(--text-muted);
    font-size: 0.8rem;
}

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

.help-section {
    background-color: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    overflow: hidden;
    height: 100%;
}

.help-section h3 {
    padding: 1.25rem;
    font-size: 1.2rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.help-content {
    padding: 1.25rem;
}

.help-content ul {
    list-style: disc;
    padding-left: 1.5rem;
    margin-bottom: 1.5rem;
}

.help-content li {
    margin-bottom: 0.5rem;
    color: var(--text-secondary);
}

.example-prompt {
    background-color: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    padding: 1.25rem;
}

.example-prompt h4 {
    font-size: 1rem;
    margin-bottom: 0.75rem;
    color: var(--accent-primary);
}

@media (max-width: 768px) {
    .theme-options, .playback-options, .visibility-options {
        justify-content: center;
    }
    
    .custom-radio {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation for cards
    const animatedCards = document.querySelectorAll('.animate-card');
    setTimeout(() => {
        animatedCards.forEach(card => {
            card.style.opacity = 1;
            card.style.transform = 'translateY(0)';
        });
    }, 100);
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>