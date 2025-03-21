<?php
/**
 * Admin Model
 * Veritabanı yönetimi için model sınıfı
 */
class Admin {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Genel İstatistikler

    // Kullanıcı sayısını al
    public function getUserCount() {
        $this->db->query("SELECT COUNT(*) as count FROM users");
        $result = $this->db->single();
        return $result->count;
    }

    // Parça sayısını al
    public function getTrackCount() {
        $this->db->query("SELECT COUNT(*) as count FROM tracks");
        $result = $this->db->single();
        return $result->count;
    }

    // Çalma listesi sayısını al
    public function getPlaylistCount() {
        $this->db->query("SELECT COUNT(*) as count FROM playlists");
        $result = $this->db->single();
        return $result->count;
    }

    // Kullanıcı Yönetimi

    // Tüm kullanıcıları getir
    public function getUsers() {
        $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    // ID'ye göre kullanıcı getir
    public function getUserById($id) {
        $this->db->query("SELECT * FROM users WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Kullanıcı güncelle
    public function updateUser($id, $data) {
        $this->db->query("UPDATE users SET 
                        username = :username, 
                        email = :email, 
                        name = :name, 
                        bio = :bio, 
                        subscription_tier = :subscription_tier, 
                        monthly_generations = :monthly_generations, 
                        reset_date = :reset_date,
                        theme = :theme,
                        player_visualization = :player_visualization,
                        auto_play = :auto_play,
                        language = :language,
                        monthly_downloads = :monthly_downloads
                        WHERE id = :id");
        
        $this->db->bind(':id', $id);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':bio', $data['bio']);
        $this->db->bind(':subscription_tier', $data['subscription_tier']);
        $this->db->bind(':monthly_generations', $data['monthly_generations']);
        $this->db->bind(':reset_date', $data['reset_date']);
        $this->db->bind(':theme', $data['theme']);
        $this->db->bind(':player_visualization', $data['player_visualization']);
        $this->db->bind(':auto_play', isset($data['auto_play']) ? 1 : 0);
        $this->db->bind(':language', $data['language']);
        $this->db->bind(':monthly_downloads', $data['monthly_downloads']);

        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Kullanıcı sil
    public function deleteUser($id) {
        $this->db->query("DELETE FROM users WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Parça Yönetimi

    // Tüm parçaları getir
    public function getTracks() {
        $this->db->query("SELECT t.*, u.username as username FROM tracks t 
                        JOIN users u ON t.user_id = u.id 
                        ORDER BY t.created_at DESC");
        return $this->db->resultSet();
    }

    // ID'ye göre parça getir
    public function getTrackById($id) {
        $this->db->query("SELECT t.*, u.username as username FROM tracks t 
                        JOIN users u ON t.user_id = u.id 
                        WHERE t.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Parça güncelle
    public function updateTrack($id, $data) {
        $this->db->query("UPDATE tracks SET 
                        title = :title, 
                        prompt = :prompt, 
                        genre = :genre, 
                        duration = :duration, 
                        bpm = :bpm, 
                        `key` = :key
                        WHERE id = :id");
        
        $this->db->bind(':id', $id);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':prompt', $data['prompt']);
        $this->db->bind(':genre', $data['genre']);
        $this->db->bind(':duration', $data['duration']);
        $this->db->bind(':bpm', $data['bpm']);
        $this->db->bind(':key', $data['key']);

        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Parça sil
    public function deleteTrack($id) {
        $this->db->query("DELETE FROM tracks WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Çalma Listesi Yönetimi

    // Tüm çalma listelerini getir
    public function getPlaylists() {
        $this->db->query("SELECT p.*, u.username as username, COUNT(pt.track_id) as track_count 
                        FROM playlists p 
                        JOIN users u ON p.user_id = u.id 
                        LEFT JOIN playlist_tracks pt ON p.id = pt.playlist_id 
                        GROUP BY p.id
                        ORDER BY p.created_at DESC");
        return $this->db->resultSet();
    }

    // ID'ye göre çalma listesi getir
    public function getPlaylistById($id) {
        $this->db->query("SELECT p.*, u.username as username FROM playlists p 
                        JOIN users u ON p.user_id = u.id 
                        WHERE p.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Çalma listesi parçalarını getir
    public function getPlaylistTracks($playlistId) {
        $this->db->query("SELECT t.*, pt.added_at 
                        FROM playlist_tracks pt
                        JOIN tracks t ON pt.track_id = t.id
                        WHERE pt.playlist_id = :playlist_id
                        ORDER BY pt.added_at DESC");
        $this->db->bind(':playlist_id', $playlistId);
        return $this->db->resultSet();
    }

    // Çalma listesi güncelle
    public function updatePlaylist($id, $data) {
        $this->db->query("UPDATE playlists SET 
                        name = :name, 
                        description = :description
                        WHERE id = :id");
        
        $this->db->bind(':id', $id);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);

        // Execute
        return $this->db->execute();
    }

    // Çalma listesine parça ekle
    public function addTrackToPlaylist($playlistId, $trackId) {
        // Önce bu parçanın çalma listesinde olup olmadığını kontrol et
        $this->db->query("SELECT * FROM playlist_tracks 
                        WHERE playlist_id = :playlist_id AND track_id = :track_id");
        $this->db->bind(':playlist_id', $playlistId);
        $this->db->bind(':track_id', $trackId);
        
        if($this->db->rowCount() > 0) {
            return false; // Zaten eklenmiş
        }
        
        // Parçayı çalma listesine ekle
        $this->db->query("INSERT INTO playlist_tracks (playlist_id, track_id, added_at) 
                        VALUES (:playlist_id, :track_id, NOW())");
        $this->db->bind(':playlist_id', $playlistId);
        $this->db->bind(':track_id', $trackId);
        
        return $this->db->execute();
    }

    // Çalma listesinden parça kaldır
    public function removeTrackFromPlaylist($playlistId, $trackId) {
        $this->db->query("DELETE FROM playlist_tracks 
                        WHERE playlist_id = :playlist_id AND track_id = :track_id");
        $this->db->bind(':playlist_id', $playlistId);
        $this->db->bind(':track_id', $trackId);
        
        return $this->db->execute();
    }

    // Çalma listesi sil
    public function deletePlaylist($id) {
        $this->db->query("DELETE FROM playlists WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Favoriler Yönetimi

    // Tüm favorileri getir
    public function getFavorites() {
        $this->db->query("SELECT f.*, u.username, t.title as track_title 
                        FROM favorites f
                        JOIN users u ON f.user_id = u.id
                        JOIN tracks t ON f.track_id = t.id
                        ORDER BY f.created_at DESC");
        return $this->db->resultSet();
    }

    // Favori sil
    public function deleteFavorite($id) {
        $this->db->query("DELETE FROM favorites WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}