-- Octaverum AI Complete Database Schema

-- No CREATE DATABASE or USE statement - you'll select database in phpMyAdmin first

-- Drop existing tables (for clean reinitialization)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS playlist_tracks;
DROP TABLE IF EXISTS favorites;
DROP TABLE IF EXISTS tracks;
DROP TABLE IF EXISTS playlists;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- Create users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100),
    bio TEXT,
    subscription_tier ENUM('free', 'premium', 'professional') NOT NULL DEFAULT 'free',
    monthly_generations INT NOT NULL DEFAULT 0,
    reset_date DATE NOT NULL,
    theme VARCHAR(50) DEFAULT 'dark',
    player_visualization VARCHAR(50) DEFAULT 'waveform',
    auto_play TINYINT(1) DEFAULT 0,
    language VARCHAR(10) DEFAULT 'en',
    reset_token VARCHAR(255) DEFAULT NULL,
    reset_token_expires DATETIME DEFAULT NULL,
    monthly_downloads INT NOT NULL DEFAULT 0,
    downloads_reset_date DATE DEFAULT (CURRENT_DATE),
    created_at DATETIME NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create tracks table
CREATE TABLE tracks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    prompt TEXT NOT NULL,
    audio_url VARCHAR(255) NOT NULL,
    parameters TEXT NOT NULL,
    genre VARCHAR(50) DEFAULT 'electronic',
    duration INT NOT NULL, -- in seconds
    bpm INT NOT NULL,
    `key` VARCHAR(10) NOT NULL,
    share_count INT NOT NULL DEFAULT 0,
    embed_count INT NOT NULL DEFAULT 0,
    download_count INT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create playlists table
CREATE TABLE playlists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at DATETIME NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create favorites table
CREATE TABLE favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    track_id INT NOT NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (track_id) REFERENCES tracks(id) ON DELETE CASCADE,
    UNIQUE KEY (user_id, track_id)
);

-- Create playlist_tracks table (many-to-many relationship)
CREATE TABLE playlist_tracks (
    playlist_id INT NOT NULL,
    track_id INT NOT NULL,
    added_at DATETIME NOT NULL,
    PRIMARY KEY (playlist_id, track_id),
    FOREIGN KEY (playlist_id) REFERENCES playlists(id) ON DELETE CASCADE,
    FOREIGN KEY (track_id) REFERENCES tracks(id) ON DELETE CASCADE
);

-- Create sample admin user (password: admin123)
INSERT INTO users (username, email, password, name, subscription_tier, monthly_generations, reset_date, created_at)
VALUES ('admin', 'admin@octaverum.ai', '$2y$10$f7OfIRY2fy0c6PsP0QHUy.I33XtCo5KfzEkJgpJX63gJ60hmTYlVi', 'Admin User', 'professional', 0, DATE_ADD(NOW(), INTERVAL 1 MONTH), NOW());

-- Create sample regular user (password: user123)
INSERT INTO users (username, email, password, name, subscription_tier, monthly_generations, reset_date, created_at)
VALUES ('user', 'user@octaverum.ai', '$2y$10$Y8ZXR8IXjWLBGfFgC0ydP.UIdVA2m9FFQ6Jt3tJXhjW1nRQXBT4b2', 'Demo User', 'free', 5, DATE_ADD(NOW(), INTERVAL 1 MONTH), NOW());

-- Insert sample tracks for demo purposes
INSERT INTO tracks (user_id, title, prompt, audio_url, parameters, genre, duration, bpm, `key`, created_at) 
VALUES 
(2, 'Electronic Dreams', 'Create an upbeat electronic track with synth leads', '/samples/track1.mp3', '{"model":"v1","seed":12345}', 'electronic', 180, 128, 'C minor', NOW()),
(2, 'Ambient Space', 'Generate a relaxing ambient track with space theme', '/samples/track2.mp3', '{"model":"v1","seed":67890}', 'ambient', 240, 85, 'G major', NOW()),
(1, 'Professional Demo', 'High quality orchestral piece with strings', '/samples/track3.mp3', '{"model":"v2","seed":55555}', 'orchestral', 210, 95, 'D major', NOW());

-- Create sample playlists
INSERT INTO playlists (user_id, name, description, created_at)
VALUES 
(2, 'My Favorites', 'Collection of my favorite generated tracks', NOW()),
(2, 'Chill Vibes', 'Relaxing tracks for study sessions', NOW()),
(1, 'Demo Collection', 'Showcase of professional tier capabilities', NOW());

-- Add tracks to playlists
INSERT INTO playlist_tracks (playlist_id, track_id, added_at)
VALUES 
(1, 1, NOW()),
(1, 2, NOW()),
(2, 2, NOW()),
(3, 3, NOW());

-- Add favorites
INSERT INTO favorites (user_id, track_id, created_at)
VALUES 
(2, 1, NOW()),
(2, 2, NOW()),
(1, 3, NOW());