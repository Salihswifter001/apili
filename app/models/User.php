<?php
/**
 * User Model
 * Handles database operations for users
 */
class User {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }

    // Register user
    public function register($data) {
        // Ensure downloads columns exist before registration
        $this->ensureDownloadColumns();
        
        $this->db->query('INSERT INTO users (username, email, password, subscription_tier, monthly_generations, reset_date, monthly_downloads, downloads_reset_date, created_at)
                         VALUES (:username, :email, :password, :subscription_tier, :monthly_generations, :reset_date, 0, NOW(), NOW())');
        
        // Bind values
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':subscription_tier', $data['subscription_tier']);
        $this->db->bind(':monthly_generations', $data['monthly_generations']);
        $this->db->bind(':reset_date', $data['reset_date']);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Find user by email
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        
        $row = $this->db->single();
        
        // Check row
        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    // Find user by username
    public function findUserByUsername($username) {
        $this->db->query('SELECT * FROM users WHERE username = :username');
        $this->db->bind(':username', $username);
        
        $row = $this->db->single();
        
        // Check row
        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    // Get user by ID
    public function getUserById($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        
        $row = $this->db->single();
        
        return $row;
    }

    // Update user
    public function updateUser($data) {
        // Ensure new profile columns exist
        $this->ensureProfileColumns();
        
        $query = 'UPDATE users SET
                  name = :name,
                  email = :email,
                  bio = :bio,
                  theme = :theme';
        
        // Add optional fields if they exist in data
        if (isset($data['website'])) {
            $query .= ', website = :website';
        }
        
        if (isset($data['location'])) {
            $query .= ', location = :location';
        }
        
        if (isset($data['social_twitter'])) {
            $query .= ', social_twitter = :social_twitter';
        }
        
        if (isset($data['social_instagram'])) {
            $query .= ', social_instagram = :social_instagram';
        }
        
        if (isset($data['social_soundcloud'])) {
            $query .= ', social_soundcloud = :social_soundcloud';
        }
        
        if (isset($data['social_spotify'])) {
            $query .= ', social_spotify = :social_spotify';
        }
        
        if (isset($data['color_scheme'])) {
            $query .= ', color_scheme = :color_scheme';
        }
        
        if (isset($data['enable_animations'])) {
            $query .= ', enable_animations = :enable_animations';
        }
        
        if (isset($data['enable_visualizer'])) {
            $query .= ', enable_visualizer = :enable_visualizer';
        }
        
        if (isset($data['dashboard_layout'])) {
            $query .= ', dashboard_layout = :dashboard_layout';
        }
        
        if (isset($data['enable_2fa'])) {
            $query .= ', enable_2fa = :enable_2fa';
        }
        
        if (isset($data['notify_login'])) {
            $query .= ', notify_login = :notify_login';
        }
                  
        // Add password to query if provided
        if (isset($data['password'])) {
            $query .= ', password = :password';
        }
        
        // Add avatar if provided
        if (isset($data['avatar'])) {
            $query .= ', avatar = :avatar';
        }
        
        $query .= ' WHERE id = :id';
        
        $this->db->query($query);
        
        // Bind required values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':bio', $data['bio'] ?? '');
        $this->db->bind(':theme', $data['theme']);
        $this->db->bind(':id', $data['id']);
        
        // Bind optional values if they exist
        if (isset($data['website'])) {
            $this->db->bind(':website', $data['website']);
        }
        
        if (isset($data['location'])) {
            $this->db->bind(':location', $data['location']);
        }
        
        if (isset($data['social_twitter'])) {
            $this->db->bind(':social_twitter', $data['social_twitter']);
        }
        
        if (isset($data['social_instagram'])) {
            $this->db->bind(':social_instagram', $data['social_instagram']);
        }
        
        if (isset($data['social_soundcloud'])) {
            $this->db->bind(':social_soundcloud', $data['social_soundcloud']);
        }
        
        if (isset($data['social_spotify'])) {
            $this->db->bind(':social_spotify', $data['social_spotify']);
        }
        
        if (isset($data['color_scheme'])) {
            $this->db->bind(':color_scheme', $data['color_scheme']);
        }
        
        if (isset($data['enable_animations'])) {
            $this->db->bind(':enable_animations', $data['enable_animations'] ? 1 : 0);
        }
        
        if (isset($data['enable_visualizer'])) {
            $this->db->bind(':enable_visualizer', $data['enable_visualizer'] ? 1 : 0);
        }
        
        if (isset($data['dashboard_layout'])) {
            $this->db->bind(':dashboard_layout', $data['dashboard_layout']);
        }
        
        if (isset($data['enable_2fa'])) {
            $this->db->bind(':enable_2fa', $data['enable_2fa'] ? 1 : 0);
        }
        
        if (isset($data['notify_login'])) {
            $this->db->bind(':notify_login', $data['notify_login'] ? 1 : 0);
        }
        
        // Bind password if provided
        if (isset($data['password'])) {
            $this->db->bind(':password', $data['password']);
        }
        
        // Bind avatar if provided
        if (isset($data['avatar'])) {
            $this->db->bind(':avatar', $data['avatar']);
        }
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Update subscription
    public function updateSubscription($data) {
        $this->db->query('UPDATE users SET subscription_tier = :subscription_tier WHERE id = :id');
        
        // Bind values
        $this->db->bind(':subscription_tier', $data['subscription_tier']);
        $this->db->bind(':id', $data['id']);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Update preferences
    public function updatePreferences($data) {
        $this->db->query('UPDATE users SET 
                          theme = :theme, 
                          player_visualization = :player_visualization,
                          auto_play = :auto_play,
                          language = :language
                          WHERE id = :id');
        
        // Bind values
        $this->db->bind(':theme', $data['theme']);
        $this->db->bind(':player_visualization', $data['player_visualization']);
        $this->db->bind(':auto_play', $data['auto_play']);
        $this->db->bind(':language', $data['language']);
        $this->db->bind(':id', $data['id']);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Reset monthly generations
    public function resetMonthlyGenerations($userId) {
        $this->db->query('UPDATE users SET monthly_generations = 0 WHERE id = :id');
        
        // Bind value
        $this->db->bind(':id', $userId);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Update reset date
    public function updateResetDate($userId, $resetDate) {
        $this->db->query('UPDATE users SET reset_date = :reset_date WHERE id = :id');
        
        // Bind values
        $this->db->bind(':reset_date', $resetDate);
        $this->db->bind(':id', $userId);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Increment monthly generation count
    public function incrementGenerationCount($userId) {
        $this->db->query('UPDATE users SET monthly_generations = monthly_generations + 1 WHERE id = :id');
        
        // Bind value
        $this->db->bind(':id', $userId);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Save password reset token
    public function saveResetToken($email, $token) {
        $this->db->query('UPDATE users SET reset_token = :token, reset_token_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = :email');
        
        // Bind values
        $this->db->bind(':token', $token);
        $this->db->bind(':email', $email);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Find user by reset token
    public function findUserByResetToken($token) {
        $this->db->query('SELECT * FROM users WHERE reset_token = :token AND reset_token_expires > NOW()');
        $this->db->bind(':token', $token);
        
        $row = $this->db->single();
        
        // Check row
        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    // Reset password
    public function resetPassword($userId, $password) {
        $this->db->query('UPDATE users SET password = :password, reset_token = NULL, reset_token_expires = NULL WHERE id = :id');
        
        // Bind values
        $this->db->bind(':password', $password);
        $this->db->bind(':id', $userId);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Get the monthly download count for a user
     *
     * @param int $userId User ID
     * @return int Monthly downloads
     */
    public function getMonthlyDownloads($userId) {
        // Ensure monthly_downloads column exists
        $this->ensureDownloadColumns();
        
        $this->db->query('SELECT monthly_downloads FROM users WHERE id = :id');
        $this->db->bind(':id', $userId);
        
        $result = $this->db->single();
        return $result ? $result->monthly_downloads : 0;
    }
    
    /**
     * Increment monthly download count
     *
     * @param int $userId User ID
     * @return bool Success or failure
     */
    public function incrementDownloadCount($userId) {
        // Ensure monthly_downloads column exists
        $this->ensureDownloadColumns();
        
        $this->db->query('UPDATE users SET monthly_downloads = monthly_downloads + 1 WHERE id = :id');
        
        // Bind value
        $this->db->bind(':id', $userId);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Reset monthly downloads count
     *
     * @param int $userId User ID
     * @return bool Success or failure
     */
    public function resetMonthlyDownloads($userId) {
        // Ensure monthly_downloads column exists
        $this->ensureDownloadColumns();
        
        $this->db->query('UPDATE users SET monthly_downloads = 0 WHERE id = :id');
        
        // Bind value
        $this->db->bind(':id', $userId);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Update the language preference for a user
     *
     * @param int $userId User ID
     * @param string $language Language code
     * @return bool Success or failure
     */
    public function updateUserLanguage($userId, $language) {
        $this->db->query('UPDATE users SET language = :language WHERE id = :id');
        
        // Bind values
        $this->db->bind(':language', $language);
        $this->db->bind(':id', $userId);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Ensure that the users table has the required download tracking columns
     * This method adds the columns if they don't exist
     *
     * @return void
     */
    private function ensureDownloadColumns() {
        // Check if the monthly_downloads column exists using INFORMATION_SCHEMA
        $this->db->query("SELECT COLUMN_NAME
                         FROM INFORMATION_SCHEMA.COLUMNS
                         WHERE TABLE_SCHEMA = DATABASE()
                         AND TABLE_NAME = 'users'
                         AND COLUMN_NAME = 'monthly_downloads'");
        
        $result = $this->db->single();
        
        if (!$result) {
            // Add the column
            $this->db->query("ALTER TABLE users ADD monthly_downloads INT NOT NULL DEFAULT 0");
            $this->db->execute();
        }
        
        // Check if the downloads_reset_date column exists using INFORMATION_SCHEMA
        $this->db->query("SELECT COLUMN_NAME
                         FROM INFORMATION_SCHEMA.COLUMNS
                         WHERE TABLE_SCHEMA = DATABASE()
                         AND TABLE_NAME = 'users'
                         AND COLUMN_NAME = 'downloads_reset_date'");
        
        $result = $this->db->single();
        
        if (!$result) {
            // Add the column with current date
            $this->db->query("ALTER TABLE users ADD downloads_reset_date DATE DEFAULT CURRENT_DATE()");
            $this->db->execute();
        }
    }
    
    /**
     * Ensure that the users table has the columns required for enhanced profile features
     * This method adds the columns if they don't exist
     *
     * @return void
     */
    private function ensureProfileColumns() {
        // Define the columns to check and add if they don't exist
        $profileColumns = [
            'website' => 'VARCHAR(255) NULL',
            'location' => 'VARCHAR(255) NULL',
            'social_twitter' => 'VARCHAR(100) NULL',
            'social_instagram' => 'VARCHAR(100) NULL',
            'social_soundcloud' => 'VARCHAR(100) NULL',
            'social_spotify' => 'VARCHAR(100) NULL',
            'color_scheme' => 'VARCHAR(50) DEFAULT "default"',
            'enable_animations' => 'TINYINT DEFAULT 1',
            'enable_visualizer' => 'TINYINT DEFAULT 1',
            'dashboard_layout' => 'VARCHAR(20) DEFAULT "grid"',
            'enable_2fa' => 'TINYINT DEFAULT 0',
            'notify_login' => 'TINYINT DEFAULT 0',
            'avatar' => 'VARCHAR(255) NULL'
        ];
        
        // Check for and add each column if it doesn't exist
        foreach ($profileColumns as $columnName => $columnDef) {
            $this->db->query("SELECT COLUMN_NAME
                             FROM INFORMATION_SCHEMA.COLUMNS
                             WHERE TABLE_SCHEMA = DATABASE()
                             AND TABLE_NAME = 'users'
                             AND COLUMN_NAME = :column_name");
            
            $this->db->bind(':column_name', $columnName);
            $result = $this->db->single();
            
            if (!$result) {
                // Column doesn't exist, so add it
                $this->db->query("ALTER TABLE users ADD $columnName $columnDef");
                $this->db->execute();
            }
        }
    }
}