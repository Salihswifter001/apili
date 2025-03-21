<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="admin-container">
    <!-- Admin Side Menu -->
    <div class="admin-sidebar">
        <h3>Yönetim Paneli</h3>
        <ul>
            <li><a href="<?php echo URLROOT; ?>/admin"><i class="fas fa-tachometer-alt"></i> Genel Bakış</a></li>
            <li><a href="<?php echo URLROOT; ?>/admin/users"><i class="fas fa-users"></i> Kullanıcılar</a></li>
            <li class="active"><a href="<?php echo URLROOT; ?>/admin/tracks"><i class="fas fa-music"></i> Parçalar</a></li>
            <li><a href="<?php echo URLROOT; ?>/admin/playlists"><i class="fas fa-list"></i> Çalma Listeleri</a></li>
            <li><a href="<?php echo URLROOT; ?>/admin/favorites"><i class="fas fa-heart"></i> Favoriler</a></li>
        </ul>
    </div>

    <!-- Admin Content Area -->
    <div class="admin-content">
        <div class="content-header">
            <h1>Parça Düzenle</h1>
            <a href="<?php echo URLROOT; ?>/admin/tracks" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Geri Dön
            </a>
        </div>
        
        <?php flash('admin_message'); ?>
        
        <div class="form-container">
            <div class="form-info">
                <h2><?php echo $data['track']->title; ?></h2>
                <p><strong>Kullanıcı:</strong> <?php echo $data['track']->username; ?></p>
                <p><strong>Oluşturulma Tarihi:</strong> <?php echo date('d.m.Y H:i', strtotime($data['track']->created_at)); ?></p>
                
                <div class="audio-preview">
                    <h3>Ses Önizleme</h3>
                    <audio controls>
                        <source src="<?php echo $data['track']->audio_url; ?>" type="audio/mpeg">
                        Tarayıcınız ses etiketini desteklemiyor.
                    </audio>
                </div>
            </div>
            
            <form action="<?php echo URLROOT; ?>/admin/editTrack/<?php echo $data['track']->id; ?>" method="POST">
                <div class="form-group">
                    <label for="title">Başlık:</label>
                    <input type="text" name="title" id="title" value="<?php echo $data['track']->title; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="prompt">Komut (Prompt):</label>
                    <textarea name="prompt" id="prompt" rows="4" required><?php echo $data['track']->prompt; ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="genre">Tür:</label>
                        <select name="genre" id="genre">
                            <option value="electronic" <?php echo $data['track']->genre === 'electronic' ? 'selected' : ''; ?>>Elektronik</option>
                            <option value="ambient" <?php echo $data['track']->genre === 'ambient' ? 'selected' : ''; ?>>Ambient</option>
                            <option value="orchestral" <?php echo $data['track']->genre === 'orchestral' ? 'selected' : ''; ?>>Orkestral</option>
                            <option value="pop" <?php echo $data['track']->genre === 'pop' ? 'selected' : ''; ?>>Pop</option>
                            <option value="rock" <?php echo $data['track']->genre === 'rock' ? 'selected' : ''; ?>>Rock</option>
                            <option value="jazz" <?php echo $data['track']->genre === 'jazz' ? 'selected' : ''; ?>>Jazz</option>
                            <option value="hiphop" <?php echo $data['track']->genre === 'hiphop' ? 'selected' : ''; ?>>Hip Hop</option>
                            <option value="classical" <?php echo $data['track']->genre === 'classical' ? 'selected' : ''; ?>>Klasik</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="duration">Süre (saniye):</label>
                        <input type="number" name="duration" id="duration" value="<?php echo $data['track']->duration; ?>" min="1" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="bpm">BPM (Tempo):</label>
                        <input type="number" name="bpm" id="bpm" value="<?php echo $data['track']->bpm; ?>" min="1" max="300" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="key">Tonalite:</label>
                        <select name="key" id="key" required>
                            <option value="C major" <?php echo $data['track']->key === 'C major' ? 'selected' : ''; ?>>C Majör</option>
                            <option value="C minor" <?php echo $data['track']->key === 'C minor' ? 'selected' : ''; ?>>C Minör</option>
                            <option value="C# major" <?php echo $data['track']->key === 'C# major' ? 'selected' : ''; ?>>C# Majör</option>
                            <option value="C# minor" <?php echo $data['track']->key === 'C# minor' ? 'selected' : ''; ?>>C# Minör</option>
                            <option value="D major" <?php echo $data['track']->key === 'D major' ? 'selected' : ''; ?>>D Majör</option>
                            <option value="D minor" <?php echo $data['track']->key === 'D minor' ? 'selected' : ''; ?>>D Minör</option>
                            <option value="D# major" <?php echo $data['track']->key === 'D# major' ? 'selected' : ''; ?>>D# Majör</option>
                            <option value="D# minor" <?php echo $data['track']->key === 'D# minor' ? 'selected' : ''; ?>>D# Minör</option>
                            <option value="E major" <?php echo $data['track']->key === 'E major' ? 'selected' : ''; ?>>E Majör</option>
                            <option value="E minor" <?php echo $data['track']->key === 'E minor' ? 'selected' : ''; ?>>E Minör</option>
                            <option value="F major" <?php echo $data['track']->key === 'F major' ? 'selected' : ''; ?>>F Majör</option>
                            <option value="F minor" <?php echo $data['track']->key === 'F minor' ? 'selected' : ''; ?>>F Minör</option>
                            <option value="F# major" <?php echo $data['track']->key === 'F# major' ? 'selected' : ''; ?>>F# Majör</option>
                            <option value="F# minor" <?php echo $data['track']->key === 'F# minor' ? 'selected' : ''; ?>>F# Minör</option>
                            <option value="G major" <?php echo $data['track']->key === 'G major' ? 'selected' : ''; ?>>G Majör</option>
                            <option value="G minor" <?php echo $data['track']->key === 'G minor' ? 'selected' : ''; ?>>G Minör</option>
                            <option value="G# major" <?php echo $data['track']->key === 'G# major' ? 'selected' : ''; ?>>G# Majör</option>
                            <option value="G# minor" <?php echo $data['track']->key === 'G# minor' ? 'selected' : ''; ?>>G# Minör</option>
                            <option value="A major" <?php echo $data['track']->key === 'A major' ? 'selected' : ''; ?>>A Majör</option>
                            <option value="A minor" <?php echo $data['track']->key === 'A minor' ? 'selected' : ''; ?>>A Minör</option>
                            <option value="A# major" <?php echo $data['track']->key === 'A# major' ? 'selected' : ''; ?>>A# Majör</option>
                            <option value="A# minor" <?php echo $data['track']->key === 'A# minor' ? 'selected' : ''; ?>>A# Minör</option>
                            <option value="B major" <?php echo $data['track']->key === 'B major' ? 'selected' : ''; ?>>B Majör</option>
                            <option value="B minor" <?php echo $data['track']->key === 'B minor' ? 'selected' : ''; ?>>B Minör</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row parameters-section">
                    <div class="form-group">
                        <label>Model Parametreleri:</label>
                        <div class="parameters-display">
                            <?php 
                                $parameters = json_decode($data['track']->parameters, true);
                                foreach($parameters as $key => $value) : 
                            ?>
                                <div class="parameter-item">
                                    <span class="parameter-name"><?php echo $key; ?>:</span>
                                    <span class="parameter-value"><?php echo $value; ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <small class="text-muted">Not: Model parametreleri doğrudan düzenlenemez</small>
                    </div>
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn btn-success">Kaydet</button>
                    <a href="<?php echo URLROOT; ?>/admin/tracks" class="btn btn-secondary">İptal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Admin Panel Custom CSS -->
