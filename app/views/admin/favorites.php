<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="admin-container">
    <!-- Admin Side Menu -->
    <div class="admin-sidebar">
        <h3>Yönetim Paneli</h3>
        <ul>
            <li><a href="<?php echo URLROOT; ?>/admin"><i class="fas fa-tachometer-alt"></i> Genel Bakış</a></li>
            <li><a href="<?php echo URLROOT; ?>/admin/users"><i class="fas fa-users"></i> Kullanıcılar</a></li>
            <li><a href="<?php echo URLROOT; ?>/admin/tracks"><i class="fas fa-music"></i> Parçalar</a></li>
            <li><a href="<?php echo URLROOT; ?>/admin/playlists"><i class="fas fa-list"></i> Çalma Listeleri</a></li>
            <li class="active"><a href="<?php echo URLROOT; ?>/admin/favorites"><i class="fas fa-heart"></i> Favoriler</a></li>
        </ul>
    </div>

    <!-- Admin Content Area -->
    <div class="admin-content">
        <h1>Favoriler Yönetimi</h1>
        
        <?php flash('admin_message'); ?>
        
        <div class="data-container">
            <div class="data-header">
                <h3>Favori Listesi</h3>
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Favori ara...">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kullanıcı</th>
                            <th>Parça</th>
                            <th>Eklenme Tarihi</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['favorites'] as $favorite) : ?>
                        <tr>
                            <td><?php echo $favorite->id; ?></td>
                            <td><?php echo $favorite->username; ?></td>
                            <td><?php echo $favorite->track_title; ?></td>
                            <td><?php echo date('d.m.Y H:i', strtotime($favorite->created_at)); ?></td>
                            <td>
                                <a href="<?php echo URLROOT; ?>/admin/deleteFavorite/<?php echo $favorite->id; ?>" class="btn-sm btn-danger" onclick="return confirm('Bu favoriyi silmek istediğinizden emin misiniz?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if(empty($data['favorites'])) : ?>
                <div class="no-data">
                    <i class="fas fa-heart"></i>
                    <p>Henüz hiç favori bulunmuyor.</p>
                </div>
            <?php endif; ?>
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
    
    .data-container {
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .data-header {
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #eee;
    }
    
    .data-header h3 {
        margin: 0;
    }
    
    .search-box {
        position: relative;
    }
    
    .search-box input {
        padding: 8px 15px 8px 35px;
        border: 1px solid #ddd;
        border-radius: 4px;
        width: 250px;
        outline: none;
    }
    
    .search-box i {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
    }
    
    .table-responsive {
        overflow-x: auto;
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table th, .data-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    
    .data-table th {
        background: #f9f9f9;
        font-weight: 600;
    }
    
    .data-table tr:hover {
        background: #f5f5f5;
    }
    
    .btn-sm {
        display: inline-block;
        width: 30px;
        height: 30px;
        line-height: 30px;
        padding: 0;
        text-align: center;
        border-radius: 3px;
        color: #fff;
        text-decoration: none;
    }
    
    .btn-danger {
        background: #dc3545;
    }
    
    .no-data {
        padding: 50px 20px;
        text-align: center;
        color: #999;
    }
    
    .no-data i {
        font-size: 48px;
        margin-bottom: 10px;
    }
</style>

<script>
    // Arama işlevi
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const table = document.querySelector('.data-table');
        const rows = table.querySelectorAll('tbody tr');
        
        searchInput.addEventListener('keyup', function() {
            const term = searchInput.value.toLowerCase();
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if(text.indexOf(term) > -1) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>