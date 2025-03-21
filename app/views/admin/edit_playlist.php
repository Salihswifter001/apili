<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="admin-container">
    <!-- Admin Side Menu -->
    <div class="admin-sidebar">
        <h3>Yönetim Paneli</h3>
        <ul>
            <li><a href="<?php echo URLROOT; ?>/admin"><i class="fas fa-tachometer-alt"></i> Genel Bakış</a></li>
            <li><a href="<?php echo URLROOT; ?>/admin/users"><i class="fas fa-users"></i> Kullanıcılar</a></li>
            <li><a href="<?php echo URLROOT; ?>/admin/tracks"><i class="fas fa-music"></i> Parçalar</a></li>
            <li class="active"><a href="<?php echo URLROOT; ?>/admin/playlists"><i class="fas fa-list"></i> Çalma Listeleri</a></li>
            <li><a href="<?php echo URLROOT; ?>/admin/favorites"><i class="fas fa-heart"></i> Favoriler</a></li>
        </ul>
    </div>

    <!-- Admin Content Area -->
    <div class="admin-content">
        <div class="content-header">
            <h1>Çalma Listesi Düzenle</h1>
            <a href="<?php echo URLROOT; ?>/admin/playlists" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Geri Dön
            </a>
        </div>
        
        <?php flash('admin_message'); ?>
        
        <div class="playlist-edit-container">
            <!-- Çalma Listesi Bilgileri Formu -->
            <div class="form-container">
                <h2>Çalma Listesi Bilgileri</h2>
                <form action="<?php echo URLROOT; ?>/admin/editPlaylist/<?php echo $data['playlist']->id; ?>" method="POST">
                    <div class="form-group">
                        <label for="name">Çalma Listesi Adı:</label>
                        <input type="text" name="name" id="name" value="<?php echo $data['playlist']->name; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Açıklama:</label>
                        <textarea name="description" id="description" rows="4"><?php echo $data['playlist']->description; ?></textarea>
                    </div>
                    
                    <div class="form-info">
                        <p><strong>Kullanıcı:</strong> <?php echo $data['playlist']->username; ?></p>
                        <p><strong>Oluşturulma Tarihi:</strong> <?php echo date('d.m.Y H:i', strtotime($data['playlist']->created_at)); ?></p>
                    </div>
                    
                    <div class="form-buttons">
                        <button type="submit" class="btn btn-success">Güncelle</button>
                    </div>
                </form>
            </div>
            
            <!-- Çalma Listesi Parçaları -->
            <div class="playlist-tracks">
                <div class="section-header">
                    <h2>Çalma Listesindeki Parçalar</h2>
                    <button class="btn btn-primary" type="button" id="showAddTrackModal">
                        <i class="fas fa-plus"></i> Parça Ekle
                    </button>
                </div>
                
                <div class="tracks-list">
                    <?php if(!empty($data['playlistTracks'])) : ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Başlık</th>
                                    <th>Tür</th>
                                    <th>Süre</th>
                                    <th>Eklenme Tarihi</th>
                                    <th>İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['playlistTracks'] as $track) : ?>
                                <tr>
                                    <td><?php echo $track->id; ?></td>
                                    <td><?php echo $track->title; ?></td>
                                    <td><?php echo $track->genre; ?></td>
                                    <td><?php echo floor($track->duration / 60) . ':' . str_pad($track->duration % 60, 2, '0', STR_PAD_LEFT); ?></td>
                                    <td><?php echo date('d.m.Y H:i', strtotime($track->added_at)); ?></td>
                                    <td>
                                        <a href="<?php echo URLROOT; ?>/admin/removeTrackFromPlaylist/<?php echo $data['playlist']->id; ?>/<?php echo $track->id; ?>" class="btn-sm btn-danger" onclick="return confirm('Bu parçayı çalma listesinden kaldırmak istediğinizden emin misiniz?');">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <div class="no-data">
                            <i class="fas fa-music"></i>
                            <p>Bu çalma listesinde henüz parça bulunmuyor.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Parça Ekle Modal -->
