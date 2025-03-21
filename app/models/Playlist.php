<?php
/**
 * Playlist Model
 * Handles database operations for playlists
 */
class Playlist {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }

    // Add new playlist
    public function addPlaylist($data) {
        $this->db->query('INSERT INTO playlists (user_id, name, description, created_at) 
                          VALUES (:user_id, :name, :description, NOW())');
        
        // Bind values
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        
        // Execute
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    // Get all playlists for a user
    public function getUserPlaylists($userId) {
        $this->db->query('SELECT p.*, COUNT(pt.track_id) as track_count 
                          FROM playlists p 
                          LEFT JOIN playlist_tracks pt ON p.id = pt.playlist_id 
                          WHERE p.user_id = :user_id 
                          GROUP BY p.id 
                          ORDER BY p.created_at DESC');
                          
        $this->db->bind(':user_id', $userId);
        
        return $this->db->resultSet();
    }

    // Get playlist by ID
    public function getPlaylistById($id) {
        $this->db->query('SELECT * FROM playlists WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    // Update playlist
    public function updatePlaylist($data) {
        $this->db->query('UPDATE playlists SET name = :name, description = :description WHERE id = :id');
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':id', $data['id']);
        
        // Execute
        return $this->db->execute();
    }

    // Delete playlist
    public function deletePlaylist($id) {
        // Delete playlist tracks first (to maintain foreign key constraints)
        $this->db->query('DELETE FROM playlist_tracks WHERE playlist_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();
        
        // Delete the playlist
        $this->db->query('DELETE FROM playlists WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }

    // Add track to playlist
    public function addTrackToPlaylist($trackId, $playlistId) {
        // Check if track is already in playlist
        if ($this->isTrackInPlaylist($trackId, $playlistId)) {
            return true;
        }
        
        $this->db->query('INSERT INTO playlist_tracks (playlist_id, track_id, added_at) 
                          VALUES (:playlist_id, :track_id, NOW())');
                          
        $this->db->bind(':playlist_id', $playlistId);
        $this->db->bind(':track_id', $trackId);
        
        return $this->db->execute();
    }

    // Remove track from playlist
    public function removeTrackFromPlaylist($trackId, $playlistId) {
        $this->db->query('DELETE FROM playlist_tracks 
                          WHERE playlist_id = :playlist_id AND track_id = :track_id');
                          
        $this->db->bind(':playlist_id', $playlistId);
        $this->db->bind(':track_id', $trackId);
        
        return $this->db->execute();
    }

    // Check if track is in playlist
    public function isTrackInPlaylist($trackId, $playlistId) {
        $this->db->query('SELECT * FROM playlist_tracks 
                          WHERE playlist_id = :playlist_id AND track_id = :track_id');
                          
        $this->db->bind(':playlist_id', $playlistId);
        $this->db->bind(':track_id', $trackId);
        
        $this->db->execute();
        
        return $this->db->rowCount() > 0;
    }

    // Get all tracks in a playlist
    public function getPlaylistTracks($playlistId) {
        $this->db->query('SELECT t.*, pt.added_at 
                          FROM tracks t 
                          JOIN playlist_tracks pt ON t.id = pt.track_id 
                          WHERE pt.playlist_id = :playlist_id 
                          ORDER BY pt.added_at DESC');
                          
        $this->db->bind(':playlist_id', $playlistId);
        
        return $this->db->resultSet();
    }

    // Count user playlists
    public function countUserPlaylists($userId) {
        $this->db->query('SELECT COUNT(*) as count FROM playlists WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        
        $row = $this->db->single();
        return $row->count;
    }

    // Count tracks in playlist
    public function countPlaylistTracks($playlistId) {
        $this->db->query('SELECT COUNT(*) as count FROM playlist_tracks WHERE playlist_id = :playlist_id');
        $this->db->bind(':playlist_id', $playlistId);
        
        $row = $this->db->single();
        return $row->count;
    }

    // Get playlist duration (sum of all track durations)
    public function getPlaylistDuration($playlistId) {
        $this->db->query('SELECT SUM(t.duration) as total_duration 
                          FROM tracks t 
                          JOIN playlist_tracks pt ON t.id = pt.track_id 
                          WHERE pt.playlist_id = :playlist_id');
                          
        $this->db->bind(':playlist_id', $playlistId);
        
        $row = $this->db->single();
        return $row->total_duration ?? 0;
    }
}