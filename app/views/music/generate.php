<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <h1><?php echo __('generate_music_title'); ?></h1>
        <p><?php echo __('generate_music_desc'); ?></p>
    </div>
    
    <div class="generator-container">
        <form action="<?php echo URL_ROOT; ?>/music/generate" method="post" class="generator-form">
            <div class="form-group prompt-group">
                <label for="prompt"><?php echo __('describe_your_music'); ?> <span class="required">*</span></label>
                <textarea name="prompt" id="prompt" rows="4" placeholder="<?php echo __('prompt_placeholder'); ?>" class="<?php echo (!empty($data['errors']['prompt'])) ? 'is-invalid' : ''; ?>" required><?php echo $data['prompt']; ?></textarea>
                <span class="invalid-feedback"><?php echo $data['errors']['prompt'] ?? ''; ?></span>
                <div class="prompt-suggestions">
                    <div class="suggestions-header">
                        <span><?php echo __('prompt_suggestions'); ?></span>
                        <button type="button" class="btn-toggle"><i class="fas fa-chevron-down"></i></button>
                    </div>
                    <div class="suggestions-content">
                        <div class="suggestion-chips">
                            <span class="chip" data-prompt="An upbeat electronic track with catchy synth melodies and a dance rhythm">Electronic Dance</span>
                            <span class="chip" data-prompt="A cinematic orchestral piece with emotional strings and epic percussion">Cinematic Orchestra</span>
                            <span class="chip" data-prompt="A lo-fi hip hop beat with jazzy piano and relaxing drums">Lo-fi Hip Hop</span>
                            <span class="chip" data-prompt="An ambient soundscape with ethereal pads and atmospheric textures">Ambient Atmosphere</span>
                            <span class="chip" data-prompt="A futuristic cyberpunk track with glitchy beats and retro synth waves">Cyberpunk Synth</span>
                        </div>
                        <div class="suggestion-tips">
                            <h4><?php echo __('tips_for_prompts'); ?></h4>
                            <ul>
                                <li><?php echo __('tip_genre_mood'); ?></li>
                                <li><?php echo __('tip_tempo'); ?></li>
                                <li><?php echo __('tip_sections'); ?></li>
                                <li><?php echo __('tip_artists'); ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="generator-options">
                <div class="options-grid">
                    <div class="form-group">
                        <label for="title"><?php echo __('track_title'); ?></label>
                        <input type="text" name="title" id="title" placeholder="<?php echo __('title_placeholder'); ?>" value="<?php echo $data['title']; ?>">
                        <small class="form-text"><?php echo __('title_optional'); ?></small>
                    </div>
                    
                    <div class="form-group">
                        <label for="genre"><?php echo __('genre'); ?></label>
                        <select name="genre" id="genre">
                            <?php foreach($data['genres'] as $value => $label): ?>
                                <option value="<?php echo $value; ?>" <?php echo $data['genre'] === $value ? 'selected' : ''; ?>><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="bpm"><?php echo __('bpm'); ?></label>
                        <div class="range-input">
                            <input type="range" name="bpm" id="bpm" min="60" max="200" step="1" value="<?php echo $data['bpm']; ?>">
                            <span class="range-value" id="bpm-value"><?php echo $data['bpm']; ?></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="key"><?php echo __('key'); ?></label>
                        <select name="key" id="key">
                            <?php foreach($data['keys'] as $value => $label): ?>
                                <option value="<?php echo $value; ?>" <?php echo $data['key'] === $value ? 'selected' : ''; ?>><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="duration"><?php echo __('duration'); ?></label>
                        <select name="duration" id="duration">
                            <?php foreach($data['durations'] as $value => $label): ?>
                                <option value="<?php echo $value; ?>" <?php echo $data['duration'] === $value ? 'selected' : ''; ?>><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="generator-quality">
                    <h3><?php echo __('audio_quality'); ?></h3>
                    <?php
                    $tier = getUserTier();
                    $quality = $tier === 'professional' ? 'Studio' : ($tier === 'premium' ? 'High' : 'Standard');
                    $qualityClass = $tier === 'professional' ? 'professional' : ($tier === 'premium' ? 'premium' : 'free');
                    ?>
                    <div class="quality-indicator <?php echo $qualityClass; ?>">
                        <span class="quality-label"><?php echo $quality; ?> Quality</span>
                        <span class="tier-badge"><?php echo ucfirst($tier); ?></span>
                    </div>
                    <?php if($tier !== 'professional'): ?>
                    <a href="<?php echo URL_ROOT; ?>/users/subscription" class="upgrade-link"><?php echo __('upgrade_quality'); ?> <i class="fas fa-arrow-right"></i></a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="generation-info">
                <div class="monthly-usage">
                    <?php 
                    $currentCount = $_SESSION['user_data']->monthly_generations ?? 0;
                    $monthlyLimit = $tier === 'professional' ? 'Unlimited' : 
                                   ($tier === 'premium' ? PREMIUM_GENERATION_LIMIT : FREE_GENERATION_LIMIT);
                    $percentUsed = $monthlyLimit === 'Unlimited' ? 0 : ($currentCount / $monthlyLimit * 100);
                    ?>
                    <div class="usage-header">
                        <span><?php echo __('monthly_usage'); ?></span>
                        <span class="usage-count"><?php echo $currentCount; ?> / <?php echo $monthlyLimit === 'Unlimited' ? '∞' : $monthlyLimit; ?></span>
                    </div>
                    <?php if($monthlyLimit !== 'Unlimited'): ?>
                    <div class="usage-bar">
                        <div class="usage-progress" style="width: <?php echo $percentUsed; ?>%"></div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php if(!empty($data['errors']['api'])): ?>
                <div class="api-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span><?php echo $data['errors']['api']; ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="generator-submit">
                <button type="submit" class="btn btn-primary btn-lg with-icon">
                    <span><?php echo __('generate_button'); ?></span>
                    <i class="fas fa-music"></i>
                </button>
                <div class="generate-note">
                    <p><i class="fas fa-info-circle"></i> <?php echo __('generation_time'); ?></p>
                </div>
            </div>
        </form>
        
        <div class="generator-help">
            <div class="help-section">
                <h3><i class="fas fa-lightbulb"></i> <?php echo __('how_to_create'); ?></h3>
                <div class="help-content">
                    <p><?php echo __('prompt_detail_tip'); ?></p>
                    <ul>
                        <li><strong><?php echo __('genre_style'); ?>:</strong> <?php echo __('tip_genre_mood'); ?></li>
                        <li><strong><?php echo __('instruments'); ?>:</strong> <?php echo __('tip_sections'); ?></li>
                        <li><strong><?php echo __('mood_energy'); ?>:</strong> <?php echo __('tip_tempo'); ?></li>
                        <li><strong><?php echo __('structure'); ?>:</strong> <?php echo __('tip_sections'); ?></li>
                        <li><strong><?php echo __('influences'); ?>:</strong> <?php echo __('tip_artists'); ?></li>
                    </ul>
                    
                    <div class="example-prompt">
                        <h4><?php echo __('example_prompt'); ?></h4>
                        <p>"Create an uplifting electronic dance track with bright synth arpeggios and a catchy melody. The track should start with a soft intro, build tension in the middle, and have an energetic drop with punchy drums. Inspired by music from Daft Punk and Disclosure."</p>
                    </div>
                </div>
            </div>
            
            <?php if($tier === 'premium' || $tier === 'professional'): ?>
            <div class="help-section">
                <h3><i class="fas fa-crown"></i> <?php echo __('premium_features'); ?></h3>
                <div class="premium-features">
                    <div class="premium-feature">
                        <i class="fas fa-file-audio"></i>
                        <div>
                            <h4><?php echo __('high_quality_audio'); ?></h4>
                            <p><?php echo __('track_enhanced'); ?></p>
                        </div>
                    </div>
                    <div class="premium-feature">
                        <i class="fas fa-pen-fancy"></i>
                        <div>
                            <h4><?php echo __('lyrics_generation'); ?></h4>
                            <p><?php echo __('lyrics_dashboard'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Update BPM value display
document.getElementById('bpm').addEventListener('input', function() {
    document.getElementById('bpm-value').textContent = this.value;
});

// Prompt suggestion chips
document.querySelectorAll('.chip').forEach(chip => {
    chip.addEventListener('click', function() {
        document.getElementById('prompt').value = this.dataset.prompt;
    });
});

// Toggle prompt suggestions
document.querySelector('.btn-toggle').addEventListener('click', function() {
    const content = document.querySelector('.suggestions-content');
    const icon = this.querySelector('i');
    
    if (content.style.display === 'none' || !content.style.display) {
        content.style.display = 'block';
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    } else {
        content.style.display = 'none';
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    }
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>