<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-8 mx-auto text-center">
            <h1 class="display-4 mb-4"><?php echo __('contact_title'); ?></h1>
            <p class="lead"><?php echo __('contact_subtitle'); ?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="card bg-dark border-0 contact-form-card">
                <div class="card-body p-4">
                    <h2 class="mb-4"><?php echo __('contact_form_title'); ?></h2>
                    
                    <?php flash('contact_success'); ?>
                    
                    <form action="<?php echo URL_ROOT; ?>/pages/contact" method="POST" id="contactForm">
                        <div class="mb-3">
                            <label for="name" class="form-label"><?php echo __('contact_name'); ?> <span class="required">*</span></label>
                            <div class="input-with-icon">
                                <i class="fas fa-user"></i>
                                <input type="text" class="form-control bg-dark text-white <?php echo (!empty($data['errors']['name'])) ? 'is-invalid' : ''; ?>"
                                       id="name" name="name" value="<?php echo isset($data['formData']['name']) ? htmlspecialchars($data['formData']['name']) : ''; ?>">
                                <?php if (!empty($data['errors']['name'])): ?>
                                    <div class="invalid-feedback"><?php echo $data['errors']['name']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label"><?php echo __('contact_email'); ?> <span class="required">*</span></label>
                            <div class="input-with-icon">
                                <i class="fas fa-envelope"></i>
                                <input type="email" class="form-control bg-dark text-white <?php echo (!empty($data['errors']['email'])) ? 'is-invalid' : ''; ?>"
                                       id="email" name="email" value="<?php echo isset($data['formData']['email']) ? htmlspecialchars($data['formData']['email']) : ''; ?>">
                                <?php if (!empty($data['errors']['email'])): ?>
                                    <div class="invalid-feedback"><?php echo $data['errors']['email']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="subject" class="form-label"><?php echo __('contact_subject'); ?> <span class="required">*</span></label>
                            <div class="input-with-icon">
                                <i class="fas fa-tag"></i>
                                <input type="text" class="form-control bg-dark text-white <?php echo (!empty($data['errors']['subject'])) ? 'is-invalid' : ''; ?>"
                                       id="subject" name="subject" value="<?php echo isset($data['formData']['subject']) ? htmlspecialchars($data['formData']['subject']) : ''; ?>">
                                <?php if (!empty($data['errors']['subject'])): ?>
                                    <div class="invalid-feedback"><?php echo $data['errors']['subject']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="inquiry_type" class="form-label"><?php echo __('contact_inquiry_type'); ?> <span class="required">*</span></label>
                            <div class="input-with-icon">
                                <i class="fas fa-question-circle"></i>
                                <select class="form-control bg-dark text-white <?php echo (!empty($data['errors']['inquiry_type'])) ? 'is-invalid' : ''; ?>"
                                        id="inquiry_type" name="inquiry_type">
                                    <option value=""><?php echo __('contact_select_inquiry'); ?></option>
                                    <?php
                                    $inquiryTypes = [
                                        'general' => __('contact_general'),
                                        'technical' => __('contact_technical'),
                                        'billing' => __('contact_billing'),
                                        'feature' => __('contact_feature'),
                                        'other' => __('contact_other')
                                    ];
                                    
                                    foreach ($inquiryTypes as $value => $label):
                                        $selected = (isset($data['formData']['inquiry_type']) && $data['formData']['inquiry_type'] === $value) ? 'selected' : '';
                                    ?>
                                        <option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (!empty($data['errors']['inquiry_type'])): ?>
                                    <div class="invalid-feedback"><?php echo $data['errors']['inquiry_type']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label"><?php echo __('contact_message'); ?> <span class="required">*</span></label>
                            <textarea class="form-control bg-dark text-white <?php echo (!empty($data['errors']['message'])) ? 'is-invalid' : ''; ?>"
                                     id="message" name="message" rows="5"><?php echo isset($data['formData']['message']) ? htmlspecialchars($data['formData']['message']) : ''; ?></textarea>
                            <?php if (!empty($data['errors']['message'])): ?>
                                <div class="invalid-feedback"><?php echo $data['errors']['message']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group mb-4">
                            <div class="checkbox-wrapper">
                                <input type="checkbox" name="privacy" id="privacy"
                                       class="<?php echo (!empty($data['errors']['privacy'])) ? 'is-invalid' : ''; ?>"
                                       <?php echo (isset($data['formData']['privacy']) && $data['formData']['privacy']) ? 'checked' : ''; ?>>
                                <label for="privacy"><?php echo __('contact_privacy'); ?> <span class="required">*</span></label>
                                <?php if (!empty($data['errors']['privacy'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $data['errors']['privacy']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-neon-pink btn-lg"><?php echo __('contact_send_btn'); ?></button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-dark border-0 h-100 contact-info-card">
                <div class="card-body p-4">
                    <h2 class="mb-4"><?php echo __('contact_info_title'); ?></h2>
                    
                    <div class="contact-info-item mb-4">
                        <div class="contact-icon-wrapper">
                            <i class="fas fa-envelope contact-icon"></i>
                        </div>
                        <div class="contact-info-content">
                            <h5><?php echo __('contact_email_label'); ?></h5>
                            <p><a href="mailto:support@octaverum.com" class="text-neon-pink contact-link">support@octaverum.com</a></p>
                            <p class="text-muted small"><?php echo __('contact_email_response'); ?></p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item mb-4">
                        <div class="contact-icon-wrapper">
                            <i class="fas fa-headset contact-icon"></i>
                        </div>
                        <div class="contact-info-content">
                            <h5><?php echo __('contact_support_label'); ?></h5>
                            <p><?php echo __('contact_support_hours'); ?></p>
                            <a href="#" class="btn btn-sm btn-neon-pink mt-2"><?php echo __('contact_live_chat'); ?></a>
                        </div>
                    </div>
                    
                    <div class="contact-info-item mb-4">
                        <div class="contact-icon-wrapper">
                            <i class="fas fa-map-marker-alt contact-icon"></i>
                        </div>
                        <div class="contact-info-content">
                            <h5><?php echo __('contact_address_label'); ?></h5>
                            <p>
                                Octaverum AI, Inc.<br>
                                123 Tech Avenue<br>
                                Istanbul, Turkey
                            </p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item mb-4">
                        <div class="contact-icon-wrapper">
                            <i class="fas fa-phone contact-icon"></i>
                        </div>
                        <div class="contact-info-content">
                            <h5><?php echo __('contact_phone_label'); ?></h5>
                            <p>+90 (555) 123-4567</p>
                        </div>
                    </div>
                    
                    <div class="social-links mt-5">
                        <h5 class="mb-3"><?php echo __('contact_social_title'); ?></h5>
                        <div class="d-flex">
                            <a href="#" class="social-icon me-3"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-icon me-3"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-icon me-3"><i class="fab fa-facebook"></i></a>
                            <a href="#" class="social-icon me-3"><i class="fab fa-discord"></i></a>
                            <a href="#" class="social-icon me-3"><i class="fab fa-linkedin"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional styling for contact page -->
<style>
.contact-form-card,
.contact-info-card {
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
}

.contact-form-card:hover,
.contact-info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.contact-info-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 2rem;
}

.contact-icon-wrapper {
    background-color: rgba(255, 65, 199, 0.1); 
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
}

.contact-icon {
    color: var(--accent-tertiary);
    font-size: 1.25rem;
}

.contact-info-content {
    flex: 1;
}

.contact-link {
    transition: all 0.3s ease;
}

.contact-link:hover {
    color: #ff65ce;
    text-shadow: 0 0 10px rgba(255, 65, 199, 0.5);
}

.social-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
    color: var(--text-primary);
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.social-icon:hover {
    background-color: var(--accent-tertiary);
    color: var(--bg-primary);
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(255, 65, 199, 0.4);
}

#contactForm .form-control:focus {
    border-color: var(--accent-tertiary);
    box-shadow: 0 0 0 2px rgba(255, 65, 199, 0.25);
}
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?>