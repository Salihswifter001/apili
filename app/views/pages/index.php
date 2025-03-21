<?php require APPROOT . '/views/inc/header.php'; ?>

<section class="hero">
    <div class="hero-content">
        <h1 class="glitch-text" data-text="<?php echo __('create_music_with_ai'); ?>"><?php echo __('create_music_with_ai'); ?></h1>
        <p><?php echo __('create_new_music', 'Generate professional-quality music with artificial intelligence. Choose genres, customize parameters, and bring your musical ideas to life.'); ?></p>
        <div class="hero-actions">
            <a href="<?php echo URL_ROOT; ?>/users/register" class="btn btn-primary btn-lg"><?php echo __('pricing_get_started_btn', 'Get Started'); ?> <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
    <div class="hero-image">
            <div class="hero-graphic">
                <div class="waveform-animation"></div>
                <div class="music-notes-animation"></div>
            </div>
            <div class="hero-glow"></div>
        </div>
</section>

<section class="features">
    <h2 class="section-title"><?php echo __('about_join_us_title', 'Unlock Musical Creativity'); ?></h2>
    <div class="features-grid">
        <?php foreach($data['features'] as $feature): ?>
        <div class="feature-card">
            <div class="feature-icon">
                <i class="neon-icon fas fa-<?php echo $feature['icon']; ?>"></i>
            </div>
            <h3><?php echo __('feature_' . strtolower(str_replace(' ', '_', $feature['title'])), $feature['title']); ?></h3>
            <p><?php echo __('feature_' . strtolower(str_replace(' ', '_', $feature['title'])) . '_desc', $feature['description']); ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="how-it-works">
    <h2 class="section-title"><?php echo __('about_technology_title', 'How It Works'); ?></h2>
    <div class="steps">
        <div class="step">
            <div class="step-number">1</div>
            <div class="step-content">
                <h3><?php echo __('describe_your_music', 'Describe Your Vision'); ?></h3>
                <p><?php echo __('demo_prompt_help', 'Use natural language to describe the music you want to create. Specify mood, genre, instruments, and more.'); ?></p>
            </div>
        </div>
        <div class="step">
            <div class="step-number">2</div>
            <div class="step-content">
                <h3><?php echo __('customize_parameters', 'Customize Parameters'); ?></h3>
                <p><?php echo __('fine_tune', 'Fine-tune your creation with BPM, key, and duration settings to match your exact requirements.'); ?></p>
            </div>
        </div>
        <div class="step">
            <div class="step-number">3</div>
            <div class="step-content">
                <h3><?php echo __('generate_button', 'Generate & Refine'); ?></h3>
                <p><?php echo __('save_tracks', 'Our AI creates your track in seconds. Listen, save to your library, and share your creations.'); ?></p>
            </div>
        </div>
    </div>
</section>


<!-- Pricing section removed -->

<section class="cta">
    <div class="cta-content">
        <h2><?php echo __('generate_music', 'Start Creating Today'); ?></h2>
        <p><?php echo __('about_join_us_text', 'Join thousands of creators using Octaverum AI to bring their musical ideas to life.'); ?></p>
        <a href="<?php echo URL_ROOT; ?>/users/register" class="btn btn-glow btn-lg"><?php echo __('sign_up', 'Sign Up Now'); ?></a>
    </div>
    <div class="cta-decoration">
        <div class="glowing-sphere"></div>
    </div>
</section>

<?php require APPROOT . '/views/inc/footer.php'; ?>