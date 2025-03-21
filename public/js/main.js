/**
 * Octaverum AI - Main JavaScript
 * Handles common functionality across the application
 */

document.addEventListener('DOMContentLoaded', function() {
    // Site constants and globals
    const URLROOT = document.querySelector('meta[name="urlroot"]')?.content || '';
    const ADD_TO_FAVORITES = document.querySelector('meta[name="add_to_favorites"]')?.content || 'Favorilere Ekle';
    const REMOVE_FROM_FAVORITES = document.querySelector('meta[name="remove_from_favorites"]')?.content || 'Favorilerden Çıkar';

    // Flash message auto-dismiss
    const flashMessage = document.getElementById('flash-message');
    if (flashMessage) {
        setTimeout(() => {
            flashMessage.remove();
        }, 5000);
    }

    // Welcome Modal
    const welcomeModal = document.getElementById('welcome-modal');
    if (welcomeModal) {
        const closeBtn = welcomeModal.querySelector('.close');
        closeBtn.addEventListener('click', () => {
            welcomeModal.style.display = 'none';
        });
        
        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target === welcomeModal) {
                welcomeModal.style.display = 'none';
            }
        });
    }

    // Mobile navigation drawer (Android-style)
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    const menuBackdrop = document.getElementById('menuBackdrop');
    
    if (menuToggle && mobileMenu && menuBackdrop) {
        // Toggle menu when hamburger icon is clicked
        menuToggle.addEventListener('click', () => {
            menuToggle.classList.toggle('active');
            mobileMenu.classList.toggle('active');
            menuBackdrop.classList.toggle('active');
            
            // Force repaint to ensure the menu is displayed correctly
            if (menuToggle.classList.contains('active')) {
                mobileMenu.style.display = 'flex';
                mobileMenu.style.visibility = 'visible';
                mobileMenu.style.opacity = '1';
                mobileMenu.style.pointerEvents = 'auto';
                mobileMenu.style.height = '100vh';
                mobileMenu.style.width = '100%';
                mobileMenu.style.position = 'fixed';
            } else {
                mobileMenu.style.pointerEvents = 'none';
            }
            
            // Prevent body scrolling when menu is open
            document.body.style.overflow = menuToggle.classList.contains('active') ? 'hidden' : '';
        });
        
        // Close menu when backdrop is clicked
        menuBackdrop.addEventListener('click', () => {
            menuToggle.classList.remove('active');
            mobileMenu.classList.remove('active');
            menuBackdrop.classList.remove('active');
            document.body.style.overflow = '';
        });
        
        // Swipe to close menu (for mobile touch devices)
        let startX, startY, startTime;
        mobileMenu.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
            startTime = Date.now();
        }, { passive: true });
        
        mobileMenu.addEventListener('touchmove', (e) => {
            if (!startY) return;
            
            const currentY = e.touches[0].clientY;
            const diff = currentY - startY;
            
            // If swiping up (to close menu)
            if (diff < -50) {
                e.preventDefault();
                mobileMenu.style.transform = `translateY(-${Math.abs(diff)}px)`;
            }
        });
        
        mobileMenu.addEventListener('touchend', (e) => {
            const currentY = e.changedTouches[0].clientY;
            const diff = currentY - startY;
            const time = Date.now() - startTime;
            
            // Close if swipe up is more than 100px or fast swipe
            if (diff < -100 || (diff < -30 && time < 300)) {
                menuToggle.classList.remove('active');
                mobileMenu.classList.remove('active');
                menuBackdrop.classList.remove('active');
                document.body.style.overflow = '';
            }
            
            // Reset menu position
            mobileMenu.style.transform = '';
            startX = null;
            startY = null;
        });
    }
    
    // Handle user dropdown in mobile view
    const userDropdownBtns = document.querySelectorAll('.user-dropdown-btn');
    if (window.innerWidth <= 768 && userDropdownBtns.length > 0) {
        userDropdownBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const content = this.nextElementSibling;
                content.classList.toggle('active');
            });
        });
    }
    
    // Add Material Design ripple effect to buttons (Android-style)
    const buttons = document.querySelectorAll('.btn, .bottom-nav-item');
    if (buttons.length > 0) {
        buttons.forEach(button => {
            button.addEventListener('touchstart', function(e) {
                const rect = this.getBoundingClientRect();
                const x = e.touches[0].clientX - rect.left;
                const y = e.touches[0].clientY - rect.top;
                
                const ripple = document.createElement('span');
                ripple.classList.add('ripple-effect');
                ripple.style.left = `${x}px`;
                ripple.style.top = `${y}px`;
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            }, { passive: true });
        });
    }

    // Global Player Controls (if exists)
    const globalPlayer = document.getElementById('global-player');
    if (globalPlayer) {
        const playerHandle = globalPlayer.querySelector('.player-handle');
        
        playerHandle.addEventListener('click', () => {
            globalPlayer.classList.toggle('player-collapsed');
            
            const handleIcon = playerHandle.querySelector('i');
            if (globalPlayer.classList.contains('player-collapsed')) {
                handleIcon.classList.remove('fa-chevron-down');
                handleIcon.classList.add('fa-chevron-up');
            } else {
                handleIcon.classList.remove('fa-chevron-up');
                handleIcon.classList.add('fa-chevron-down');
            }
        });
    }

    // Glitch text animation
    const glitchTexts = document.querySelectorAll('.glitch-text');
    if (glitchTexts.length > 0) {
        glitchTexts.forEach(text => {
            if (!text.getAttribute('data-text')) {
                text.setAttribute('data-text', text.textContent);
            }
        });
    }

    // Dropdown menu behavior
    const dropdowns = document.querySelectorAll('.user-dropdown');
    if (dropdowns.length > 0) {
        dropdowns.forEach(dropdown => {
            const dropdownBtn = dropdown.querySelector('.user-dropdown-btn');
            const dropdownContent = dropdown.querySelector('.user-dropdown-content');
            
            // Close dropdowns when clicking outside
            document.addEventListener('click', (e) => {
                if (!dropdown.contains(e.target)) {
                    dropdownContent.style.opacity = '0';
                    dropdownContent.style.visibility = 'hidden';
                    dropdownContent.style.transform = 'translateY(10px)';
                    
                    const icon = dropdownBtn.querySelector('i');
                    icon.style.transform = 'rotate(0deg)';
                }
            });
            
            // Toggle dropdown on click (for mobile devices)
            dropdownBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                const isVisible = dropdownContent.style.visibility === 'visible';
                
                // Toggle dropdown visibility
                dropdownContent.style.opacity = isVisible ? '0' : '1';
                dropdownContent.style.visibility = isVisible ? 'hidden' : 'visible';
                dropdownContent.style.transform = isVisible ? 'translateY(10px)' : 'translateY(0)';
                
                // Rotate chevron icon
                const icon = dropdownBtn.querySelector('i');
                icon.style.transform = isVisible ? 'rotate(0deg)' : 'rotate(180deg)';
            });
        });
    }

    // AJAX form submissions for favoriting tracks
    const favoriteButtons = document.querySelectorAll('.btn-icon[href*="toggleFavorite"]');
    if (favoriteButtons.length > 0) {
        favoriteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const url = this.getAttribute('href') || `${URLROOT}/music/toggleFavorite/${this.getAttribute('data-id')}`;
                const heartIcon = this.querySelector('.fa-heart');
                
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.isFavorite) {
                            this.classList.add('favorited');
                            if (heartIcon) {
                                heartIcon.classList.add('text-danger');
                                this.setAttribute('title', REMOVE_FROM_FAVORITES || 'Favorilerden Çıkar');
                                if (this.querySelector('span:not(.me-2):not(.neon-particles)')) {
                                    this.querySelector('span:not(.me-2):not(.neon-particles)').textContent = REMOVE_FROM_FAVORITES || 'Favorilerden Çıkar';
                                }
                            }
                        } else {
                            this.classList.remove('favorited');
                            if (heartIcon) {
                                heartIcon.classList.remove('text-danger');
                                this.setAttribute('title', ADD_TO_FAVORITES || 'Favorilere Ekle');
                                if (this.querySelector('span:not(.me-2):not(.neon-particles)')) {
                                    this.querySelector('span:not(.me-2):not(.neon-particles)').textContent = ADD_TO_FAVORITES || 'Favorilere Ekle';
                                }
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    }

    // Range input value display
    const rangeInputs = document.querySelectorAll('input[type="range"]');
    if (rangeInputs.length > 0) {
        rangeInputs.forEach(input => {
            const valueDisplay = input.nextElementSibling;
            if (valueDisplay && valueDisplay.classList.contains('range-value')) {
                input.addEventListener('input', () => {
                    valueDisplay.textContent = input.value;
                });
            }
        });
    }

    // Initialize password toggle functionality
    const passwordToggles = document.querySelectorAll('.password-toggle');
    if (passwordToggles.length > 0) {
        passwordToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
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
    }

    // Animated background effect
    createNeonParticles();
});