<div id="addTrackModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Çalma Listesine Parça Ekle</h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="search-container">
                <input type="text" id="trackSearchInput" placeholder="Parça ara...">
                <button id="searchTracksBtn" class="btn btn-primary">Ara</button>
            </div>
            
            <div class="tracks-list modal-tracks-list">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Başlık</th>
                            <th>Kullanıcı</th>
                            <th>Tür</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['tracks'] as $track) : ?>
                            <?php 
                                // Halihazırda çalma listesinde olan parçayı kontrol et
                                $isInPlaylist = false;
                                if(!empty($data['playlistTracks'])) {
                                    foreach($data['playlistTracks'] as $playlistTrack) {
                                        if($playlistTrack->id == $track->id) {
                                            $isInPlaylist = true;
                                            break;
                                        }
                                    }
                                }
                            ?>
                            <tr class="track-item" data-title="<?php echo strtolower($track->title); ?>">
                                <td><?php echo $track->id; ?></td>
                                <td><?php echo $track->title; ?></td>
                                <td><?php echo $track->username; ?></td>
                                <td><?php echo $track->genre; ?></td>
                                <td>
                                    <?php if($isInPlaylist) : ?>
                                        <button class="btn-sm btn-secondary" disabled>Eklendi</button>
                                    <?php else : ?>
                                        <a href="<?php echo URLROOT; ?>/admin/addTrackToPlaylist/<?php echo $data['playlist']->id; ?>/<?php echo $track->id; ?>" class="btn-sm btn-success">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
    
    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .playlist-edit-container {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .form-container, .playlist-tracks {
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        padding: 20px;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .section-header h2 {
        margin: 0;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
    }
    
    input[type="text"],
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
    
    .form-info {
        margin-top: 20px;
        padding: 15px;
        background: #f9f9f9;
        border-radius: 5px;
    }
    
    .form-buttons {
        margin-top: 20px;
    }
    
    .tracks-list {
        margin-top: 20px;
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
    
    .btn {
        display: inline-block;
        padding: 8px 15px;
        background: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 14px;
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
    
    .btn-primary {
        background: #007bff;
    }
    
    .btn-success {
        background: #28a745;
    }
    
    .btn-secondary {
        background: #6c757d;
    }
    
    .btn-danger {
        background: #dc3545;
    }
    
    .btn i {
        margin-right: 5px;
    }
    
    .no-data {
        padding: 30px 20px;
        text-align: center;
        color: #999;
    }
    
    .no-data i {
        font-size: 48px;
        margin-bottom: 10px;
    }
    
    /* Modal Stili */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
    }
    
    .modal-content {
        position: relative;
        background-color: #fff;
        margin: 5% auto;
        padding: 0;
        border-radius: 5px;
        width: 80%;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        animation: modalopen 0.4s;
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #eee;
    }
    
    .modal-header h2 {
        margin: 0;
    }
    
    .close {
        color: #999;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    
    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
    }
    
    .modal-body {
        padding: 20px;
    }
    
    .search-container {
        display: flex;
        margin-bottom: 20px;
    }
    
    .search-container input {
        flex: 1;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px 0 0 4px;
    }
    
    .search-container button {
        border-radius: 0 4px 4px 0;
    }
    
    .modal-tracks-list {
        max-height: 400px;
        overflow-y: auto;
    }
    
    @keyframes modalopen {
        from {opacity: 0; transform: scale(0.8);}
        to {opacity: 1; transform: scale(1);}
    }
    
    @media (min-width: 992px) {
        .playlist-edit-container {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>

<!-- JavaScript Kodları -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal İşlevleri
        const modal = document.getElementById('addTrackModal');
        const showModalBtn = document.getElementById('showAddTrackModal');
        const closeModalBtn = document.querySelector('.close');
        
        showModalBtn.addEventListener('click', function() {
            modal.style.display = 'block';
        });
        
        closeModalBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
        
        window.addEventListener('click', function(e) {
            if (e.target == modal) {
                modal.style.display = 'none';
            }
        });
        
        // Parça Arama İşlevi
        const searchBtn = document.getElementById('searchTracksBtn');
        const searchInput = document.getElementById('trackSearchInput');
        const trackItems = document.querySelectorAll('.track-item');
        
        searchBtn.addEventListener('click', filterTracks);
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                filterTracks();
            }
        });
        
        function filterTracks() {
            const term = searchInput.value.toLowerCase();
            
            trackItems.forEach(track => {
                const title = track.getAttribute('data-title');
                if(title.indexOf(term) > -1) {
                    track.style.display = '';
                } else {
                    track.style.display = 'none';
                }
            });
        }
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>