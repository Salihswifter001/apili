<?php
/**
 * Music Model
 * Handles database operations for music tracks
 */
class MusicModel {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }

    // Add new track
    public function addTrack($data) {
        $this->db->query('INSERT INTO tracks (user_id, title, prompt, audio_url, parameters, genre, duration, bpm, `key`, created_at) 
                          VALUES (:user_id, :title, :prompt, :audio_url, :parameters, :genre, :duration, :bpm, :key, NOW())');
        
        // Bind values
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':prompt', $data['prompt']);
        $this->db->bind(':audio_url', $data['audio_url']);
        $this->db->bind(':parameters', $data['parameters']);
        $this->db->bind(':genre', $data['genre']);
        $this->db->bind(':duration', $data['duration']);
        $this->db->bind(':bpm', $data['bpm']);
        $this->db->bind(':key', $data['key']);
        
        // Execute
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    // Get all tracks for a user
    public function getUserTracks($userId) {
        $this->db->query('SELECT * FROM tracks WHERE user_id = :user_id ORDER BY created_at DESC');
        $this->db->bind(':user_id', $userId);
        
        return $this->db->resultSet();
    }

    // Get recent tracks for a user
    public function getRecentTracks($userId, $limit = 10) {
        $this->db->query('SELECT * FROM tracks WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :limit');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }

    // Get favorite tracks for a user
    public function getFavoriteTracks($userId, $limit = null) {
        $query = 'SELECT t.* FROM tracks t 
                  JOIN favorites f ON t.id = f.track_id 
                  WHERE f.user_id = :user_id 
                  ORDER BY f.created_at DESC';
                  
        if ($limit) {
            $query .= ' LIMIT :limit';
        }
        
        $this->db->query($query);
        $this->db->bind(':user_id', $userId);
        
        if ($limit) {
            $this->db->bind(':limit', $limit);
        }
        
        return $this->db->resultSet();
    }

    // Get track by ID
    public function getTrackById($id) {
        $this->db->query('SELECT * FROM tracks WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    // Update track
    public function updateTrack($data) {
        $this->db->query('UPDATE tracks SET title = :title, genre = :genre WHERE id = :id');
        
        // Bind values
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':genre', $data['genre']);
        $this->db->bind(':id', $data['id']);
        
        // Execute
        return $this->db->execute();
    }

    // Delete track
    public function deleteTrack($id) {
        // Delete from favorites first (to maintain foreign key constraints)
        $this->db->query('DELETE FROM favorites WHERE track_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();
        
        // Delete from playlists
        $this->db->query('DELETE FROM playlist_tracks WHERE track_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();
        
        // Delete the track
        $this->db->query('DELETE FROM tracks WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }

    // Check if track is in favorites
    public function isTrackFavorite($userId, $trackId) {
        $this->db->query('SELECT * FROM favorites WHERE user_id = :user_id AND track_id = :track_id');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':track_id', $trackId);
        
        $this->db->execute();
        
        return $this->db->rowCount() > 0;
    }

    // Add track to favorites
    public function addToFavorites($userId, $trackId) {
        // Check if already in favorites
        if ($this->isTrackFavorite($userId, $trackId)) {
            return true;
        }
        
        $this->db->query('INSERT INTO favorites (user_id, track_id, created_at) VALUES (:user_id, :track_id, NOW())');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':track_id', $trackId);
        
        return $this->db->execute();
    }

    // Remove track from favorites
    public function removeFromFavorites($userId, $trackId) {
        $this->db->query('DELETE FROM favorites WHERE user_id = :user_id AND track_id = :track_id');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':track_id', $trackId);
        
        return $this->db->execute();
    }

    // Count user tracks
    public function countUserTracks($userId) {
        $this->db->query('SELECT COUNT(*) as count FROM tracks WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        
        $row = $this->db->single();
        return $row->count;
    }

    // Count user favorites
    public function countUserFavorites($userId) {
        $this->db->query('SELECT COUNT(*) as count FROM favorites WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        
        $row = $this->db->single();
        return $row->count;
    }

    // Get tracks by genre
    public function getTracksByGenre($userId, $genre) {
        $this->db->query('SELECT * FROM tracks WHERE user_id = :user_id AND genre = :genre ORDER BY created_at DESC');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':genre', $genre);
        
        return $this->db->resultSet();
    }

    // Search tracks
    public function searchTracks($userId, $searchTerm) {
        $this->db->query('SELECT * FROM tracks
                         WHERE user_id = :user_id
                         AND (title LIKE :search
                         OR prompt LIKE :search
                         OR genre LIKE :search)
                         ORDER BY created_at DESC');
                         
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':search', '%' . $searchTerm . '%');
        
        return $this->db->resultSet();
    }
    
    /**
     * Increment the share count for a track
     *
     * @param int $trackId Track ID
     * @return bool Success or failure
     */
    public function incrementShareCount($trackId) {
        // Check if share_count column exists, if not add it
        $this->ensureTrackStatsColumns();
        
        $this->db->query('UPDATE tracks SET share_count = share_count + 1 WHERE id = :id');
        $this->db->bind(':id', $trackId);
        
        return $this->db->execute();
    }
    
    /**
     * Increment the embed count for a track
     *
     * @param int $trackId Track ID
     * @return bool Success or failure
     */
    public function incrementEmbedCount($trackId) {
        // Check if embed_count column exists, if not add it
        $this->ensureTrackStatsColumns();
        
        $this->db->query('UPDATE tracks SET embed_count = embed_count + 1 WHERE id = :id');
        $this->db->bind(':id', $trackId);
        
        return $this->db->execute();
    }
    
    /**
     * Increment the download count for a track
     *
     * @param int $trackId Track ID
     * @return bool Success or failure
     */
    public function incrementDownloadCount($trackId) {
        // Check if download_count column exists, if not add it
        $this->ensureTrackStatsColumns();
        
        $this->db->query('UPDATE tracks SET download_count = download_count + 1 WHERE id = :id');
        $this->db->bind(':id', $trackId);
        
        return $this->db->execute();
    }
    
    /**
     * Get the share count for a track
     *
     * @param int $trackId Track ID
     * @return int Share count
     */
    public function getShareCount($trackId) {
        $this->db->query('SELECT share_count FROM tracks WHERE id = :id');
        $this->db->bind(':id', $trackId);
        
        $result = $this->db->single();
        return $result ? $result->share_count : 0;
    }
    
    /**
     * Get the embed count for a track
     *
     * @param int $trackId Track ID
     * @return int Embed count
     */
    public function getEmbedCount($trackId) {
        $this->db->query('SELECT embed_count FROM tracks WHERE id = :id');
        $this->db->bind(':id', $trackId);
        
        $result = $this->db->single();
        return $result ? $result->embed_count : 0;
    }
    
    /**
     * Get the download count for a track
     *
     * @param int $trackId Track ID
     * @return int Download count
     */
    public function getDownloadCount($trackId) {
        $this->db->query('SELECT download_count FROM tracks WHERE id = :id');
        $this->db->bind(':id', $trackId);
        
        $result = $this->db->single();
        return $result ? $result->download_count : 0;
    }
    
    /**
     * Ensure that the tracks table has the required statistics columns
     * This method adds the columns if they don't exist
     *
     * @return void
     */
    private function ensureTrackStatsColumns() {
        // Check if the share_count column exists
        $this->db->query("SHOW COLUMNS FROM tracks LIKE 'share_count'");
        if ($this->db->rowCount() == 0) {
            // Add the column
            $this->db->query("ALTER TABLE tracks ADD share_count INT NOT NULL DEFAULT 0");
            $this->db->execute();
        }
        
        // Check if the embed_count column exists
        $this->db->query("SHOW COLUMNS FROM tracks LIKE 'embed_count'");
        if ($this->db->rowCount() == 0) {
            // Add the column
            $this->db->query("ALTER TABLE tracks ADD embed_count INT NOT NULL DEFAULT 0");
            $this->db->execute();
        }
        
        // Check if the download_count column exists
        $this->db->query("SHOW COLUMNS FROM tracks LIKE 'download_count'");
        if ($this->db->rowCount() == 0) {
            // Add the column
            $this->db->query("ALTER TABLE tracks ADD download_count INT NOT NULL DEFAULT 0");
            $this->db->execute();
        }
    }
}