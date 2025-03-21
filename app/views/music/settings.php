<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="content-container">
    <div class="section-header">
        <h1><?php echo __('settings_title', 'Settings'); ?></h1>
        <p class="section-description"><?php echo __('customize_experience', 'Customize your Octaverum AI experience.'); ?></p>
    </div>

    <form action="<?php echo URL_ROOT; ?>/music/settings" method="POST" class="settings-form">
        <!-- Appearance Settings -->
        <div class="settings-section">
            <h2><?php echo __('appearance', 'Appearance'); ?></h2>
            
            <div class="form-group">
                <label for="theme"><?php echo __('theme', 'Theme'); ?></label>
                <select name="theme" id="theme" class="form-control">
                    <?php foreach($data['themes'] as $value => $label): ?>
                        <option value="<?php echo $value; ?>" <?php echo $data['user']->theme === $value ? 'selected' : ''; ?>>
                            <?php echo __($value, $label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Player Settings -->
        <div class="settings-section">
            <h2><?php echo __('player_settings', 'Player Settings'); ?></h2>
            
            <div class="form-group">
                <label for="player_visualization"><?php echo __('visualization', 'Visualization'); ?></label>
                <select name="player_visualization" id="player_visualization" class="form-control">
                    <?php foreach($data['visualizations'] as $value => $label): ?>
                        <option value="<?php echo $value; ?>" <?php echo $data['user']->player_visualization === $value ? 'selected' : ''; ?>>
                            <?php echo __($value, $label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="auto_play" name="auto_play" 
                       <?php echo $data['user']->auto_play ? 'checked' : ''; ?>>
                <label class="form-check-label" for="auto_play">
                    <?php echo __('auto_play', 'Auto Play tracks when opening'); ?>
                </label>
            </div>
        </div>

        <!-- Language Settings -->
        <div class="settings-section">
            <h2><?php echo __('language_settings', 'Language Settings'); ?></h2>
            
            <div class="form-group">
                <label for="language"><?php echo __('language', 'Language'); ?></label>
                <select name="language" id="language" class="form-control">
                    <?php foreach($data['languages'] as $value => $label): ?>
                        <option value="<?php echo $value; ?>" <?php echo $data['user']->language === $value ? 'selected' : ''; ?>>
                            <?php echo $label; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="form-text text-muted"><?php echo __('language_help', 'Select your preferred interface language.'); ?></small>
            </div>
            
            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle me-2"></i>
                <?php echo __('language_note', 'Language changes will take effect immediately after saving.'); ?>
            </div>
        </div>

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary"><?php echo __('save_settings', 'Save Settings'); ?></button>
        </div>
    </form>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>