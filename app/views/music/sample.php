<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-8 mx-auto text-center">
            <h1 class="display-4 mb-4"><?php echo __('sample_title'); ?></h1>
            <p class="lead"><?php echo __('sample_subtitle'); ?></p>
            <div class="mt-4">
                <a href="<?php echo URLROOT; ?>/users/register" class="btn btn-primary btn-lg mx-2"><?php echo __('sample_sign_up_btn'); ?></a>
                <a href="<?php echo URLROOT; ?>/music/demo" class="btn btn-outline-light btn-lg mx-2"><?php echo __('sample_try_demo_btn'); ?></a>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <?php foreach ($data['samples'] as $sample): ?>
            <div class="col-md-6 mb-4">
                <div class="card bg-dark border-0">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $sample['title']; ?></h4>
                        <p class="card-text text-muted">
                            <span class="badge bg-primary me-2"><?php echo $sample['genre']; ?></span>
                            <span><i class="far fa-clock me-1"></i> <?php echo $sample['duration']; ?></span>
                        </p>
                        
                        <div class="audio-player mt-3 mb-3">
                            <audio controls class="w-100" data-track-id="sample-<?php echo $sample['id']; ?>">
                                <source src="<?php echo $sample['audio_url']; ?>" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card bg-dark border-0">
                <div class="card-body text-center">
                    <h2 class="mb-4"><?php echo __('sample_create_your_own'); ?></h2>
                    <p class="mb-4"><?php echo __('sample_create_text'); ?></p>
                    <a href="<?php echo URLROOT; ?>/users/register" class="btn btn-primary btn-lg"><?php echo __('sample_get_started_btn'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>