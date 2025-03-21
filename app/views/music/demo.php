<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-8 mx-auto text-center">
            <h1 class="display-4 mb-4"><?php echo __('demo_title'); ?></h1>
            <p class="lead"><?php echo __('demo_subtitle'); ?></p>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-lg-7 mx-auto">
            <div class="card bg-dark border-0">
                <div class="card-body p-4">
                    <h2 class="card-title mb-4"><?php echo __('demo_form_title'); ?></h2>
                    
                    <?php if (!empty($data['errors'])): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($data['errors'] as $error): ?>
                                <div><?php echo $error; ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?php echo URLROOT; ?>/music/demo" method="POST">
                        <div class="mb-4">
                            <label for="prompt" class="form-label"><?php echo __('demo_prompt_label'); ?></label>
                            <textarea class="form-control bg-dark text-white" id="prompt" name="prompt" rows="4" placeholder="<?php echo __('demo_prompt_placeholder'); ?>" required><?php echo $data['prompt']; ?></textarea>
                            <div class="form-text"><?php echo __('demo_prompt_help'); ?></div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="genre" class="form-label"><?php echo __('demo_genre_label'); ?></label>
                            <select class="form-select bg-dark text-white" id="genre" name="genre">
                                <?php foreach ($data['genres'] as $key => $genre): ?>
                                    <option value="<?php echo $key; ?>" <?php echo $data['genre'] === $key ? 'selected' : ''; ?>>
                                        <?php echo $genre; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-warning"><?php echo __('demo_limitations_notice'); ?></p>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg"><?php echo __('demo_generate_btn'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8 mx-auto text-center">
            <h2 class="mb-4"><?php echo __('demo_join_title'); ?></h2>
            <p class="mb-4"><?php echo __('demo_join_text'); ?></p>
            <a href="<?php echo URLROOT; ?>/users/register" class="btn btn-primary btn-lg"><?php echo __('demo_sign_up_btn'); ?></a>
            <a href="<?php echo URLROOT; ?>/pages/pricing" class="btn btn-outline-light btn-lg ms-3"><?php echo __('demo_pricing_btn'); ?></a>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>