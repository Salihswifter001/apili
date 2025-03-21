<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container py-5 faq-container">
    <div class="row mb-5">
        <div class="col-md-8 mx-auto text-center">
            <h1 class="display-4 mb-4"><?php echo __('faq_title', 'FAQ'); ?></h1>
            <p class="lead"><?php echo __('faq_subtitle', 'Find answers to common questions about the Octaverum AI platform'); ?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="accordion faq-accordion" id="faqAccordion">
                <?php $count = 0; ?>
                <?php foreach ($data['faqs'] as $faq): ?>
                    <?php $count++; ?>
                    <div class="accordion-item bg-dark text-white border-0 mb-4 faq-item">
                        <h2 class="accordion-header" id="heading<?php echo $count; ?>">
                            <button class="accordion-button <?php echo ($count > 1) ? 'collapsed' : ''; ?> bg-dark text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $count; ?>" aria-expanded="<?php echo ($count == 1) ? 'true' : 'false'; ?>" aria-controls="collapse<?php echo $count; ?>">
                                <span class="faq-question-number"><?php echo $count; ?>.</span>
                                <?php echo $faq['question']; ?>
                            </button>
                        </h2>
                        <div id="collapse<?php echo $count; ?>" class="accordion-collapse collapse <?php echo ($count == 1) ? 'show' : ''; ?>" aria-labelledby="heading<?php echo $count; ?>" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p><?php echo $faq['answer']; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-md-8 mx-auto text-center">
            <div class="faq-contact-box p-4">
                <h3><?php echo __('faq_more_questions', 'Still have questions?'); ?></h3>
                <p><?php echo __('faq_contact_text', 'If you couldn\'t find the answer to your question, feel free to contact our support team.'); ?></p>
                <a href="<?php echo URL_ROOT; ?>/pages/contact" class="btn btn-neon-pink mt-3">
                    <?php echo __('faq_contact_button', 'Contact Support'); ?>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Additional styling for FAQ page -->
<style>
.faq-container {
    padding-top: calc(var(--header-height) + 20px);
}

.faq-item {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.faq-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.accordion-button {
    font-weight: 600;
    font-size: 1.1rem;
    padding: 1.25rem;
}

.accordion-button:not(.collapsed) {
    background-color: var(--accent-primary) !important;
    color: white !important;
}

.accordion-button:focus {
    box-shadow: none;
    border-color: var(--accent-primary);
}

.accordion-body {
    padding: 1.5rem;
    font-size: 1.05rem;
    line-height: 1.6;
}

.faq-question-number {
    color: var(--accent-tertiary);
    font-weight: bold;
    margin-right: 15px;
    font-family: 'Orbitron', sans-serif;
}

.faq-contact-box {
    background: linear-gradient(45deg, rgba(15, 247, 239, 0.1), rgba(255, 65, 199, 0.1));
    border-radius: 15px;
    border: 1px solid rgba(255, 65, 199, 0.2);
    margin-top: 3rem;
}
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?>