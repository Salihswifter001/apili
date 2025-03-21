<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2><?php echo __('login_title', 'Welcome Back'); ?></h2>
            <p><?php echo __('login_subtitle', 'Log in to your Octaverum AI account'); ?></p>
        </div>
        
        <form action="/users/login" method="post" class="auth-form">
            <div class="form-group">
                <label for="email">Email <span class="required">*</span></label>
                <div class="input-with-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" id="email" value="<?php echo $data['email']; ?>" class="<?php echo (!empty($data['errors']['email'])) ? 'is-invalid' : ''; ?>" required>
                </div>
                <span class="invalid-feedback"><?php echo $data['errors']['email'] ?? ''; ?></span>
            </div>
            
            <div class="form-group">
                <label for="password">Password <span class="required">*</span></label>
                <div class="input-with-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="password" class="<?php echo (!empty($data['errors']['password'])) ? 'is-invalid' : ''; ?>" required>
                    <button type="button" class="password-toggle"><i class="fas fa-eye"></i></button>
                </div>
                <span class="invalid-feedback"><?php echo $data['errors']['password'] ?? ''; ?></span>
            </div>
            
            <div class="form-options">
                <div class="checkbox-wrapper">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember me</label>
                </div>
                <a href="/users/forgotPassword" class="forgot-password text-neon-pink"><?php echo __('forgot_password', 'Forgot Password?'); ?></a>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Log In</button>
        </form>
        
        <div class="auth-footer">
            <p>Don't have an account? <a href="/users/register">Sign Up</a></p>
        </div>
    </div>
    
    <div class="auth-info">
        <div class="auth-info-content">
            <h2>Unleash Your Music Creativity</h2>
            <div class="info-features">
                <div class="info-feature">
                    <i class="fas fa-bolt"></i>
                    <div>
                        <h3>Quick Generation</h3>
                        <p>Create complete music tracks in seconds, not hours</p>
                    </div>
                </div>
                <div class="info-feature">
                    <i class="fas fa-layer-group"></i>
                    <div>
                        <h3>Advanced Library</h3>
                        <p>Organize your creations with playlists and favorites</p>
                    </div>
                </div>
                <div class="info-feature">
                    <i class="fas fa-download"></i>
                    <div>
                        <h3>Export Options</h3>
                        <p>Download your tracks in various formats for any project</p>
                    </div>
                </div>
            </div>
            
            <div class="login-samples">
                <h3>Recent Creations by Our Users</h3>
                <div class="sample-tracks">
                    <div class="mini-track">
                        <div class="mini-track-art">
                            <img src="/public/img/track-1.jpg" alt="Track">
                            <div class="play-icon"><i class="fas fa-play"></i></div>
                        </div>
                        <div class="mini-track-info">
                            <div class="mini-track-title">Neon Dreams</div>
                            <div class="mini-track-genre">Electronic</div>
                        </div>
                    </div>
                    <div class="mini-track">
                        <div class="mini-track-art">
                            <img src="/public/img/track-2.jpg" alt="Track">
                            <div class="play-icon"><i class="fas fa-play"></i></div>
                        </div>
                        <div class="mini-track-info">
                            <div class="mini-track-title">Urban Flow</div>
                            <div class="mini-track-genre">Hip Hop</div>
                        </div>
                    </div>
                    <div class="mini-track">
                        <div class="mini-track-art">
                            <img src="/public/img/track-3.jpg" alt="Track">
                            <div class="play-icon"><i class="fas fa-play"></i></div>
                        </div>
                        <div class="mini-track-info">
                            <div class="mini-track-title">Cyber Horizon</div>
                            <div class="mini-track-genre">Cinematic</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.password-toggle').forEach(button => {
        button.addEventListener('click', function() {
            // Get the input field that is a sibling to this button
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
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>