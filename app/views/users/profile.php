<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="profile-container">
    <div class="profile-header">
        <div class="container">
            <div class="profile-header-content">
                <div class="profile-avatar-container">
                    <div class="profile-avatar">
                        <div class="avatar-preview" id="avatarPreview">
                            <?php if(!empty($data['user']->avatar)): ?>
                                <img src="<?php echo $data['user']->avatar; ?>" alt="Profile Avatar">
                            <?php else: ?>
                                <div class="avatar-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="avatar-upload-overlay">
                            <label for="avatarUpload" class="avatar-upload-btn">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" id="avatarUpload" class="avatar-upload-input" accept="image/*">
                        </div>
                    </div>
                    <div class="avatar-status <?php echo $data['user']->subscription_tier; ?>">
                        <span><?php echo ucfirst($data['user']->subscription_tier); ?></span>
                    </div>
                </div>
                <div class="profile-user-info">
                    <h1 class="profile-username"><?php echo htmlspecialchars($data['user']->username); ?></h1>
                    <div class="profile-stats">
                        <div class="stat-item">
                            <div class="stat-value"><?php echo isset($data['user']->monthly_generations) ? $data['user']->monthly_generations : 0; ?></div>
                            <div class="stat-label"><?php echo __('tracks_generated', 'Tracks Generated'); ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">
                                <?php 
                                $monthlyLimit = $data['user']->subscription_tier === 'professional' ? 'Unlimited' :
                                              ($data['user']->subscription_tier === 'premium' ? PREMIUM_GENERATION_LIMIT : FREE_GENERATION_LIMIT);
                                echo $monthlyLimit;
                                ?>
                            </div>
                            <div class="stat-label"><?php echo __('monthly_limit', 'Monthly Limit'); ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value"><?php echo isset($data['user']->created_at) ? date('M Y', strtotime($data['user']->created_at)) : '-'; ?></div>
                            <div class="stat-label"><?php echo __('member_since', 'Member Since'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Tab navigation -->
        <div class="profile-tabs">
            <button class="tab-btn active" data-tab="personalInfo"><?php echo __('personal_info', 'Personal Info'); ?></button>
            <button class="tab-btn" data-tab="appearance"><?php echo __('appearance', 'Appearance'); ?></button>
            <button class="tab-btn" data-tab="security"><?php echo __('security', 'Security'); ?></button>
            <button class="tab-btn" data-tab="activity"><?php echo __('activity', 'Activity'); ?></button>
        </div>

        <?php flash('profile_success'); ?>

        <!-- Tab content -->
        <div class="profile-content">
            <!-- Personal Info Tab -->
            <div class="tab-content active" id="personalInfo">
                <form action="<?php echo URL_ROOT; ?>/users/profile" method="post" class="profile-form">
                    <div class="form-section">
                        <h2 class="section-title"><?php echo __('basic_info', 'Basic Information'); ?></h2>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name"><?php echo __('full_name', 'Full Name'); ?> <span class="required">*</span></label>
                                <div class="input-with-icon">
                                    <i class="fas fa-user"></i>
                                    <input type="text" name="name" id="name" value="<?php echo isset($data['user']->name) ? htmlspecialchars($data['user']->name) : ''; ?>" class="<?php echo (!empty($data['errors']['name'])) ? 'is-invalid' : ''; ?>" required>
                                </div>
                                <span class="invalid-feedback"><?php echo $data['errors']['name'] ?? ''; ?></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="email"><?php echo __('email', 'Email'); ?> <span class="required">*</span></label>
                                <div class="input-with-icon">
                                    <i class="fas fa-envelope"></i>
                                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($data['user']->email); ?>" class="<?php echo (!empty($data['errors']['email'])) ? 'is-invalid' : ''; ?>" required>
                                </div>
                                <span class="invalid-feedback"><?php echo $data['errors']['email'] ?? ''; ?></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="bio"><?php echo __('bio', 'Bio'); ?></label>
                            <textarea name="bio" id="bio" rows="4"><?php echo isset($data['user']->bio) ? htmlspecialchars($data['user']->bio) : ''; ?></textarea>
                            <span class="form-text"><?php echo __('bio_help', 'Tell others about yourself, your music style, and interests.'); ?></span>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h2 class="section-title"><?php echo __('contact_info', 'Contact Information'); ?></h2>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="website"><?php echo __('website', 'Website'); ?></label>
                                <div class="input-with-icon">
                                    <i class="fas fa-globe"></i>
                                    <input type="url" name="website" id="website" value="<?php echo isset($data['user']->website) ? htmlspecialchars($data['user']->website) : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="location"><?php echo __('location', 'Location'); ?></label>
                                <div class="input-with-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <input type="text" name="location" id="location" value="<?php echo isset($data['user']->location) ? htmlspecialchars($data['user']->location) : ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="social-media-links">
                            <h3><?php echo __('social_media', 'Social Media'); ?></h3>
                            
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="twitter"><?php echo __('twitter', 'Twitter'); ?></label>
                                    <div class="input-with-icon">
                                        <i class="fab fa-twitter"></i>
                                        <input type="text" name="social_twitter" id="twitter" value="<?php echo isset($data['user']->social_twitter) ? htmlspecialchars($data['user']->social_twitter) : ''; ?>" placeholder="@username">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="instagram"><?php echo __('instagram', 'Instagram'); ?></label>
                                    <div class="input-with-icon">
                                        <i class="fab fa-instagram"></i>
                                        <input type="text" name="social_instagram" id="instagram" value="<?php echo isset($data['user']->social_instagram) ? htmlspecialchars($data['user']->social_instagram) : ''; ?>" placeholder="username">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="soundcloud"><?php echo __('soundcloud', 'SoundCloud'); ?></label>
                                    <div class="input-with-icon">
                                        <i class="fab fa-soundcloud"></i>
                                        <input type="text" name="social_soundcloud" id="soundcloud" value="<?php echo isset($data['user']->social_soundcloud) ? htmlspecialchars($data['user']->social_soundcloud) : ''; ?>" placeholder="username">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="spotify"><?php echo __('spotify', 'Spotify'); ?></label>
                                    <div class="input-with-icon">
                                        <i class="fab fa-spotify"></i>
                                        <input type="text" name="social_spotify" id="spotify" value="<?php echo isset($data['user']->social_spotify) ? htmlspecialchars($data['user']->social_spotify) : ''; ?>" placeholder="profile ID">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-action">
                        <button type="submit" class="btn btn-primary"><?php echo __('save_changes', 'Save Changes'); ?></button>
                    </div>
                </form>
            </div>
            
            <!-- Appearance Tab -->
            <div class="tab-content" id="appearance">
                <form action="<?php echo URL_ROOT; ?>/users/profile" method="post" class="profile-form">
                    <div class="form-section">
                        <h2 class="section-title"><?php echo __('theme_settings', 'Theme Settings'); ?></h2>
                        
                        <div class="theme-selector">
                            <div class="theme-option <?php echo ($data['user']->theme == 'dark') ? 'active' : ''; ?>" data-theme="dark">
                                <div class="theme-preview dark-theme">
                                    <div class="preview-header"></div>
                                    <div class="preview-content">
                                        <div class="preview-sidebar"></div>
                                        <div class="preview-main"></div>
                                    </div>
                                </div>
                                <div class="theme-label">
                                    <input type="radio" name="theme" value="dark" id="theme-dark" <?php echo ($data['user']->theme == 'dark') ? 'checked' : ''; ?>>
                                    <label for="theme-dark"><?php echo __('dark_theme', 'Dark Theme'); ?></label>
                                </div>
                            </div>
                            
                            <div class="theme-option <?php echo ($data['user']->theme == 'light') ? 'active' : ''; ?>" data-theme="light">
                                <div class="theme-preview light-theme">
                                    <div class="preview-header"></div>
                                    <div class="preview-content">
                                        <div class="preview-sidebar"></div>
                                        <div class="preview-main"></div>
                                    </div>
                                </div>
                                <div class="theme-label">
                                    <input type="radio" name="theme" value="light" id="theme-light" <?php echo ($data['user']->theme == 'light') ? 'checked' : ''; ?>>
                                    <label for="theme-light"><?php echo __('light_theme', 'Light Theme'); ?></label>
                                </div>
                            </div>
                            
                            <div class="theme-option <?php echo ($data['user']->theme == 'neon') ? 'active' : ''; ?>" data-theme="neon">
                                <div class="theme-preview neon-theme">
                                    <div class="preview-header"></div>
                                    <div class="preview-content">
                                        <div class="preview-sidebar"></div>
                                        <div class="preview-main"></div>
                                    </div>
                                </div>
                                <div class="theme-label">
                                    <input type="radio" name="theme" value="neon" id="theme-neon" <?php echo ($data['user']->theme == 'neon') ? 'checked' : ''; ?>>
                                    <label for="theme-neon"><?php echo __('neon_theme', 'Neon Theme'); ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h2 class="section-title"><?php echo __('color_scheme', 'Color Scheme'); ?></h2>
                        
                        <div class="color-scheme-selector">
                            <div class="color-option <?php echo (!isset($data['user']->color_scheme) || $data['user']->color_scheme == 'default') ? 'active' : ''; ?>" data-color="default">
                                <div class="color-preview default-color">
                                    <span class="color-dot primary"></span>
                                    <span class="color-dot secondary"></span>
                                </div>
                                <div class="color-label">
                                    <input type="radio" name="color_scheme" value="default" id="color-default" <?php echo (!isset($data['user']->color_scheme) || $data['user']->color_scheme == 'default') ? 'checked' : ''; ?>>
                                    <label for="color-default"><?php echo __('default', 'Default'); ?></label>
                                </div>
                            </div>
                            
                            <div class="color-option <?php echo (isset($data['user']->color_scheme) && $data['user']->color_scheme == 'purple') ? 'active' : ''; ?>" data-color="purple">
                                <div class="color-preview purple-color">
                                    <span class="color-dot primary"></span>
                                    <span class="color-dot secondary"></span>
                                </div>
                                <div class="color-label">
                                    <input type="radio" name="color_scheme" value="purple" id="color-purple" <?php echo (isset($data['user']->color_scheme) && $data['user']->color_scheme == 'purple') ? 'checked' : ''; ?>>
                                    <label for="color-purple"><?php echo __('purple_dream', 'Purple Dream'); ?></label>
                                </div>
                            </div>
                            
                            <div class="color-option <?php echo (isset($data['user']->color_scheme) && $data['user']->color_scheme == 'ocean') ? 'active' : ''; ?>" data-color="ocean">
                                <div class="color-preview ocean-color">
                                    <span class="color-dot primary"></span>
                                    <span class="color-dot secondary"></span>
                                </div>
                                <div class="color-label">
                                    <input type="radio" name="color_scheme" value="ocean" id="color-ocean" <?php echo (isset($data['user']->color_scheme) && $data['user']->color_scheme == 'ocean') ? 'checked' : ''; ?>>
                                    <label for="color-ocean"><?php echo __('ocean_blue', 'Ocean Blue'); ?></label>
                                </div>
                            </div>
                            
                            <div class="color-option <?php echo (isset($data['user']->color_scheme) && $data['user']->color_scheme == 'sunset') ? 'active' : ''; ?>" data-color="sunset">
                                <div class="color-preview sunset-color">
                                    <span class="color-dot primary"></span>
                                    <span class="color-dot secondary"></span>
                                </div>
                                <div class="color-label">
                                    <input type="radio" name="color_scheme" value="sunset" id="color-sunset" <?php echo (isset($data['user']->color_scheme) && $data['user']->color_scheme == 'sunset') ? 'checked' : ''; ?>>
                                    <label for="color-sunset"><?php echo __('sunset_vibes', 'Sunset Vibes'); ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h2 class="section-title"><?php echo __('interface_options', 'Interface Options'); ?></h2>
                        
                        <div class="form-group">
                            <div class="checkbox-wrapper">
                                <input type="checkbox" name="enable_animations" id="enable-animations" <?php echo (isset($data['user']->enable_animations) && $data['user']->enable_animations) ? 'checked' : ''; ?>>
                                <label for="enable-animations"><?php echo __('enable_animations', 'Enable Animations'); ?></label>
                            </div>
                            <span class="form-text"><?php echo __('animations_help', 'Show animations throughout the interface for a more dynamic experience.'); ?></span>
                        </div>
                        
                        <div class="form-group">
                            <div class="checkbox-wrapper">
                                <input type="checkbox" name="enable_visualizer" id="enable-visualizer" <?php echo (isset($data['user']->enable_visualizer) && $data['user']->enable_visualizer) ? 'checked' : ''; ?>>
                                <label for="enable-visualizer"><?php echo __('enable_visualizer', 'Enable Audio Visualizer'); ?></label>
                            </div>
                            <span class="form-text"><?php echo __('visualizer_help', 'Show audio visualizer when playing tracks for a more immersive experience.'); ?></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="dashboard-layout"><?php echo __('dashboard_layout', 'Dashboard Layout'); ?></label>
                            <select name="dashboard_layout" id="dashboard-layout">
                                <option value="grid" <?php echo (isset($data['user']->dashboard_layout) && $data['user']->dashboard_layout == 'grid') ? 'selected' : ''; ?>><?php echo __('grid_layout', 'Grid Layout'); ?></option>
                                <option value="list" <?php echo (isset($data['user']->dashboard_layout) && $data['user']->dashboard_layout == 'list') ? 'selected' : ''; ?>><?php echo __('list_layout', 'List Layout'); ?></option>
                                <option value="compact" <?php echo (isset($data['user']->dashboard_layout) && $data['user']->dashboard_layout == 'compact') ? 'selected' : ''; ?>><?php echo __('compact_layout', 'Compact Layout'); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-action">
                        <button type="submit" class="btn btn-primary"><?php echo __('save_changes', 'Save Changes'); ?></button>
                    </div>
                </form>
            </div>
            
            <!-- Security Tab -->
            <div class="tab-content" id="security">
                <form action="<?php echo URL_ROOT; ?>/users/profile" method="post" class="profile-form">
                    <div class="form-section">
                        <h2 class="section-title"><?php echo __('change_password', 'Change Password'); ?></h2>
                        
                        <div class="form-group">
                            <label for="current_password"><?php echo __('current_password', 'Current Password'); ?> <span class="required">*</span></label>
                            <div class="input-with-icon">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="current_password" id="current_password" class="<?php echo (!empty($data['errors']['current_password'])) ? 'is-invalid' : ''; ?>">
                                <button type="button" class="password-toggle"><i class="fas fa-eye"></i></button>
                            </div>
                            <span class="invalid-feedback"><?php echo $data['errors']['current_password'] ?? ''; ?></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password"><?php echo __('new_password', 'New Password'); ?> <span class="required">*</span></label>
                            <div class="input-with-icon">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="new_password" id="new_password" class="<?php echo (!empty($data['errors']['new_password'])) ? 'is-invalid' : ''; ?>">
                                <button type="button" class="password-toggle"><i class="fas fa-eye"></i></button>
                            </div>
                            <span class="invalid-feedback"><?php echo $data['errors']['new_password'] ?? ''; ?></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_new_password"><?php echo __('confirm_new_password', 'Confirm New Password'); ?> <span class="required">*</span></label>
                            <div class="input-with-icon">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="confirm_new_password" id="confirm_new_password" class="<?php echo (!empty($data['errors']['confirm_new_password'])) ? 'is-invalid' : ''; ?>">
                                <button type="button" class="password-toggle"><i class="fas fa-eye"></i></button>
                            </div>
                            <span class="invalid-feedback"><?php echo $data['errors']['confirm_new_password'] ?? ''; ?></span>
                        </div>
                        
                        <div class="password-strength-meter">
                            <div class="password-strength-label"><?php echo __('password_strength', 'Password Strength'); ?>: <span id="strengthValue"><?php echo __('not_set', 'Not Set'); ?></span></div>
                            <div class="password-strength-bar">
                                <div class="strength-bar" id="strengthBar"></div>
                            </div>
                            <div class="password-requirements">
                                <ul>
                                    <li id="req-length"><i class="fas fa-circle"></i> <?php echo __('password_req_length', 'At least 8 characters'); ?></li>
                                    <li id="req-uppercase"><i class="fas fa-circle"></i> <?php echo __('password_req_uppercase', 'At least 1 uppercase letter'); ?></li>
                                    <li id="req-lowercase"><i class="fas fa-circle"></i> <?php echo __('password_req_lowercase', 'At least 1 lowercase letter'); ?></li>
                                    <li id="req-number"><i class="fas fa-circle"></i> <?php echo __('password_req_number', 'At least 1 number'); ?></li>
                                    <li id="req-special"><i class="fas fa-circle"></i> <?php echo __('password_req_special', 'At least 1 special character'); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h2 class="section-title"><?php echo __('account_security', 'Account Security'); ?></h2>
                        
                        <div class="form-group">
                            <div class="checkbox-wrapper">
                                <input type="checkbox" name="enable_2fa" id="enable-2fa" <?php echo (isset($data['user']->enable_2fa) && $data['user']->enable_2fa) ? 'checked' : ''; ?>>
                                <label for="enable-2fa"><?php echo __('enable_2fa', 'Enable Two-Factor Authentication'); ?></label>
                            </div>
                            <span class="form-text"><?php echo __('2fa_help', 'Add an extra layer of security to your account by requiring a verification code in addition to your password.'); ?></span>
                        </div>
                        
                        <div class="form-group">
                            <div class="checkbox-wrapper">
                                <input type="checkbox" name="notify_login" id="notify-login" <?php echo (isset($data['user']->notify_login) && $data['user']->notify_login) ? 'checked' : ''; ?>>
                                <label for="notify-login"><?php echo __('notify_login', 'Email me when there is a new login'); ?></label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-action">
                        <button type="submit" class="btn btn-primary"><?php echo __('save_changes', 'Save Changes'); ?></button>
                    </div>
                </form>
            </div>
            
            <!-- Activity Tab -->
            <div class="tab-content" id="activity">
                <div class="activity-summary">
                    <div class="form-section">
                        <h2 class="section-title"><?php echo __('activity_overview', 'Activity Overview'); ?></h2>
                        
                        <div class="activity-charts">
                            <div class="activity-chart">
                                <h3><?php echo __('generation_history', 'Generation History'); ?></h3>
                                <div class="chart-container">
                                    <canvas id="generationChart"></canvas>
                                </div>
                            </div>
                            
                            <div class="activity-chart">
                                <h3><?php echo __('genre_distribution', 'Genre Distribution'); ?></h3>
                                <div class="chart-container">
                                    <canvas id="genreChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h2 class="section-title"><?php echo __('recent_activity', 'Recent Activity'); ?></h2>
                        
                        <div class="activity-list">
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-music"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title"><?php echo __('created_track', 'Created new track'); ?> "Neon Dreams"</div>
                                    <div class="activity-time">2 <?php echo __('hours_ago', 'hours ago'); ?></div>
                                </div>
                            </div>
                            
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title"><?php echo __('updated_profile', 'Updated profile information'); ?></div>
                                    <div class="activity-time"><?php echo __('yesterday', 'Yesterday'); ?></div>
                                </div>
                            </div>
                            
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title"><?php echo __('favorited_track', 'Favorited track'); ?> "Cyber Horizon"</div>
                                    <div class="activity-time">3 <?php echo __('days_ago', 'days ago'); ?></div>
                                </div>
                            </div>
                            
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-plus"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title"><?php echo __('added_to_playlist', 'Added 3 tracks to playlist'); ?> "Ambient Flow"</div>
                                    <div class="activity-time">1 <?php echo __('week_ago', 'week ago'); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS for Profile Page -->
<style>
    /* Profile Container */
    .profile-container {
        margin-top: var(--header-height);
    }
    
    /* Profile Header */
    .profile-header {
        background-color: var(--bg-tertiary);
        padding: 3rem 0 2rem;
        border-bottom: 1px solid var(--border-color);
        position: relative;
        overflow: hidden;
    }
    
    .profile-header:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: 
            radial-gradient(circle at 15% 50%, rgba(121, 49, 255, 0.2) 0%, transparent 40%),
            radial-gradient(circle at 85% 30%, rgba(15, 247, 239, 0.2) 0%, transparent 40%);
        z-index: 0;
    }
    
    .profile-header-content {
        display: flex;
        align-items: center;
        gap: 2.5rem;
        position: relative;
        z-index: 1;
    }
    
    /* Profile Avatar */
    .profile-avatar-container {
        position: relative;
    }
    
    .profile-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        background-color: var(--bg-secondary);
        position: relative;
        border: 3px solid var(--accent-primary);
        box-shadow: 0 0 15px var(--glow-primary);
        transition: all 0.3s ease;
    }
    
    .profile-avatar:hover {
        transform: scale(1.05);
    }
    
    .avatar-preview {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .avatar-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: var(--text-muted);
    }
    
    .avatar-upload-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .profile-avatar:hover .avatar-upload-overlay {
        opacity: 1;
    }
    
    .avatar-upload-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--accent-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--bg-primary);
        cursor: pointer;
        box-shadow: 0 0 10px var(--glow-primary);
    }
    
    .avatar-upload-input {
        display: none;
    }
    
    .avatar-status {
        position: absolute;
        bottom: 0;
        right: 0;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        text-transform: uppercase;
        font-weight: 500;
        font-family: 'Orbitron', sans-serif;
    }
    
    .avatar-status.free {
        background-color: rgba(160, 160, 208, 0.3);
        color: var(--free-color);
        border: 1px solid var(--free-color);
    }
    
    .avatar-status.premium {
        background-color: rgba(247, 187, 42, 0.3);
        color: var(--premium-color);
        border: 1px solid var(--premium-color);
    }
    
    .avatar-status.professional {
        background-color: rgba(15, 247, 239, 0.3);
        color: var(--professional-color);
        border: 1px solid var(--professional-color);
    }
    
    /* Profile User Info */
    .profile-user-info {
        flex: 1;
    }
    
    .profile-username {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: var(--text-primary);
        position: relative;
        display: inline-block;
    }
    
    .profile-username:after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(to right, var(--accent-primary), var(--accent-secondary));
        border-radius: 3px;
    }
    
    .profile-stats {
        display: flex;
        gap: 2rem;
    }
    
    .stat-item {
        display: flex;
        flex-direction: column;
    }
    
    .stat-value {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.5rem;
        color: var(--accent-primary);
    }
    
    .stat-label {
        color: var(--text-secondary);
        font-size: 0.9rem;
    }
    
    /* Profile Tabs */
    .profile-tabs {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin: 2rem 0;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 1rem;
    }
    
    .tab-btn {
        background: none;
        border: none;
        padding: 0.75rem 1.5rem;
        color: var(--text-secondary);
        font-family: 'Orbitron', sans-serif;
        font-size: 1rem;
        cursor: pointer;
        position: relative;
        transition: all 0.3s ease;
    }
    
    .tab-btn:after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 0;
        height: 3px;
        background-color: var(--accent-primary);
        transition: width 0.3s ease;
    }
    
    .tab-btn:hover {
        color: var(--text-primary);
    }
    
    .tab-btn.active {
        color: var(--accent-primary);
    }
    
    .tab-btn.active:after {
        width: 100%;
        box-shadow: 0 0 5px var(--glow-primary);
    }
    
    /* Profile Content */
    .profile-content {
        margin-bottom: 3rem;
    }
    
    .tab-content {
        display: none;
        animation: fadeIn 0.5s ease;
    }
    
    .tab-content.active {
        display: block;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    /* Form Sections */
    .form-section {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        color: var(--text-primary);
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    
    .social-media-links h3 {
        font-size: 1.2rem;
        margin-bottom: 1rem;
        color: var(--text-secondary);
    }
    
    .form-action {
        text-align: center;
        margin-top: 2rem;
    }
    
    /* Theme Selector */
    .theme-selector {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        justify-content: center;
    }
    
    .theme-option {
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        width: 200px;
    }
    
    .theme-option:hover {
        transform: translateY(-5px);
    }
    
    .theme-option.active {
        transform: translateY(-5px);
    }
    
    .theme-option.active:before {
        content: '\f00c';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        top: -10px;
        right: -10px;
        width: 30px;
        height: 30px;
        background-color: var(--accent-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--bg-primary);
        z-index: 10;
        box-shadow: 0 0 10px var(--glow-primary);
    }
    
    .theme-preview {
        width: 100%;
        height: 150px;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 1rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }
    
    .preview-header {
        height: 20%;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .preview-content {
        height: 80%;
        display: flex;
    }
    
    .preview-sidebar {
        width: 30%;
        height: 100%;
        border-right: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .preview-main {
        width: 70%;
        height: 100%;
    }
    
    .dark-theme {
        background-color: #0a0a12;
    }
    
    .dark-theme .preview-header {
        background-color: #12121f;
    }
    
    .dark-theme .preview-sidebar {
        background-color: #1a1a2e;
    }
    
    .light-theme {
        background-color: #f0f0f5;
    }
    
    .light-theme .preview-header {
        background-color: #ffffff;
    }
    
    .light-theme .preview-sidebar {
        background-color: #e0e0e8;
    }
    
    .neon-theme {
        background-color: #0a0a12;
        position: relative;
    }
    
    .neon-theme:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: 
            radial-gradient(circle at 15% 50%, rgba(255, 65, 199, 0.3) 0%, transparent 40%),
            radial-gradient(circle at 85% 30%, rgba(15, 247, 239, 0.3) 0%, transparent 40%);
        z-index: 0;
    }
    
    .neon-theme .preview-header {
        background-color: rgba(18, 18, 31, 0.9);
        position: relative;
        z-index: 1;
    }
    
    .neon-theme .preview-sidebar {
        background-color: rgba(26, 26, 46, 0.8);
        position: relative;
        z-index: 1;
    }
    
    .neon-theme .preview-main {
        position: relative;
        z-index: 1;
    }
    
    .theme-label {
        text-align: center;
    }
    
    .theme-label input[type="radio"] {
        display: none;
    }
    
    .theme-label label {
        font-family: 'Orbitron', sans-serif;
        font-size: 0.9rem;
        cursor: pointer;
    }
    
    /* Color Scheme Selector */
    .color-scheme-selector {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        justify-content: center;
    }
    
    .color-option {
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        width: 150px;
    }
    
    .color-option:hover {
        transform: translateY(-5px);
    }
    
    .color-option.active {
        transform: translateY(-5px);
    }
    
    .color-option.active:before {
        content: '\f00c';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        top: -10px;
        right: -10px;
        width: 25px;
        height: 25px;
        background-color: var(--accent-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--bg-primary);
        z-index: 10;
        box-shadow: 0 0 10px var(--glow-primary);
        font-size: 0.8rem;
    }
    
    .color-preview {
        width: 100%;
        height: 60px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 1rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }
    
    .color-dot {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }
    
    .default-color {
        background-color: #0a0a12;
    }
    
    .default-color .primary {
        background-color: #0ff7ef;
    }
    
    .default-color .secondary {
        background-color: #f72a8a;
    }
    
    .purple-color {
        background-color: #0a0a12;
    }
    
    .purple-color .primary {
        background-color: #9b4dff;
    }
    
    .purple-color .secondary {
        background-color: #ff4df2;
    }
    
    .ocean-color {
        background-color: #0a0a12;
    }
    
    .ocean-color .primary {
        background-color: #00a2ff;
    }
    
    .ocean-color .secondary {
        background-color: #00ffb3;
    }
    
    .sunset-color {
        background-color: #0a0a12;
    }
    
    .sunset-color .primary {
        background-color: #ff7b00;
    }
    
    .sunset-color .secondary {
        background-color: #ff00aa;
    }
    
    .color-label {
        text-align: center;
    }
    
    .color-label input[type="radio"] {
        display: none;
    }
    
    .color-label label {
        font-family: 'Orbitron', sans-serif;
        font-size: 0.9rem;
        cursor: pointer;
    }
    
    /* Password Strength Meter */
    .password-strength-meter {
        margin-top: 2rem;
        padding: 1.5rem;
        background-color: rgba(255, 255, 255, 0.05);
        border-radius: 8px;
    }
    
    .password-strength-label {
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    
    .password-strength-bar {
        height: 8px;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 1rem;
    }
    
    .strength-bar {
        height: 100%;
        width: 0;
        border-radius: 4px;
        transition: width 0.3s ease, background-color 0.3s ease;
    }
    
    .password-requirements ul {
        list-style: none;
        padding: 0;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 0.5rem;
    }
    
    .password-requirements li {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-muted);
        font-size: 0.9rem;
    }
    
    .password-requirements li i {
        font-size: 0.6rem;
    }
    
    .password-requirements li.valid {
        color: var(--accent-primary);
    }
    
    .password-requirements li.valid i {
        color: var(--accent-primary);
    }
    
    /* Activity Charts */
    .activity-charts {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }
    
    .activity-chart {
        background-color: rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        padding: 1.5rem;
    }
    
    .activity-chart h3 {
        font-size: 1.2rem;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    
    .chart-container {
        width: 100%;
        height: 250px;
    }
    
    /* Activity List */
    .activity-list {
        margin-top: 1.5rem;
    }
    
    .activity-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-radius: 8px;
        background-color: rgba(255, 255, 255, 0.05);
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    
    .activity-item:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateX(5px);
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--bg-tertiary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent-primary);
    }
    
    .activity-content {
        flex: 1;
    }
    
    .activity-title {
        font-weight: 500;
        margin-bottom: 0.25rem;
    }
    
    .activity-time {
        color: var(--text-muted);
        font-size: 0.875rem;
    }
    
    /* Responsive Styles */
    @media (max-width: 768px) {
        .profile-header-content {
            flex-direction: column;
            text-align: center;
        }
        
        .profile-stats {
            justify-content: center;
        }
        
        .profile-tabs {
            flex-wrap: wrap;
        }
        
        .activity-charts {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Profile Page JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab navigation
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const tabId = this.dataset.tab;
                
                // Remove active class from all buttons and contents
                tabBtns.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));
                
                // Add active class to clicked button and corresponding content
                this.classList.add('active');
                document.getElementById(tabId).classList.add('active');
            });
        });
        
        // Password toggles
        document.querySelectorAll('.password-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input[type="password"], input[type="text"]');
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
        
        // Theme and color scheme selectors
        document.querySelectorAll('.theme-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.theme-option').forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                
                const radioInput = this.querySelector('input[type="radio"]');
                radioInput.checked = true;
            });
        });
        
        document.querySelectorAll('.color-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.color-option').forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                
                const radioInput = this.querySelector('input[type="radio"]');
                radioInput.checked = true;
            });
        });
        
        // Password strength checker
        const passwordInput = document.getElementById('new_password');
        const strengthBar = document.getElementById('strengthBar');
        const strengthValue = document.getElementById('strengthValue');
        const reqLength = document.getElementById('req-length');
        const reqUppercase = document.getElementById('req-uppercase');
        const reqLowercase = document.getElementById('req-lowercase');
        const reqNumber = document.getElementById('req-number');
        const reqSpecial = document.getElementById('req-special');
        
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                let color = '';
                
                // Check requirements
                const hasLength = password.length >= 8;
                const hasUppercase = /[A-Z]/.test(password);
                const hasLowercase = /[a-z]/.test(password);
                const hasNumber = /[0-9]/.test(password);
                const hasSpecial = /[^A-Za-z0-9]/.test(password);
                
                // Update requirement indicators
                toggleRequirement(reqLength, hasLength);
                toggleRequirement(reqUppercase, hasUppercase);
                toggleRequirement(reqLowercase, hasLowercase);
                toggleRequirement(reqNumber, hasNumber);
                toggleRequirement(reqSpecial, hasSpecial);
                
                // Calculate strength
                if (password.length > 0) {
                    strength += hasLength ? 20 : 0;
                    strength += hasUppercase ? 20 : 0;
                    strength += hasLowercase ? 20 : 0;
                    strength += hasNumber ? 20 : 0;
                    strength += hasSpecial ? 20 : 0;
                    
                    if (strength < 40) {
                        strengthValue.textContent = 'Weak';
                        color = '#f72a8a';
                    } else if (strength < 70) {
                        strengthValue.textContent = 'Medium';
                        color = '#f7bb2a';
                    } else {
                        strengthValue.textContent = 'Strong';
                        color = '#0ff7ef';
                    }
                } else {
                    strengthValue.textContent = 'Not Set';
                    color = '';
                }
                
                // Update strength bar
                strengthBar.style.width = strength + '%';
                strengthBar.style.backgroundColor = color;
            });
        }
        
        function toggleRequirement(element, isValid) {
            if (isValid) {
                element.classList.add('valid');
                element.querySelector('i').classList.remove('fa-circle');
                element.querySelector('i').classList.add('fa-check-circle');
            } else {
                element.classList.remove('valid');
                element.querySelector('i').classList.remove('fa-check-circle');
                element.querySelector('i').classList.add('fa-circle');
            }
        }
        
        // Avatar upload preview
        const avatarUpload = document.getElementById('avatarUpload');
        const avatarPreview = document.getElementById('avatarPreview');
        
        if (avatarUpload && avatarPreview) {
            avatarUpload.addEventListener('change', function() {
                const file = this.files[0];
                
                if (file) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        avatarPreview.innerHTML = `<img src="${e.target.result}" alt="Profile Avatar">`;
                    }
                    
                    reader.readAsDataURL(file);
                }
            });
        }
        
        // Activity charts initialization (if Chart.js is loaded)
        if (typeof Chart !== 'undefined') {
            // Generation history chart
            const generationCtx = document.getElementById('generationChart');
            if (generationCtx) {
                new Chart(generationCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'Tracks Generated',
                            data: [5, 12, 18, 10, 15, 25],
                            borderColor: '#0ff7ef',
                            backgroundColor: 'rgba(15, 247, 239, 0.1)',
                            tension: 0.3,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.05)'
                                },
                                ticks: {
                                    color: 'rgba(224, 224, 255, 0.7)'
                                }
                            },
                            x: {
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.05)'
                                },
                                ticks: {
                                    color: 'rgba(224, 224, 255, 0.7)'
                                }
                            }
                        }
                    }
                });
            }
            
            // Genre distribution chart
            const genreCtx = document.getElementById('genreChart');
            if (genreCtx) {
                new Chart(genreCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Electronic', 'Lo-fi', 'Hip Hop', 'Ambient', 'Cinematic'],
                        datasets: [{
                            data: [30, 20, 15, 25, 10],
                            backgroundColor: [
                                '#0ff7ef',
                                '#f72a8a',
                                '#f7bb2a',
                                '#9b4dff',
                                '#00ffb3'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    color: 'rgba(224, 224, 255, 0.7)'
                                }
                            }
                        }
                    }
                });
            }
        } else {
            // Fallback for charts if Chart.js is not loaded
            const chartContainers = document.querySelectorAll('.chart-container');
            chartContainers.forEach(container => {
                container.innerHTML = '<div class="chart-fallback">Charts library not available</div>';
            });
        }
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>