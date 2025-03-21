<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-8 mx-auto text-center">
            <h1 class="display-4 mb-4"><?php echo __('privacy_title', 'Privacy Policy'); ?></h1>
            <p class="lead"><?php echo __('privacy_subtitle', 'How we collect, use, and protect your information'); ?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card bg-dark border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="privacy-section mb-5">
                        <h2 class="mb-4">1. <?php echo __('privacy_introduction', 'Introduction'); ?></h2>
                        <p><?php echo __('privacy_introduction_text', 'This Privacy Policy describes how Octaverum AI ("we", "our", or "us") collects, uses, and shares your personal information when you use our website and services. We respect your privacy and are committed to protecting your personal data.'); ?></p>
                    </div>

                    <div class="privacy-section mb-5">
                        <h2 class="mb-4">2. <?php echo __('privacy_collection', 'Information We Collect'); ?></h2>
                        <p><?php echo __('privacy_collection_text', 'We collect several types of information from and about users of our website, including:'); ?></p>
                        <ul>
                            <li><?php echo __('privacy_collection_personal', 'Personal Information: Name, email address, and payment information when you register for an account or subscribe to our services.'); ?></li>
                            <li><?php echo __('privacy_collection_usage', 'Usage Data: Information about how you use our website and services, including the music you generate, your preferences, and interaction patterns.'); ?></li>
                            <li><?php echo __('privacy_collection_technical', 'Technical Data: IP address, browser type and version, time zone setting, operating system, and device information.'); ?></li>
                            <li><?php echo __('privacy_collection_cookies', 'Cookies and Similar Technologies: Information collected through cookies and similar tracking technologies about your browsing actions and patterns.'); ?></li>
                        </ul>
                    </div>

                    <div class="privacy-section mb-5">
                        <h2 class="mb-4">3. <?php echo __('privacy_usage', 'How We Use Your Information'); ?></h2>
                        <p><?php echo __('privacy_usage_text', 'We use the information we collect to:'); ?></p>
                        <ul>
                            <li><?php echo __('privacy_usage_provide', 'Provide, maintain, and improve our services'); ?></li>
                            <li><?php echo __('privacy_usage_account', 'Create and manage your account'); ?></li>
                            <li><?php echo __('privacy_usage_process', 'Process transactions and send related information'); ?></li>
                            <li><?php echo __('privacy_usage_personalize', 'Personalize your experience and deliver content relevant to your interests'); ?></li>
                            <li><?php echo __('privacy_usage_communicate', 'Communicate with you about updates, promotions, and other news about our services'); ?></li>
                            <li><?php echo __('privacy_usage_analyze', 'Analyze and improve our services, marketing, and user experiences'); ?></li>
                            <li><?php echo __('privacy_usage_protect', 'Protect our services and prevent fraud and abuse'); ?></li>
                        </ul>
                    </div>

                    <div class="privacy-section mb-5">
                        <h2 class="mb-4">4. <?php echo __('privacy_sharing', 'Information Sharing and Disclosure'); ?></h2>
                        <p><?php echo __('privacy_sharing_text', 'We may share your information with:'); ?></p>
                        <ul>
                            <li><?php echo __('privacy_sharing_providers', 'Service Providers: Companies that perform services on our behalf, such as payment processing, data analysis, email delivery, and hosting services.'); ?></li>
                            <li><?php echo __('privacy_sharing_business', 'Business Transfers: If we are involved in a merger, acquisition, or sale of assets, your information may be transferred as part of that transaction.'); ?></li>
                            <li><?php echo __('privacy_sharing_legal', 'Legal Requirements: When required by law or to protect our rights, property, or safety, or the rights, property, or safety of others.'); ?></li>
                            <li><?php echo __('privacy_sharing_consent', 'With Your Consent: We may share your information for other purposes with your consent.'); ?></li>
                        </ul>
                    </div>

                    <div class="privacy-section mb-5">
                        <h2 class="mb-4">5. <?php echo __('privacy_data_security', 'Data Security'); ?></h2>
                        <p><?php echo __('privacy_data_security_text', 'We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, accidental loss, alteration, or disclosure. However, no method of transmission over the Internet or electronic storage is 100% secure, and we cannot guarantee absolute security.'); ?></p>
                    </div>

                    <div class="privacy-section mb-5">
                        <h2 class="mb-4">6. <?php echo __('privacy_data_retention', 'Data Retention'); ?></h2>
                        <p><?php echo __('privacy_data_retention_text', 'We retain your personal information for as long as necessary to fulfill the purposes outlined in this Privacy Policy, unless a longer retention period is required or permitted by law. We will securely delete or anonymize your information when it is no longer needed for these purposes.'); ?></p>
                    </div>

                    <div class="privacy-section mb-5">
                        <h2 class="mb-4">7. <?php echo __('privacy_your_rights', 'Your Rights and Choices'); ?></h2>
                        <p><?php echo __('privacy_your_rights_text', 'Depending on your location, you may have certain rights regarding your personal information, including:'); ?></p>
                        <ul>
                            <li><?php echo __('privacy_rights_access', 'Access: Request access to your personal information.'); ?></li>
                            <li><?php echo __('privacy_rights_correction', 'Correction: Request correction of inaccurate data.'); ?></li>
                            <li><?php echo __('privacy_rights_deletion', 'Deletion: Request deletion of your personal information.'); ?></li>
                            <li><?php echo __('privacy_rights_restriction', 'Restriction: Request restriction of processing of your personal information.'); ?></li>
                            <li><?php echo __('privacy_rights_portability', 'Data Portability: Request transfer of your personal information.'); ?></li>
                            <li><?php echo __('privacy_rights_objection', 'Objection: Object to processing of your personal information.'); ?></li>
                        </ul>
                        <p><?php echo __('privacy_rights_exercise', 'To exercise these rights, please contact us using the information provided at the end of this Privacy Policy.'); ?></p>
                    </div>

                    <div class="privacy-section mb-5">
                        <h2 class="mb-4">8. <?php echo __('privacy_cookies', 'Cookies and Tracking Technologies'); ?></h2>
                        <p><?php echo __('privacy_cookies_text', 'We use cookies and similar tracking technologies to collect information about your browsing activities and to improve your experience on our website. You can manage your cookie preferences through your browser settings.'); ?></p>
                    </div>

                    <div class="privacy-section mb-5">
                        <h2 class="mb-4">9. <?php echo __('privacy_children', 'Children\'s Privacy'); ?></h2>
                        <p><?php echo __('privacy_children_text', 'Our services are not intended for children under 16 years of age. We do not knowingly collect personal information from children under 16. If you are a parent or guardian and believe that your child has provided us with personal information, please contact us.'); ?></p>
                    </div>

                    <div class="privacy-section mb-5">
                        <h2 class="mb-4">10. <?php echo __('privacy_changes', 'Changes to This Privacy Policy'); ?></h2>
                        <p><?php echo __('privacy_changes_text', 'We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last Updated" date. We encourage you to review this Privacy Policy periodically for any changes.'); ?></p>
                    </div>

                    <div class="privacy-section">
                        <h2 class="mb-4">11. <?php echo __('privacy_contact', 'Contact Us'); ?></h2>
                        <p><?php echo __('privacy_contact_text', 'If you have any questions or concerns about this Privacy Policy or our data practices, please contact us at:'); ?></p>
                        <p class="ms-4">
                            <a href="mailto:privacy@octaverum.com" class="text-neon-pink">privacy@octaverum.com</a>
                        </p>
                    </div>

                    <div class="privacy-meta mt-5 text-muted">
                        <p><?php echo __('privacy_last_updated', 'Last Updated'); ?>: March 18, 2025</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>