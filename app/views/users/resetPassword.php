<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2><?php echo __('reset_password_title', 'Reset Your Password'); ?></h2>
            <p><?php echo __('reset_password_subtitle', 'Enter your new password below.'); ?></p>
        </div>
        
        <?php flash('reset_password'); ?>
        
        <form action="<?php echo URL_ROOT; ?>/users/resetPassword/<?php echo $data['token']; ?>" method="post" class="auth-form">
            <div class="form-group">
                <label for="password"><?php echo __('new_password', 'New Password'); ?> <span class="required">*</span></label>
                <div class="input-with-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="password" class="<?php echo (!empty($data['errors']['password'])) ? 'is-invalid' : ''; ?>" required>
                    <button type="button" class="password-toggle"><i class="fas fa-eye"></i></button>
                </div>
                <span class="invalid-feedback"><?php echo $data['errors']['password'] ?? ''; ?></span>
                <small class="form-text"><?php echo __('password_requirements', 'Password must be at least 6 characters.'); ?></small>
            </div>
            
            <div class="form-group">
                <label for="confirm_password"><?php echo __('confirm_password', 'Confirm Password'); ?> <span class="required">*</span></label>
                <div class="input-with-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="confirm_password" id="confirm_password" class="<?php echo (!empty($data['errors']['confirm_password'])) ? 'is-invalid' : ''; ?>" required>
                </div>
                <span class="invalid-feedback"><?php echo $data['errors']['confirm_password'] ?? ''; ?></span>
            </div>
            
            <button type="submit" class="btn btn-neon-pink btn-block"><?php echo __('reset_password_btn', 'Reset Password'); ?></button>
        </form>
    </div>
    
    <div class="auth-info">
        <div class="auth-info-content">
            <h2><?php echo __('reset_password_info_title', 'Create a Strong Password'); ?></h2>
            <p><?php echo __('reset_password_info_text', 'A strong password is crucial to keeping your Octaverum AI account secure.'); ?></p>
            
            <div class="info-features">
                <div class="info-feature">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <h3><?php echo __('strong_password_tips', 'Password Tips'); ?></h3>
                        <p><?php echo __('strong_password_tips_text', 'Use a mix of letters, numbers, and symbols. Make it at least 8 characters long.'); ?></p>
                    </div>
                </div>
                <div class="info-feature">
                    <i class="fas fa-times-circle"></i>
                    <div>
                        <h3><?php echo __('password_donts', 'What to Avoid'); ?></h3>
                        <p><?php echo __('password_donts_text', 'Don\'t use personal information like your name or birthday. Avoid common words and sequences.'); ?></p>
                    </div>
                </div>
                <div class="info-feature">
                    <i class="fas fa-user-shield"></i>
                    <div>
                        <h3><?php echo __('account_security', 'Account Security'); ?></h3>
                        <p><?php echo __('account_security_text', 'Use a unique password for your Octaverum AI account. Never share your password with anyone.'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.password-toggle').forEach(button => {
    button.addEventListener('click', function() {
        const input = this.previousElementSibling;
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
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>