<style>
    .admin-container {
        display: flex;
        min-height: calc(100vh - 100px);
    }
    
    .admin-sidebar {
        width: 250px;
        background: #222;
        color: #fff;
        padding: 20px 0;
    }
    
    .admin-sidebar h3 {
        padding: 0 20px 20px;
        margin: 0;
        border-bottom: 1px solid #444;
    }
    
    .admin-sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .admin-sidebar ul li {
        padding: 0;
    }
    
    .admin-sidebar ul li a {
        display: block;
        padding: 15px 20px;
        color: #ddd;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .admin-sidebar ul li a:hover, 
    .admin-sidebar ul li.active a {
        background: #444;
        color: #fff;
    }
    
    .admin-sidebar ul li a i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }
    
    .admin-content {
        flex: 1;
        padding: 20px;
        background: #f5f5f5;
    }
    
    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .form-container {
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        padding: 20px;
    }
    
    .form-info {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .form-info h2 {
        margin-top: 0;
        color: #333;
    }
    
    .audio-preview {
        margin-top: 20px;
        padding: 15px;
        background: #f9f9f9;
        border-radius: 5px;
    }
    
    .audio-preview h3 {
        margin-top: 0;
        margin-bottom: 10px;
        font-size: 16px;
    }
    
    audio {
        width: 100%;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-row {
        display: flex;
        gap: 20px;
    }
    
    .form-row .form-group {
        flex: 1;
    }
    
    label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
    }
    
    input[type="text"],
    input[type="number"],
    select,
    textarea {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: #f9f9f9;
    }
    
    textarea {
        resize: vertical;
    }
    
    .parameters-section {
        display: block;
    }
    
    .parameters-display {
        background: #f9f9f9;
        padding: 10px;
        border: 1px solid #eee;
        border-radius: 4px;
        margin-bottom: 5px;
    }
    
    .parameter-item {
        padding: 5px 0;
        border-bottom: 1px dotted #ddd;
    }
    
    .parameter-item:last-child {
        border-bottom: none;
    }
    
    .parameter-name {
        font-weight: 600;
        color: #555;
    }
    
    .text-muted {
        color: #6c757d;
        font-size: 0.9em;
    }
    
    .form-buttons {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }
    
    .btn {
        display: inline-block;
        padding: 10px 15px;
        background: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
    }
    
    .btn-success {
        background: #28a745;
    }
    
    .btn-secondary {
        background: #6c757d;
    }
    
    .btn i {
        margin-right: 5px;
    }
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?>