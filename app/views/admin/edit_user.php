<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="admin-container">
    <!-- Admin Side Menu -->
    <div class="admin-sidebar">
        <h3>Yönetim Paneli</h3>
        <ul>
            <li><a href="<?php echo URLROOT; ?>/admin"><i class="fas fa-tachometer-alt"></i> Genel Bakış</a></li>
            <li class="active"><a href="<?php echo URLROOT; ?>/admin/users"><i class="fas fa-users"></i> Kullanıcılar</a></li>
            <li><a href="<?php echo URLROOT; ?>/admin/tracks"><i class="fas fa-music"></i> Parçalar</a></li>
            <li><a href="<?php echo URLROOT; ?>/admin/playlists"><i class="fas fa-list"></i> Çalma Listeleri</a></li>
            <li><a href="<?php echo URLROOT; ?>/admin/favorites"><i class="fas fa-heart"></i> Favoriler</a></li>
        </ul>
    </div>

    <!-- Admin Content Area -->
    <div class="admin-content">
        <div class="content-header">
            <h1>Kullanıcı Düzenle</h1>
            <a href="<?php echo URLROOT; ?>/admin/users" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Geri Dön
            </a>
        </div>
        
        <?php flash('admin_message'); ?>
        
        <div class="form-container">
            <form action="<?php echo URLROOT; ?>/admin/editUser/<?php echo $data['user']->id; ?>" method="POST">
                <div class="form-group">
                    <label for="username">Kullanıcı Adı:</label>
                    <input type="text" name="username" id="username" value="<?php echo $data['user']->username; ?>" required <?php echo $data['user']->username === 'admin' ? 'readonly' : ''; ?>>
                </div>
                
                <div class="form-group">
                    <label for="email">E-posta:</label>
                    <input type="email" name="email" id="email" value="<?php echo $data['user']->email; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="name">İsim:</label>
                    <input type="text" name="name" id="name" value="<?php echo $data['user']->name; ?>">
                </div>
                
                <div class="form-group">
                    <label for="bio">Biyografi:</label>
                    <textarea name="bio" id="bio" rows="4"><?php echo $data['user']->bio; ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="subscription_tier">Abonelik:</label>
                        <select name="subscription_tier" id="subscription_tier" required>
                            <option value="free" <?php echo $data['user']->subscription_tier === 'free' ? 'selected' : ''; ?>>Ücretsiz</option>
                            <option value="premium" <?php echo $data['user']->subscription_tier === 'premium' ? 'selected' : ''; ?>>Premium</option>
                            <option value="professional" <?php echo $data['user']->subscription_tier === 'professional' ? 'selected' : ''; ?>>Profesyonel</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="monthly_generations">Aylık Üretim Limiti:</label>
                        <input type="number" name="monthly_generations" id="monthly_generations" value="<?php echo $data['user']->monthly_generations; ?>" min="0" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="reset_date">Yenileme Tarihi:</label>
                        <input type="date" name="reset_date" id="reset_date" value="<?php echo date('Y-m-d', strtotime($data['user']->reset_date)); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="theme">Tema:</label>
                        <select name="theme" id="theme">
                            <option value="dark" <?php echo $data['user']->theme === 'dark' ? 'selected' : ''; ?>>Koyu</option>
                            <option value="light" <?php echo $data['user']->theme === 'light' ? 'selected' : ''; ?>>Açık</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="player_visualization">Oynatıcı Görselleştirme:</label>
                        <select name="player_visualization" id="player_visualization">
                            <option value="waveform" <?php echo $data['user']->player_visualization === 'waveform' ? 'selected' : ''; ?>>Dalga Formu</option>
                            <option value="bars" <?php echo $data['user']->player_visualization === 'bars' ? 'selected' : ''; ?>>Çubuklar</option>
                            <option value="circular" <?php echo $data['user']->player_visualization === 'circular' ? 'selected' : ''; ?>>Dairesel</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="language">Dil:</label>
                        <select name="language" id="language">
                            <option value="en" <?php echo $data['user']->language === 'en' ? 'selected' : ''; ?>>İngilizce</option>
                            <option value="tr" <?php echo $data['user']->language === 'tr' ? 'selected' : ''; ?>>Türkçe</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="auto_play" <?php echo $data['user']->auto_play ? 'checked' : ''; ?>>
                        <span>Otomatik Oynatma</span>
                    </label>
                </div>
                
                <div class="form-group">
                    <label for="monthly_downloads">Aylık İndirme Sayısı:</label>
                    <input type="number" name="monthly_downloads" id="monthly_downloads" value="<?php echo $data['user']->monthly_downloads; ?>" min="0" required>
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn btn-success">Kaydet</button>
                    <a href="<?php echo URLROOT; ?>/admin/users" class="btn btn-secondary">İptal</a>
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
    input[type="email"],
    input[type="number"],
    input[type="date"],
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
    
    .checkbox-label {
        display: flex;
        align-items: center;
        cursor: pointer;
    }
    
    .checkbox-label input {
        margin-right: 10px;
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