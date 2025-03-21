<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2><?php echo __('create_account', 'Create Account'); ?></h2>
            <p><?php echo __('join_octaverum', 'Join Octaverum AI and start creating music'); ?></p>
        </div>
        
        <form action="/users/register" method="post" class="auth-form">
            <div class="form-group">
                <label for="username"><?php echo __('username', 'Username'); ?> <span class="required">*</span></label>
                <div class="input-with-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" id="username" value="<?php echo $data['username']; ?>" class="<?php echo (!empty($data['errors']['username'])) ? 'is-invalid' : ''; ?>" required>
                </div>
                <span class="invalid-feedback"><?php echo $data['errors']['username'] ?? ''; ?></span>
            </div>
            
            <div class="form-group">
                <label for="email"><?php echo __('contact_email', 'Email'); ?> <span class="required">*</span></label>
                <div class="input-with-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" id="email" value="<?php echo $data['email']; ?>" class="<?php echo (!empty($data['errors']['email'])) ? 'is-invalid' : ''; ?>" required>
                </div>
                <span class="invalid-feedback"><?php echo $data['errors']['email'] ?? ''; ?></span>
            </div>
            
            <div class="form-group">
                <label for="password"><?php echo __('password', 'Password'); ?> <span class="required">*</span></label>
                <div class="input-with-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="password" class="<?php echo (!empty($data['errors']['password'])) ? 'is-invalid' : ''; ?>" required>
                    <button type="button" class="password-toggle"><i class="fas fa-eye"></i></button>
                </div>
                <span class="invalid-feedback"><?php echo $data['errors']['password'] ?? ''; ?></span>
                <small class="form-text">Must be at least 6 characters</small>
            </div>
            
            <div class="form-group">
                <label for="confirm_password"><?php echo __('password_confirm', 'Confirm Password'); ?> <span class="required">*</span></label>
                <div class="input-with-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="confirm_password" id="confirm_password" class="<?php echo (!empty($data['errors']['confirm_password'])) ? 'is-invalid' : ''; ?>" required>
                </div>
                <span class="invalid-feedback"><?php echo $data['errors']['confirm_password'] ?? ''; ?></span>
            </div>
            
            <div class="form-agreement">
                <div class="checkbox-wrapper">
                    <input type="checkbox" name="agreement" id="agreement" required>
                    <label for="agreement"><?php echo __('agreement_text', 'I agree to the'); ?> <a href="/pages/terms"><?php echo __('terms_title', 'Terms of Service'); ?></a> <?php echo __('and', 'and'); ?> <a href="/pages/privacy"><?php echo __('privacy_title', 'Privacy Policy'); ?></a></label>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block"><?php echo __('create_account', 'Create Account'); ?></button>
        </form>
        
        <div class="auth-footer">
            <p><?php echo __('already_have_account', 'Already have an account?'); ?> <a href="/users/login"><?php echo __('login', 'Log In'); ?></a></p>
        </div>
    </div>
    
    <div class="auth-info">
        <div class="auth-info-content">
            <h2><?php echo __('join_music_creation', 'Join the Future of Music Creation'); ?></h2>
            <div class="info-features">
                <div class="info-feature">
                    <i class="fas fa-magic"></i>
                    <div>
                        <h3><?php echo __('ai_powered_music', 'AI-Powered Music'); ?></h3>
                        <p><?php echo __('ai_music_desc', 'Generate unique tracks in seconds with our advanced AI technology'); ?></p>
                    </div>
                </div>
                <div class="info-feature">
                    <i class="fas fa-sliders-h"></i>
                    <div>
                        <h3><?php echo __('full_control', 'Full Control'); ?></h3>
                        <p><?php echo __('full_control_desc', 'Customize every aspect of your music with intuitive controls'); ?></p>
                    </div>
                </div>
                <div class="info-feature">
                    <i class="fas fa-headphones"></i>
                    <div>
                        <h3><?php echo __('high_quality_audio', 'High Quality Audio'); ?></h3>
                        <p><?php echo __('high_quality_desc', 'Create professional-sounding tracks ready to use in your projects'); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="testimonial">
                <p>"<?php echo __('testimonial_text', 'Octaverum AI completely transformed how I create music for my projects. It\'s fast, intuitive, and the results are amazing.'); ?>"</p>
                <div class="testimonial-author">
                    <img src="/public/img/testimonial-1.jpg" alt="Testimonial Author">
                    <div>
                        <strong>Alex Kim</strong>
                        <span>Game Developer</span>
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