<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-8 mx-auto text-center">
            <h1 class="display-4 mb-4"><?php echo __('terms_title', 'Terms of Service'); ?></h1>
            <p class="lead"><?php echo __('terms_subtitle', 'Please read these terms carefully before using our service'); ?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card bg-dark border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="terms-section mb-5">
                        <h2 class="mb-4">1. <?php echo __('terms_acceptance', 'Acceptance of Terms'); ?></h2>
                        <p><?php echo __('terms_acceptance_text', 'By accessing or using Octaverum AI services, you agree to be bound by these Terms of Service and all applicable laws and regulations. If you do not agree with any of these terms, you are prohibited from using or accessing this site.'); ?></p>
                    </div>

                    <div class="terms-section mb-5">
                        <h2 class="mb-4">2. <?php echo __('terms_description', 'Service Description'); ?></h2>
                        <p><?php echo __('terms_description_text', 'Octaverum AI provides an artificial intelligence-based music generation service. Users can create, modify, and download music tracks within the limits of their subscription plan.'); ?></p>
                    </div>

                    <div class="terms-section mb-5">
                        <h2 class="mb-4">3. <?php echo __('terms_accounts', 'User Accounts'); ?></h2>
                        <p><?php echo __('terms_accounts_text1', 'You are responsible for maintaining the confidentiality of your account and password and for restricting access to your computer. You agree to accept responsibility for all activities that occur under your account.'); ?></p>
                        <p><?php echo __('terms_accounts_text2', 'You must provide accurate, current, and complete information during the registration process and keep your account information updated.'); ?></p>
                    </div>

                    <div class="terms-section mb-5">
                        <h2 class="mb-4">4. <?php echo __('terms_subscription', 'Subscription Plans'); ?></h2>
                        <p><?php echo __('terms_subscription_text1', 'Octaverum AI offers various subscription plans with different features and usage limits. Subscription fees are charged in advance on a monthly basis.'); ?></p>
                        <p><?php echo __('terms_subscription_text2', 'You can upgrade or downgrade your subscription at any time. Changes will take effect at the beginning of the next billing cycle.'); ?></p>
                        <p><?php echo __('terms_subscription_text3', 'Unused generations do not roll over to the next month and will be reset on your billing date.'); ?></p>
                    </div>

                    <div class="terms-section mb-5">
                        <h2 class="mb-4">5. <?php echo __('terms_payments', 'Payments and Billing'); ?></h2>
                        <p><?php echo __('terms_payments_text1', 'By subscribing to a paid plan, you authorize us to charge the payment method provided on a recurring basis until you cancel your subscription.'); ?></p>
                        <p><?php echo __('terms_payments_text2', 'If we are unable to process your payment, we may suspend or terminate your account until the payment is successfully processed.'); ?></p>
                    </div>

                    <div class="terms-section mb-5">
                        <h2 class="mb-4">6. <?php echo __('terms_cancellation', 'Cancellation and Refunds'); ?></h2>
                        <p><?php echo __('terms_cancellation_text1', 'You can cancel your subscription at any time from your account settings. After cancellation, your account will remain active until the end of the current billing period.'); ?></p>
                        <p><?php echo __('terms_cancellation_text2', 'We do not provide refunds for partial months of service or for months where the service was not used.'); ?></p>
                    </div>

                    <div class="terms-section mb-5">
                        <h2 class="mb-4">7. <?php echo __('terms_content', 'Content Ownership and Licensing'); ?></h2>
                        <p><?php echo __('terms_content_text1', 'Music generated through Octaverum AI is owned by you. However, we grant Octaverum AI a non-exclusive license to use, reproduce, and display your generated content for service improvement and promotional purposes.'); ?></p>
                        <p><?php echo __('terms_content_text2', 'Different licensing terms apply to different subscription tiers:'); ?></p>
                        <ul>
                            <li><?php echo __('terms_content_free', 'Free: Music can be used for personal, non-commercial purposes only.'); ?></li>
                            <li><?php echo __('terms_content_premium', 'Premium: Music can be used for personal and limited commercial purposes.'); ?></li>
                            <li><?php echo __('terms_content_professional', 'Professional: Music can be used for all commercial purposes with full rights.'); ?></li>
                        </ul>
                    </div>

                    <div class="terms-section mb-5">
                        <h2 class="mb-4">8. <?php echo __('terms_prohibited', 'Prohibited Uses'); ?></h2>
                        <p><?php echo __('terms_prohibited_text', 'You may not use Octaverum AI to:'); ?></p>
                        <ul>
                            <li><?php echo __('terms_prohibited_1', 'Generate content that infringes on copyrights or other intellectual property rights'); ?></li>
                            <li><?php echo __('terms_prohibited_2', 'Create content that promotes hate speech, violence, or discrimination'); ?></li>
                            <li><?php echo __('terms_prohibited_3', 'Engage in activities that disrupt or interfere with the service'); ?></li>
                            <li><?php echo __('terms_prohibited_4', 'Attempt to bypass usage limits or security measures'); ?></li>
                        </ul>
                    </div>

                    <div class="terms-section mb-5">
                        <h2 class="mb-4">9. <?php echo __('terms_limitation', 'Limitation of Liability'); ?></h2>
                        <p><?php echo __('terms_limitation_text', 'In no event shall Octaverum AI be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from your access to or use of or inability to access or use the service.'); ?></p>
                    </div>

                    <div class="terms-section mb-5">
                        <h2 class="mb-4">10. <?php echo __('terms_changes', 'Changes to Terms'); ?></h2>
                        <p><?php echo __('terms_changes_text', 'We reserve the right to modify these terms at any time. We will provide notice of any material changes through our service or by sending an email to the address associated with your account.'); ?></p>
                    </div>

                    <div class="terms-section">
                        <h2 class="mb-4">11. <?php echo __('terms_contact', 'Contact Information'); ?></h2>
                        <p><?php echo __('terms_contact_text', 'If you have any questions about these Terms, please contact us at:'); ?></p>
                        <p class="ms-4">
                            <a href="mailto:support@octaverum.com" class="text-neon-pink">support@octaverum.com</a>
                        </p>
                    </div>

                    <div class="terms-meta mt-5 text-muted">
                        <p><?php echo __('terms_last_updated', 'Last Updated'); ?>: March 18, 2025</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>