/**
 * Creates animated neon particles in the background
 * Only runs on pages with the .hero section for performance
 */
function createNeonParticles() {
    const heroSection = document.querySelector('.hero');
    if (!heroSection) return;
    
    const particlesContainer = document.createElement('div');
    particlesContainer.className = 'particles-container';
    particlesContainer.style.cssText = `
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 0;
        pointer-events: none;
    `;
    
    heroSection.appendChild(particlesContainer);
    
    // Create particles
    const particleCount = 15;
    const colors = [
        'rgba(15, 247, 239, 0.5)',  // Cyan
        'rgba(247, 42, 138, 0.3)',  // Pink
        'rgba(121, 49, 255, 0.4)'   // Purple
    ];
    
    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        
        // Random properties
        const size = Math.random() * 4 + 2;
        const color = colors[Math.floor(Math.random() * colors.length)];
        
        particle.style.cssText = `
            position: absolute;
            background: ${color};
            border-radius: 50%;
            width: ${size}px;
            height: ${size}px;
            filter: blur(${size}px);
            box-shadow: 0 0 ${size * 2}px ${color};
            top: ${Math.random() * 100}%;
            left: ${Math.random() * 100}%;
            transform: scale(0);
            animation: floatParticle ${Math.random() * 10 + 10}s linear infinite;
            animation-delay: ${Math.random() * 5}s;
        `;
        
        particlesContainer.appendChild(particle);
    }
    
    // Add keyframes for animation
    const styleSheet = document.createElement('style');
    styleSheet.innerHTML = `
        @keyframes floatParticle {
            0% {
                transform: translate(0, 0) scale(0);
                opacity: 0;
            }
            25% {
                transform: translate(${Math.random() * 100 - 50}px, ${Math.random() * 100 - 50}px) scale(1);
                opacity: 1;
            }
            75% {
                transform: translate(${Math.random() * 200 - 100}px, ${Math.random() * 200 - 100}px) scale(1);
                opacity: 1;
            }
            100% {
                transform: translate(${Math.random() * 300 - 150}px, ${Math.random() * 300 - 150}px) scale(0);
                opacity: 0;
            }
        }
    `;
    
    document.head.appendChild(styleSheet);
}