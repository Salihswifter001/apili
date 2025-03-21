<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-8 mx-auto text-center">
            <h1 class="display-4 mb-4"><?php echo __('about_title'); ?></h1>
            <p class="lead"><?php echo __('about_subtitle'); ?></p>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-6">
            <h2><?php echo __('about_mission_title'); ?></h2>
            <p><?php echo __('about_mission_text'); ?></p>
            <p><?php echo __('about_vision_text'); ?></p>
        </div>
        <div class="col-md-6">
            <div class="rounded shadow-sm p-4 bg-dark">
                <h3 class="mb-3"><?php echo __('about_technology_title'); ?></h3>
                <p><?php echo __('about_technology_text'); ?></p>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> <?php echo __('about_tech_feat_1'); ?></li>
                    <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> <?php echo __('about_tech_feat_2'); ?></li>
                    <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> <?php echo __('about_tech_feat_3'); ?></li>
                    <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> <?php echo __('about_tech_feat_4'); ?></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto text-center">
            <h2 class="mb-4"><?php echo __('about_join_us_title'); ?></h2>
            <p class="mb-4"><?php echo __('about_join_us_text'); ?></p>
            <a href="<?php echo URL_ROOT; ?>/users/register" class="btn btn-primary btn-lg"><?php echo __('about_get_started_btn'); ?></a>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>