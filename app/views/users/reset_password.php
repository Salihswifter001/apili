<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2><?php echo __('reset_password_title', 'Reset Your Password'); ?></h2>
            <p><?php echo __('reset_password_subtitle', 'Enter your new password below.'); ?></p>
        </div>
        
        <div class="auth-form">
            <form action="<?php echo URL_ROOT; ?>/users/resetPassword/<?php echo $data['token']; ?>" method="POST">
                <div class="form-group">
                    <label for="password"><?php echo __('new_password', 'New Password'); ?></label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="password" class="form-control <?php echo (!empty($data['errors']['password'])) ? 'is-invalid' : ''; ?>">
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <span class="invalid-feedback"><?php echo $data['errors']['password'] ?? ''; ?></span>
                    <small class="form-text"><?php echo __('password_requirements', 'Password must be at least 6 characters.'); ?></small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password"><?php echo __('confirm_password', 'Confirm Password'); ?></label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control <?php echo (!empty($data['errors']['confirm_password'])) ? 'is-invalid' : ''; ?>">
                    </div>
                    <span class="invalid-feedback"><?php echo $data['errors']['confirm_password'] ?? ''; ?></span>
                </div>
                
                <input type="hidden" name="token" value="<?php echo $data['token']; ?>">
                
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-neon-pink btn-block"><?php echo __('reset_password_btn', 'Reset Password'); ?></button>
                </div>
            </form>
        </div>
        
        <div class="auth-footer">
            <p><?php echo __('remembered_password', 'Remembered your password?'); ?> <a href="<?php echo URL_ROOT; ?>/users/login"><?php echo __('login', 'Login'); ?></a></p>
        </div>
    </div>
    
    <div class="auth-info">
        <div class="auth-info-content">
            <h2><?php echo __('reset_password_info_title', 'Create a Strong Password'); ?></h2>
            <p><?php echo __('reset_password_info_text', 'A strong password is crucial for keeping your Octaverum AI account secure.'); ?></p>
            
            <div class="info-features">
                <div class="info-feature">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <h3><?php echo __('strong_password_tips', 'Password Tips'); ?></h3>
                        <p><?php echo __('strong_password_tips_text', 'Use a mix of letters, numbers, and symbols. Make it at least 8 characters long.'); ?></p>
                    </div>
                </div>
                
                <div class="info-feature">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <h3><?php echo __('password_donts', 'What to Avoid'); ?></h3>
                        <p><?php echo __('password_donts_text', 'Don\'t use personal information like your name or birthday. Avoid common words and sequences.'); ?></p>
                    </div>
                </div>
                
                <div class="info-feature">
                    <i class="fas fa-user-lock"></i>
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
    // Toggle password visibility
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('togglePassword').addEventListener('click', function(e) {
            e.preventDefault();
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Add password toggle for confirmation password as well
        const confirmPasswordField = document.getElementById('confirm_password');
        if (confirmPasswordField) {
            const toggleButton = document.createElement('button');
            toggleButton.setAttribute('type', 'button');
            toggleButton.setAttribute('class', 'password-toggle');
            toggleButton.setAttribute('id', 'toggleConfirmPassword');
            toggleButton.innerHTML = '<i class="fas fa-eye"></i>';
            
            confirmPasswordField.parentNode.appendChild(toggleButton);
            
            document.getElementById('toggleConfirmPassword').addEventListener('click', function(e) {
                e.preventDefault();
                const confirmPasswordInput = document.getElementById('confirm_password');
                const icon = this.querySelector('i');
                
                if (confirmPasswordInput.type === 'password') {
                    confirmPasswordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    confirmPasswordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        }
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>