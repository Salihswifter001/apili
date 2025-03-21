<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="admin-container">
    <!-- Admin Side Menu -->
    <div class="admin-sidebar">
        <h3>Yönetim Paneli</h3>
        <ul>
            <li class="active"><a href="<?php echo URLROOT; ?>/admin"><i class="fas fa-tachometer-alt"></i> Genel Bakış</a></li>
            <li><a href="<?php echo URLROOT; ?>/admin/users"><i class="fas fa-users"></i> Kullanıcılar</a></li>
            <li><a href="<?php echo URLROOT; ?>/admin/tracks"><i class="fas fa-music"></i> Parçalar</a></li>
            <li><a href="<?php echo URLROOT; ?>/admin/playlists"><i class="fas fa-list"></i> Çalma Listeleri</a></li>
            <li><a href="<?php echo URLROOT; ?>/admin/favorites"><i class="fas fa-heart"></i> Favoriler</a></li>
        </ul>
    </div>

    <!-- Admin Content Area -->
    <div class="admin-content">
        <h1>Yönetim Paneli</h1>
        <p>Octaverum AI veritabanını bu panel üzerinden yönetebilirsiniz.</p>
        
        <!-- Dashboard Statistics -->
        <div class="admin-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h2><?php echo $data['userCount']; ?></h2>
                    <p>Toplam Kullanıcı</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-music"></i>
                </div>
                <div class="stat-info">
                    <h2><?php echo $data['trackCount']; ?></h2>
                    <p>Toplam Parça</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div class="stat-info">
                    <h2><?php echo $data['playlistCount']; ?></h2>
                    <p>Toplam Çalma Listesi</p>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="admin-quick-actions">
            <h3>Hızlı İşlemler</h3>
            <div class="quick-action-buttons">
                <a href="<?php echo URLROOT; ?>/admin/users" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Kullanıcıları Yönet
                </a>
                <a href="<?php echo URLROOT; ?>/admin/tracks" class="btn btn-success">
                    <i class="fas fa-music"></i> Parçaları Yönet
                </a>
                <a href="<?php echo URLROOT; ?>/admin/playlists" class="btn btn-info">
                    <i class="fas fa-list"></i> Çalma Listelerini Yönet
                </a>
            </div>
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
    
    .admin-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 20px;
    }
    
    .stat-card {
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        padding: 20px;
        display: flex;
        align-items: center;
        flex: 1;
        min-width: 200px;
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #6c5ce7;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 24px;
        margin-right: 15px;
    }
    
    .stat-info h2 {
        margin: 0;
        font-size: 28px;
        color: #333;
    }
    
    .stat-info p {
        margin: 5px 0 0;
        color: #777;
    }
    
    .stat-card:nth-child(2) .stat-icon {
        background: #ff7675;
    }
    
    .stat-card:nth-child(3) .stat-icon {
        background: #fdcb6e;
    }
    
    .admin-quick-actions {
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        padding: 20px;
        margin-top: 20px;
    }
    
    .admin-quick-actions h3 {
        margin-top: 0;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }
    
    .quick-action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 15px;
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
    
    .btn-primary {
        background: #007bff;
    }
    
    .btn-success {
        background: #28a745;
    }
    
    .btn-info {
        background: #17a2b8;
    }
    
    .btn-danger {
        background: #dc3545;
    }
    
    .btn i {
        margin-right: 5px;
    }
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?>