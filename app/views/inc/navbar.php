<nav class="navbar">
    <div class="container">
        <div class="d-flex justify-content-between w-100 align-items-center">
            <div class="d-flex align-items-center">
                <a href="/" class="logo">Octaverum<span>AI</span></a>
            </div>
        </div>
        
        <div class="nav-toggle" id="menuToggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
        
        <div class="menu-backdrop" id="menuBackdrop"></div>
        
        <div class="nav-menu" id="mobileMenu">
            
            <?php if(isLoggedIn()) : ?>
                <!-- Logged in navigation -->
                <ul class="nav-items">
                    <li><a href="/music/dashboard" class="<?php echo isActive('music/dashboard') ? 'active' : ''; ?>"><?php echo __('dashboard', 'Dashboard'); ?></a></li>
                    <li><a href="/music/generate" class="<?php echo isActive('music/generate') ? 'active' : ''; ?>"><?php echo __('generate', 'Generate'); ?></a></li>
                    <li><a href="/music/library" class="<?php echo isActive('music/library') ? 'active' : ''; ?>"><?php echo __('library', 'Library'); ?></a></li>
                    <li><a href="/music/playlists" class="<?php echo isActive('music/playlists') ? 'active' : ''; ?>"><?php echo __('playlists', 'Playlists'); ?></a></li>
                    <?php if($_SESSION['user_data']->subscription_tier !== 'free') : ?>
                    <li><a href="/music/generateLyrics" class="<?php echo isActive('music/generateLyrics') ? 'active' : ''; ?>"><?php echo __('lyrics', 'Lyrics'); ?></a></li>
                    <?php endif; ?>
                    <li class="nav-lang-item">
                        <div class="lang-dropdown">
                            <button class="lang-toggle" id="navLangToggleUser">
                                <i class="fas fa-globe"></i> <?php echo strtoupper(getCurrentLanguage()); ?>
                            </button>
                            <div class="lang-dropdown-menu" id="navLangMenuUser">
                                <a class="<?php echo getCurrentLanguage() === 'en' ? 'active' : ''; ?>" href="/users/switchLanguage/en">English</a>
                                <a class="<?php echo getCurrentLanguage() === 'tr' ? 'active' : ''; ?>" href="/users/switchLanguage/tr">Türkçe</a>
                            </div>
                        </div>
                    </li>
                </ul>
                
                <div class="nav-user-menu">
                    <div class="user-dropdown">
                        <button class="user-dropdown-btn">
                            <?php echo htmlspecialchars($_SESSION['user_data']->username); ?>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="user-dropdown-content">
                            <a href="/users/profile">
                                <i class="fas fa-user"></i> <?php echo __('profile', 'Profile'); ?>
                            </a>
                            <a href="/users/subscription">
                                <i class="fas fa-crown"></i> <?php echo __('subscription', 'Subscription'); ?>
                                <span class="badge badge-<?php echo $_SESSION['user_data']->subscription_tier; ?>">
                                    <?php echo ucfirst($_SESSION['user_data']->subscription_tier); ?>
                                </span>
                            </a>
                            <a href="/music/settings">
                                <i class="fas fa-cog"></i> <?php echo __('settings', 'Settings'); ?>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="/users/logout">
                                <i class="fas fa-sign-out-alt"></i> <?php echo __('logout', 'Logout'); ?>
                            </a>
                        </div>
                    </div>
                    
                    <div class="generation-counter">
                        <div class="counter-icon"><i class="fas fa-music"></i></div>
                        <div class="counter-text">
                            <?php
                            $monthlyLimit = $_SESSION['user_data']->subscription_tier === 'professional' ? 'Unlimited' :
                                            ($_SESSION['user_data']->subscription_tier === 'premium' ? PREMIUM_GENERATION_LIMIT : FREE_GENERATION_LIMIT);
                            $currentCount = $_SESSION['user_data']->monthly_generations ?? 0;
                            $limitText = $monthlyLimit === 'Unlimited' ? '∞' : $monthlyLimit;
                            ?>
                            <span class="current-count"><?php echo $currentCount; ?></span>/<span class="limit-count"><?php echo $limitText; ?></span>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <!-- Not logged in navigation -->
                <ul class="nav-items">
                    <li><a href="/" class="<?php echo isActive('') ? 'active' : ''; ?>"><?php echo __('home', 'Home'); ?></a></li>
                    <li><a href="/pages/pricing" class="<?php echo isActive('pages/pricing') ? 'active' : ''; ?>"><?php echo __('pricing', 'Pricing'); ?></a></li>
                    <li><a href="/pages/about" class="<?php echo isActive('pages/about') ? 'active' : ''; ?>"><?php echo __('about', 'About'); ?></a></li>
                    <li><a href="/pages/faq" class="<?php echo isActive('pages/faq') ? 'active' : ''; ?>"><?php echo __('faq_title', 'FAQ'); ?></a></li>
                    <li><a href="/pages/contact" class="<?php echo isActive('pages/contact') ? 'active' : ''; ?>"><?php echo __('contact', 'Contact'); ?></a></li>
                    <li class="nav-lang-item">
                        <div class="lang-dropdown">
                            <button class="lang-toggle" id="navLangToggle">
                                <i class="fas fa-globe"></i> <?php echo strtoupper(getCurrentLanguage()); ?>
                            </button>
                            <div class="lang-dropdown-menu" id="navLangMenu">
                                <a class="<?php echo getCurrentLanguage() === 'en' ? 'active' : ''; ?>" href="/users/switchLanguage/en">English</a>
                                <a class="<?php echo getCurrentLanguage() === 'tr' ? 'active' : ''; ?>" href="/users/switchLanguage/tr">Türkçe</a>
                            </div>
                        </div>
                    </li>
                </ul>
                
                <div class="nav-auth">
                    <a href="/users/login" class="btn btn-outline"><?php echo __('login', 'Login'); ?></a>
                    <a href="/users/register" class="btn btn-primary"><?php echo __('sign_up', 'Sign Up'); ?></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>