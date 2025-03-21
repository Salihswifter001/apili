<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-8 mx-auto text-center">
            <h1 class="display-4 mb-4"><?php echo __('demo_result_title'); ?></h1>
            <p class="lead"><?php echo __('demo_result_subtitle'); ?></p>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            <div class="card bg-dark border-0">
                <div class="card-body p-4">
                    <h2 class="card-title mb-3"><?php echo $data['track']['title'] ?? __('demo_track_title'); ?></h2>
                    
                    <div class="mb-4">
                        <div class="audio-player">
                            <audio controls class="w-100" autoplay>
                                <source src="<?php echo $data['track']['audio_url'] ?? ''; ?>" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                        </div>
                        <?php if (empty($data['track']['audio_url'])): ?>
                            <div class="alert alert-warning mt-2">
                                <i class="fas fa-exclamation-triangle"></i>
                                <?php echo __('demo_audio_not_found', 'Audio preview not available. This is just a demo.'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong class="text-primary"><?php echo __('demo_prompt_label'); ?>:</strong>
                                <p><?php echo $data['track']['prompt'] ?? ''; ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong class="text-primary"><?php echo __('demo_genre_label'); ?>:</strong>
                                <p><?php echo ucfirst($data['track']['genre'] ?? 'Electronic'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-primary">
                        <strong><?php echo __('demo_result_note_title'); ?>:</strong> <?php echo __('demo_result_note'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            <div class="card bg-dark border-0">
                <div class="card-body p-4 text-center">
                    <h3 class="mb-3"><?php echo __('demo_liked_title'); ?></h3>
                    <p class="mb-4"><?php echo __('demo_liked_text'); ?></p>
                    
                    <div class="d-grid gap-2 d-md-block">
                        <a href="<?php echo URLROOT; ?>/users/register" class="btn btn-primary btn-lg me-md-2"><?php echo __('demo_sign_up_btn'); ?></a>
                        <a href="<?php echo URLROOT; ?>/music/demo" class="btn btn-outline-light btn-lg"><?php echo __('demo_try_again_btn'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8 mx-auto text-center">
            <h2 class="mb-4"><?php echo __('demo_features_title'); ?></h2>
            <div class="row mt-4">
                <div class="col-md-4 mb-4">
                    <div class="feature-box p-3">
                        <i class="fas fa-infinity fa-3x text-primary mb-3"></i>
                        <h4><?php echo __('demo_feature1_title'); ?></h4>
                        <p><?php echo __('demo_feature1_text'); ?></p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-box p-3">
                        <i class="fas fa-sliders-h fa-3x text-primary mb-3"></i>
                        <h4><?php echo __('demo_feature2_title'); ?></h4>
                        <p><?php echo __('demo_feature2_text'); ?></p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-box p-3">
                        <i class="fas fa-download fa-3x text-primary mb-3"></i>
                        <h4><?php echo __('demo_feature3_title'); ?></h4>
                        <p><?php echo __('demo_feature3_text'); ?></p>
                    </div>
                </div>
            </div>
            <a href="<?php echo URLROOT; ?>/pages/pricing" class="btn btn-primary btn-lg mt-3"><?php echo __('demo_view_plans_btn'); ?></a>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>