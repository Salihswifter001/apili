<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2><?php echo __('forgot_password_title', 'Forgot Password?'); ?></h2>
            <p><?php echo __('forgot_password_subtitle', 'Enter your email address and we\'ll send instructions to reset your password.'); ?></p>
        </div>
        
        <?php flash('forgot_password'); ?>
        
        <form action="<?php echo URL_ROOT; ?>/users/forgotPassword" method="post" class="auth-form">
            <div class="form-group">
                <label for="email"><?php echo __('email', 'Email Address'); ?> <span class="required">*</span></label>
                <div class="input-with-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" id="email" value="<?php echo $data['email'] ?? ''; ?>" class="<?php echo (!empty($data['errors']['email'])) ? 'is-invalid' : ''; ?>" required>
                </div>
                <span class="invalid-feedback"><?php echo $data['errors']['email'] ?? ''; ?></span>
            </div>
            
            <button type="submit" class="btn btn-neon-pink btn-block"><?php echo __('send_reset_link', 'Send Reset Link'); ?></button>
        </form>
        
        <div class="auth-footer">
            <p><?php echo __('remembered_password', 'Remembered your password?'); ?> <a href="<?php echo URL_ROOT; ?>/users/login"><?php echo __('login', 'Log In'); ?></a></p>
        </div>
    </div>
    
    <div class="auth-info">
        <div class="auth-info-content">
            <h2><?php echo __('password_reset_info_title', 'Password Recovery'); ?></h2>
            <p><?php echo __('password_reset_info_text', 'We\'ll help you regain access to your account quickly and securely.'); ?></p>
            
            <div class="info-features">
                <div class="info-feature">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <h3><?php echo __('secure_reset', 'Secure Reset Process'); ?></h3>
                        <p><?php echo __('secure_reset_text', 'Our password reset system uses secure tokens that expire after use for your protection.'); ?></p>
                    </div>
                </div>
                <div class="info-feature">
                    <i class="fas fa-envelope-open-text"></i>
                    <div>
                        <h3><?php echo __('email_instructions', 'Check Your Email'); ?></h3>
                        <p><?php echo __('email_instructions_text', 'After submitting your email, check your inbox for reset instructions. If you don\'t see it, check your spam folder.'); ?></p>
                    </div>
                </div>
                <div class="info-feature">
                    <i class="fas fa-key"></i>
                    <div>
                        <h3><?php echo __('password_tips', 'Password Tips'); ?></h3>
                        <p><?php echo __('password_tips_text', 'Create a strong, unique password that you don\'t use for other accounts for maximum security.'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>