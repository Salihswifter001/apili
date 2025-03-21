/**
 * Octaverum AI - Profile Page JavaScript
 * Handles interactivity for the enhanced profile page
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize profile page functionality
    initProfilePage();
    
    /**
     * Initialize all profile page functionality
     */
    function initProfilePage() {
        initTabNavigation();
        initAvatarUpload();
        initPasswordStrengthMeter();
        initThemeColorSelectors();
        initActivityCharts();
        initAnimations();
    }
    
    /**
     * Initialize tab navigation
     */
    function initTabNavigation() {
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const tabId = this.dataset.tab;
                
                // Show loading indicator
                const loadingIndicator = document.createElement('div');
                loadingIndicator.className = 'tab-loading-indicator';
                document.querySelector('.profile-tabs').appendChild(loadingIndicator);
                
                // Remove active class from all buttons and contents
                tabBtns.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => {
                    content.classList.remove('active');
                    content.classList.add('loading');
                });
                
                // Add active class to clicked button and corresponding content
                this.classList.add('active');
                
                // Simulate loading for animation effect
                setTimeout(() => {
                    document.getElementById(tabId).classList.add('active');
                    tabContents.forEach(content => content.classList.remove('loading'));
                    
                    // Remove loading indicator
                    if (loadingIndicator.parentNode) {
                        loadingIndicator.parentNode.removeChild(loadingIndicator);
                    }
                    
                    // Trigger charts redraw when activity tab is shown
                    if (tabId === 'activity') {
                        initActivityCharts();
                    }
                }, 300);
            });
        });
    }
    
    /**
     * Initialize avatar upload and preview functionality
     */
    function initAvatarUpload() {
        const avatarUpload = document.getElementById('avatarUpload');
        const avatarPreview = document.getElementById('avatarPreview');
        
        if (avatarUpload && avatarPreview) {
            // Add ripple effect to avatar
            const avatarContainer = document.querySelector('.profile-avatar');
            const ripple = document.createElement('div');
            ripple.classList.add('avatar-ripple');
            avatarContainer.appendChild(ripple);
            
            // Handle file upload
            avatarUpload.addEventListener('change', function() {
                const file = this.files[0];
                
                if (file) {
                    // Check if file is an image
                    if (!file.type.match('image.*')) {
                        showNotification('Please select an image file', 'error');
                        return;
                    }
                    
                    // Check file size (max 2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        showNotification('Image size should be less than 2MB', 'error');
                        return;
                    }
                    
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        // Create image to check dimensions
                        const img = new Image();
                        img.onload = function() {
                            // Create canvas to resize if needed
                            const canvas = document.createElement('canvas');
                            let width = img.width;
                            let height = img.height;
                            
                            // Maintain aspect ratio but ensure max dimensions
                            const maxDim = 300;
                            if (width > height && width > maxDim) {
                                height = height * (maxDim / width);
                                width = maxDim;
                            } else if (height > maxDim) {
                                width = width * (maxDim / height);
                                height = maxDim;
                            }
                            
                            canvas.width = width;
                            canvas.height = height;
                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(img, 0, 0, width, height);
                            
                            // Update avatar preview with resized image
                            const resizedImage = canvas.toDataURL('image/jpeg', 0.85);
                            avatarPreview.innerHTML = `<img src="${resizedImage}" alt="Profile Avatar">`;
                            
                            // Add hidden input with base64 image data for form submission
                            let avatarDataInput = document.getElementById('avatarData');
                            if (!avatarDataInput) {
                                avatarDataInput = document.createElement('input');
                                avatarDataInput.type = 'hidden';
                                avatarDataInput.name = 'avatar';
                                avatarDataInput.id = 'avatarData';
                                document.querySelector('.profile-form').appendChild(avatarDataInput);
                            }
                            avatarDataInput.value = resizedImage;
                            
                            // Show success notification
                            showNotification('Profile image updated', 'success');
                        };
                        img.src = e.target.result;
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
        }
    }
    
    /**
     * Initialize password strength meter
     */
    function initPasswordStrengthMeter() {
        const passwordInput = document.getElementById('new_password');
        const strengthBar = document.getElementById('strengthBar');
        const strengthValue = document.getElementById('strengthValue');
        
        if (passwordInput && strengthBar && strengthValue) {
            // Add requirement indicators
            const reqLength = document.getElementById('req-length');
            const reqUppercase = document.getElementById('req-uppercase');
            const reqLowercase = document.getElementById('req-lowercase');
            const reqNumber = document.getElementById('req-number');
            const reqSpecial = document.getElementById('req-special');
            
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
                
                // Update strength bar with animation
                strengthBar.style.width = '0%';
                setTimeout(() => {
                    strengthBar.style.width = strength + '%';
                    strengthBar.style.backgroundColor = color;
                }, 50);
            });
            
            // Check confirm password match
            const confirmPasswordInput = document.getElementById('confirm_new_password');
            if (confirmPasswordInput) {
                confirmPasswordInput.addEventListener('input', function() {
                    if (this.value && passwordInput.value) {
                        if (this.value === passwordInput.value) {
                            this.classList.remove('is-invalid');
                            this.classList.add('is-valid');
                        } else {
                            this.classList.remove('is-valid');
                            this.classList.add('is-invalid');
                        }
                    }
                });
            }
        }
    }
    
    /**
     * Toggle password requirement indicator
     */
    function toggleRequirement(element, isValid) {
        if (element) {
            if (isValid) {
                element.classList.add('valid');
                const icon = element.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-circle');
                    icon.classList.add('fa-check-circle');
                }
            } else {
                element.classList.remove('valid');
                const icon = element.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-check-circle');
                    icon.classList.add('fa-circle');
                }
            }
        }
    }
    
    /**
     * Initialize theme and color scheme selectors
     */
    function initThemeColorSelectors() {
        // Theme selector
        const themeOptions = document.querySelectorAll('.theme-option');
        
        themeOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Update selected state
                themeOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                
                // Update radio button
                const radioInput = this.querySelector('input[type="radio"]');
                if (radioInput) {
                    radioInput.checked = true;
                }
                
                // Preview theme change
                const theme = this.dataset.theme;
                document.body.setAttribute('data-theme', theme);
                
                // Apply special effects based on theme
                applyThemeEffects(theme);
            });
        });
        
        // Color scheme selector
        const colorOptions = document.querySelectorAll('.color-option');
        
        colorOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Update selected state
                colorOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                
                // Update radio button
                const radioInput = this.querySelector('input[type="radio"]');
                if (radioInput) {
                    radioInput.checked = true;
                }
                
                // Preview color scheme change
                const colorScheme = this.dataset.color;
                document.body.setAttribute('data-color', colorScheme);
                
                // Apply special effects based on color scheme
                applyColorEffects(colorScheme);
            });
        });
    }
    
    /**
     * Apply special visual effects based on selected theme
     */
    function applyThemeEffects(theme) {
        // Get elements to apply theme-specific effects
        const avatar = document.querySelector('.profile-avatar');
        const username = document.querySelector('.profile-username');
        const headerGlow = document.querySelector('.profile-header-glow');
        
        if (!headerGlow && avatar) {
            // Create header glow effect if it doesn't exist
            const glow = document.createElement('div');
            glow.classList.add('profile-header-glow');
            avatar.closest('.profile-header').appendChild(glow);
        }
        
        // Apply theme-specific animations
        switch(theme) {
            case 'dark':
                if (avatar) avatar.style.animation = 'glowing 3s infinite';
                if (username) username.style.textShadow = '0 0 10px var(--glow-primary)';
                break;
            case 'light':
                if (avatar) avatar.style.animation = 'none';
                if (username) username.style.textShadow = '0 0 5px rgba(0, 162, 255, 0.7)';
                break;
            case 'neon':
                if (avatar) avatar.style.animation = 'glowing 1.5s infinite';
                if (username) username.style.animation = 'neonPulse 1.5s infinite';
                break;
            default:
                if (avatar) avatar.style.animation = 'none';
                if (username) username.style.textShadow = 'none';
        }
    }
    
    /**
     * Apply special visual effects based on selected color scheme
     */
    function applyColorEffects(colorScheme) {
        // Get elements that should change based on color scheme
        const icons = document.querySelectorAll('.activity-icon i, .form-group i');
        
        // Apply color scheme to icons
        let primaryColor, secondaryColor;
        
        switch(colorScheme) {
            case 'default':
                primaryColor = '#0ff7ef';
                secondaryColor = '#f72a8a';
                break;
            case 'purple':
                primaryColor = '#9b4dff';
                secondaryColor = '#ff4df2';
                break;
            case 'ocean':
                primaryColor = '#00a2ff';
                secondaryColor = '#00ffb3';
                break;
            case 'sunset':
                primaryColor = '#ff7b00';
                secondaryColor = '#ff00aa';
                break;
            default:
                primaryColor = '#0ff7ef';
                secondaryColor = '#f72a8a';
        }
        
        // Update custom properties for instant visual feedback
        document.documentElement.style.setProperty('--accent-primary', primaryColor);
        document.documentElement.style.setProperty('--accent-secondary', secondaryColor);
        document.documentElement.style.setProperty('--glow-primary', `rgba(${hexToRgb(primaryColor)}, 0.5)`);
        document.documentElement.style.setProperty('--glow-secondary', `rgba(${hexToRgb(secondaryColor)}, 0.5)`);
    }
    
    /**
     * Convert hex color to RGB
     */
    function hexToRgb(hex) {
        // Remove # if present
        hex = hex.replace('#', '');
        
        // Parse hex to RGB
        const r = parseInt(hex.substring(0, 2), 16);
        const g = parseInt(hex.substring(2, 4), 16);
        const b = parseInt(hex.substring(4, 6), 16);
        
        return `${r}, ${g}, ${b}`;
    }
    
    /**
     * Initialize activity charts
     */
    function initActivityCharts() {
        // Check if Chart.js is available
        if (typeof Chart !== 'undefined') {
            // Generation history chart
            const generationCtx = document.getElementById('generationChart');
            if (generationCtx) {
                // Destroy existing chart if any
                const existingChart = Chart.getChart(generationCtx);
                if (existingChart) {
                    existingChart.destroy();
                }
                
                // Create new chart with cyberpunk styling
                new Chart(generationCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'Tracks Generated',
                            data: [5, 12, 18, 10, 15, 25],
                            borderColor: getComputedStyle(document.documentElement).getPropertyValue('--accent-primary').trim(),
                            backgroundColor: `rgba(${hexToRgb(getComputedStyle(document.documentElement).getPropertyValue('--accent-primary').trim())}, 0.1)`,
                            tension: 0.3,
                            fill: true,
                            borderWidth: 2,
                            pointBackgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--accent-primary').trim(),
                            pointBorderColor: getComputedStyle(document.documentElement).getPropertyValue('--bg-primary').trim(),
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animations: {
                            tension: {
                                duration: 1000,
                                easing: 'linear'
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--card-bg').trim(),
                                titleColor: getComputedStyle(document.documentElement).getPropertyValue('--text-primary').trim(),
                                bodyColor: getComputedStyle(document.documentElement).getPropertyValue('--text-secondary').trim(),
                                borderColor: getComputedStyle(document.documentElement).getPropertyValue('--border-color').trim(),
                                borderWidth: 1,
                                padding: 10,
                                displayColors: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: `rgba(${hexToRgb(getComputedStyle(document.documentElement).getPropertyValue('--border-color').trim())}, 0.2)`
                                },
                                ticks: {
                                    color: getComputedStyle(document.documentElement).getPropertyValue('--text-secondary').trim(),
                                    font: {
                                        family: 'Orbitron, sans-serif'
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    color: `rgba(${hexToRgb(getComputedStyle(document.documentElement).getPropertyValue('--border-color').trim())}, 0.2)`
                                },
                                ticks: {
                                    color: getComputedStyle(document.documentElement).getPropertyValue('--text-secondary').trim(),
                                    font: {
                                        family: 'Orbitron, sans-serif'
                                    }
                                }
                            }
                        }
                    }
                });
            }
            
            // Genre distribution chart
            const genreCtx = document.getElementById('genreChart');
            if (genreCtx) {
                // Destroy existing chart if any
                const existingChart = Chart.getChart(genreCtx);
                if (existingChart) {
                    existingChart.destroy();
                }
                
                // Get theme colors for chart
                const accent1 = getComputedStyle(document.documentElement).getPropertyValue('--accent-primary').trim();
                const accent2 = getComputedStyle(document.documentElement).getPropertyValue('--accent-secondary').trim();
                const accent3 = getComputedStyle(document.documentElement).getPropertyValue('--accent-tertiary').trim();
                
                // Create new chart with cyberpunk styling
                new Chart(genreCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Electronic', 'Lo-fi', 'Hip Hop', 'Ambient', 'Cinematic'],
                        datasets: [{
                            data: [30, 20, 15, 25, 10],
                            backgroundColor: [
                                accent1,
                                accent2,
                                accent3,
                                `rgba(${hexToRgb(accent1)}, 0.6)`,
                                `rgba(${hexToRgb(accent2)}, 0.6)`
                            ],
                            borderWidth: 0,
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    color: getComputedStyle(document.documentElement).getPropertyValue('--text-secondary').trim(),
                                    font: {
                                        family: 'Roboto, sans-serif'
                                    },
                                    padding: 15,
                                    boxWidth: 15,
                                    boxHeight: 15
                                }
                            },
                            tooltip: {
                                backgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--card-bg').trim(),
                                titleColor: getComputedStyle(document.documentElement).getPropertyValue('--text-primary').trim(),
                                bodyColor: getComputedStyle(document.documentElement).getPropertyValue('--text-secondary').trim(),
                                borderColor: getComputedStyle(document.documentElement).getPropertyValue('--border-color').trim(),
                                borderWidth: 1,
                                padding: 10,
                                displayColors: true
                            }
                        },
                        animation: {
                            animateRotate: true,
                            animateScale: true
                        }
                    }
                });
            }
        } else {
            // Show fallback for charts if Chart.js is not loaded
            const chartContainers = document.querySelectorAll('.chart-container');
            chartContainers.forEach(container => {
                if (!container.querySelector('.chart-fallback')) {
                    container.innerHTML = '<div class="chart-fallback">Charts library not available</div>';
                }
            });
        }
    }
    
    /**
     * Initialize additional animations for interactive elements
     */
    function initAnimations() {
        // Add field glow effect to form inputs
        const formFields = document.querySelectorAll('.form-group input, .form-group textarea, .form-group select');
        
        formFields.forEach(field => {
            const fieldWrapper = document.createElement('div');
            fieldWrapper.className = 'form-field-effect';
            
            // Create glow element
            const fieldGlow = document.createElement('div');
            fieldGlow.className = 'field-glow';
            
            // Replace field with wrapped version
            field.parentNode.insertBefore(fieldWrapper, field);
            fieldWrapper.appendChild(field);
            fieldWrapper.appendChild(fieldGlow);
        });
        
        // Add highlight effect to stats
        const statValues = document.querySelectorAll('.stat-value');
        
        statValues.forEach(value => {
            const highlightEl = document.createElement('div');
            highlightEl.className = 'stat-highlight';
            
            // Move content to highlight element
            highlightEl.innerHTML = value.innerHTML;
            value.innerHTML = '';
            value.appendChild(highlightEl);
        });
        
        // Add header glow effect
        const profileHeader = document.querySelector('.profile-header');
        if (profileHeader && !profileHeader.querySelector('.profile-header-glow')) {
            const headerGlow = document.createElement('div');
            headerGlow.className = 'profile-header-glow';
            profileHeader.appendChild(headerGlow);
        }
    }
    
    /**
     * Show notification message
     */
    function showNotification(message, type = 'success') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'profile-notification ' + type;
        notification.innerHTML = `<div class="notification-content">${message}</div><button class="notification-close"><i class="fas fa-times"></i></button>`;
        
        // Add to document
        document.body.appendChild(notification);
        
        // Add animation
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
        
        // Set auto-hide timer
        const hideTimer = setTimeout(() => {
            hideNotification(notification);
        }, 5000);
        
        // Add close button functionality
        const closeBtn = notification.querySelector('.notification-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                clearTimeout(hideTimer);
                hideNotification(notification);
            });
        }
    }
    
    /**
     * Hide notification element
     */
    function hideNotification(notification) {
        notification.classList.remove('show');
        
        // Remove after animation completes
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }
});

// Add CSS for notifications
(function addNotificationStyles() {
    const style = document.createElement('style');
    style.textContent = `
        .profile-notification {
            position: fixed;
            top: calc(var(--header-height) + 20px);
            right: 20px;
            background-color: var(--bg-tertiary);
            border-left: 4px solid var(--accent-primary);
            border-radius: 4px;
            padding: 12px 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-width: 280px;
            max-width: 400px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            transform: translateX(120%);
            opacity: 0;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }
        
        .profile-notification.show {
            transform: translateX(0);
            opacity: 1;
        }
        
        .profile-notification.success {
            border-left-color: var(--accent-primary);
        }
        
        .profile-notification.error {
            border-left-color: var(--danger);
        }
        
        .profile-notification.warning {
            border-left-color: var(--warning);
        }
        
        .notification-content {
            flex: 1;
            padding-right: 10px;
        }
        
        .notification-close {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .notification-close:hover {
            color: var(--text-primary);
        }
    `;
    document.head.appendChild(style);
})();