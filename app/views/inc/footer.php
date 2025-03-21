</div><!-- End of container -->

    <div id="main-content">
        <!-- Placeholder for main content (needed for mobile layout) -->
    </div>

    <!-- Bottom Navigation for Mobile (Android style) -->
    <div class="bottom-nav">
        <div class="bottom-nav-container">
            <?php if(isLoggedIn()) : ?>
                <!-- Logged in bottom navigation -->
                <a href="/music/dashboard" class="bottom-nav-item <?php echo isActive('music/dashboard') ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i>
                    <span class="bottom-nav-label"><?php echo __('home', 'Home'); ?></span>
                </a>
                <a href="/music/library" class="bottom-nav-item <?php echo isActive('music/library') ? 'active' : ''; ?>">
                    <i class="fas fa-music"></i>
                    <span class="bottom-nav-label"><?php echo __('library', 'Library'); ?></span>
                </a>
                <a href="/music/playlists" class="bottom-nav-item <?php echo isActive('music/playlists') ? 'active' : ''; ?>">
                    <i class="fas fa-list"></i>
                    <span class="bottom-nav-label"><?php echo __('playlists', 'Playlists'); ?></span>
                </a>
                <a href="/users/profile" class="bottom-nav-item <?php echo isActive('users/profile') ? 'active' : ''; ?>">
                    <i class="fas fa-user"></i>
                    <span class="bottom-nav-label"><?php echo __('profile', 'Profile'); ?></span>
                </a>
            <?php else : ?>
                <!-- Not logged in bottom navigation -->
                <a href="/" class="bottom-nav-item <?php echo isActive('') ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i>
                    <span class="bottom-nav-label"><?php echo __('home', 'Home'); ?></span>
                </a>
                <a href="/pages/pricing" class="bottom-nav-item <?php echo isActive('pages/pricing') ? 'active' : ''; ?>">
                    <i class="fas fa-tags"></i>
                    <span class="bottom-nav-label"><?php echo __('pricing', 'Pricing'); ?></span>
                </a>
                <a href="/music/demo" class="bottom-nav-item <?php echo isActive('music/demo') ? 'active' : ''; ?>">
                    <i class="fas fa-headphones"></i>
                    <span class="bottom-nav-label"><?php echo __('demo', 'Demo'); ?></span>
                </a>
                <a href="/users/login" class="bottom-nav-item <?php echo isActive('users/login') ? 'active' : ''; ?>">
                    <i class="fas fa-sign-in-alt"></i>
                    <span class="bottom-nav-label"><?php echo __('login', 'Login'); ?></span>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Floating Action Button (Android style) -->
    <?php if(isLoggedIn()) : ?>
    <a href="/music/generate" class="floating-action-btn" aria-label="<?php echo __('generate', 'Generate'); ?>">
        <i class="fas fa-plus"></i>
    </a>
    <?php endif; ?>

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <h3 class="logo">Octaverum<span>AI</span></h3>
                    <p><?php echo __('create_music_with_ai', 'Create music with artificial intelligence'); ?></p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="footer-links">
                    <h4>Explore</h4>
                    <ul>
                        <li><a href="<?php echo URL_ROOT; ?>/">Home</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/music/sample">Sample Tracks</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/music/demo">Try Demo</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/pages/about">About</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/pages/pricing">Pricing</a></li>
                    </ul>
                </div>
                
                <div class="footer-links">
                    <h4>Resources</h4>
                    <ul>
                        <li><a href="<?php echo URL_ROOT; ?>/pages/faq">FAQ</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/pages/tutorial">Tutorial</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/pages/blog">Blog</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/pages/contact">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-links">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="<?php echo URL_ROOT; ?>/pages/terms">Terms of Service</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/pages/privacy">Privacy Policy</a></li>
                        <li><a href="<?php echo URL_ROOT; ?>/pages/copyright">Copyright Info</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Octaverum AI. All rights reserved.</p>
                <p>v<?php echo APP_VERSION; ?></p>
            </div>
        </div>
    </footer>

    <!-- Global Music Player (only for logged-in users) -->
    <?php if(isLoggedIn()): ?>
    <div id="global-player" class="player-collapsed">
        <div class="player-handle">
            <div class="handle-icon"><i class="fas fa-chevron-up"></i></div>
        </div>
        <div class="player-container">
            <div class="player-track-info">
                <div class="player-album-art">
                    <img src="<?php echo URL_ROOT; ?>/public/img/default-album.png" alt="Album Art">
                </div>
                <div class="player-track-details">
                    <h4 class="track-title">Select a track</h4>
                    <p class="track-genre">-</p>
                </div>
            </div>
            
            <div class="player-controls">
                <button class="player-btn" id="player-prev"><i class="fas fa-step-backward"></i></button>
                <button class="player-btn play-btn" id="player-play"><i class="fas fa-play"></i></button>
                <button class="player-btn" id="player-next"><i class="fas fa-step-forward"></i></button>
            </div>
            
            <div class="player-progress">
                <span class="current-time">0:00</span>
                <div class="progress-bar">
                    <div class="progress-current"></div>
                </div>
                <span class="total-time">0:00</span>
            </div>
            
            <div class="player-volume">
                <i class="fas fa-volume-up"></i>
                <div class="volume-slider">
                    <div class="volume-current"></div>
                </div>
            </div>
            
            <div class="player-visualization-container">
                <canvas id="visualization"></canvas>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- JavaScript -->
    <script src="<?php echo URL_ROOT; ?>/public/js/main.js"></script>
    
    <?php if(isLoggedIn()): ?>
    <script src="<?php echo URL_ROOT; ?>/public/js/player.js"></script>
    <script src="<?php echo URL_ROOT; ?>/public/js/visualizer.js"></script>
    <?php endif; ?>
    
    <?php
    // Add page-specific scripts
    if(isset($data['scripts'])) {
        foreach($data['scripts'] as $script) {
            echo '<script src="' . URL_ROOT . '/public/js/' . $script . '"></script>';
        }
    }
    
    // Add additional scripts with full paths
    if(isset($data['additionalScripts']) && is_array($data['additionalScripts'])) {
        foreach($data['additionalScripts'] as $script) {
            echo '<script src="' . $script . '"></script>';
        }
    }
    ?>
    
    <!-- Language Dropdown Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all language toggle buttons and menus
            const toggleButtons = document.querySelectorAll('.lang-toggle');
            const dropdownMenus = document.querySelectorAll('.lang-dropdown-menu');
            
            // Add click event to each toggle button
            toggleButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    
                    // Get the associated menu ID from the button ID
                    const menuId = this.id.replace('Toggle', 'Menu');
                    const menu = document.getElementById(menuId);
                    
                    // Close all other menus first
                    dropdownMenus.forEach(dropdown => {
                        if (dropdown.id !== menuId) {
                            dropdown.classList.remove('show');
                        }
                    });
                    
                    // Toggle this menu
                    menu.classList.toggle('show');
                });
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                dropdownMenus.forEach(menu => {
                    menu.classList.remove('show');
                });
            });
            
            // Prevent closing when clicking inside the dropdown menu
            dropdownMenus.forEach(menu => {
                menu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
        });
    </script>
    
    <!-- Service Worker Registration (for PWA/Android-like behavior) -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/public/service-worker.js')
                    .then(reg => {
                        console.log('Service Worker registered successfully:', reg);
                    })
                    .catch(err => {
                        console.log('Service Worker registration failed:', err);
                    });
            });
        }
        
        // Page transition effects (Material Design-like)
        document.addEventListener('click', e => {
            const link = e.target.closest('a');
            
            // Only apply to internal links that don't open in new tabs
            if (link &&
                link.href.startsWith(window.location.origin) &&
                !link.hasAttribute('target') &&
                !link.hasAttribute('download') &&
                !e.ctrlKey && !e.metaKey) {
                
                e.preventDefault();
                
                // Create ripple effect from click position
                const ripple = document.createElement('div');
                ripple.style.position = 'fixed';
                ripple.style.top = '0';
                ripple.style.left = '0';
                ripple.style.right = '0';
                ripple.style.bottom = '0';
                ripple.style.backgroundColor = 'rgba(10, 10, 18, 0.3)';
                ripple.style.zIndex = '9999';
                ripple.style.opacity = '0';
                ripple.style.transition = 'opacity 0.3s ease';
                document.body.appendChild(ripple);
                
                // Fade in the ripple
                setTimeout(() => {
                    ripple.style.opacity = '1';
                    
                    // Navigate after animation
                    setTimeout(() => {
                        window.location.href = link.href;
                    }, 300);
                }, 10);
            }
        });
    </script>
</body>
</html>