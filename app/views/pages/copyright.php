<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-8 mx-auto text-center">
            <h1 class="display-4 mb-4"><?php echo __('copyright_title', 'Copyright Notice'); ?></h1>
            <p class="lead"><?php echo __('copyright_subtitle', 'Information about copyright ownership and protection'); ?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card bg-dark border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="copyright-section mb-5">
                        <h2 class="mb-4"><?php echo __('copyright_ownership', 'Copyright Ownership'); ?></h2>
                        <p><?php echo __('copyright_ownership_text1', '© 2025 Octaverum AI. All rights reserved.'); ?></p>
                        <p><?php echo __('copyright_ownership_text2', 'The content, design, logos, and other materials on this website are the intellectual property of Octaverum AI and are protected by copyright laws.'); ?></p>
                    </div>

                    <div class="copyright-section mb-5">
                        <h2 class="mb-4"><?php echo __('copyright_content', 'Website Content'); ?></h2>
                        <p><?php echo __('copyright_content_text1', 'All text, graphics, user interfaces, visual interfaces, photographs, trademarks, logos, sounds, music, artwork, computer code, and other materials contained on this website are owned, controlled, or licensed by Octaverum AI.'); ?></p>
                        <p><?php echo __('copyright_content_text2', 'The entire content of this website is protected by copyright as a collective work under Turkish and international copyright laws.'); ?></p>
                    </div>

                    <div class="copyright-section mb-5">
                        <h2 class="mb-4"><?php echo __('copyright_generated', 'AI-Generated Music Copyright'); ?></h2>
                        <p><?php echo __('copyright_generated_text1', 'Music created through our service is subject to the following copyright terms:'); ?></p>
                        
                        <div class="table-responsive mt-4">
                            <table class="table table-dark table-bordered">
                                <thead>
                                    <tr>
                                        <th><?php echo __('copyright_plan', 'Subscription Plan'); ?></th>
                                        <th><?php echo __('copyright_ownership_title', 'Ownership'); ?></th>
                                        <th><?php echo __('copyright_usage_rights', 'Usage Rights'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="badge badge-free"><?php echo __('copyright_free', 'Free'); ?></span></td>
                                        <td><?php echo __('copyright_free_ownership', 'You own the music you create'); ?></td>
                                        <td><?php echo __('copyright_free_usage', 'Personal use only, non-commercial'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge badge-premium"><?php echo __('copyright_premium', 'Premium'); ?></span></td>
                                        <td><?php echo __('copyright_premium_ownership', 'You own the music you create'); ?></td>
                                        <td><?php echo __('copyright_premium_usage', 'Personal and limited commercial use (small businesses, content creation)'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge badge-professional"><?php echo __('copyright_professional', 'Professional'); ?></span></td>
                                        <td><?php echo __('copyright_professional_ownership', 'You own the music you create with full rights'); ?></td>
                                        <td><?php echo __('copyright_professional_usage', 'Unlimited commercial use, including large-scale distribution and licensing'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <p class="mt-4"><?php echo __('copyright_generated_text2', 'While you retain ownership of the music you create, Octaverum AI reserves the right to use anonymized data from your creations to improve our AI systems.'); ?></p>
                    </div>

                    <div class="copyright-section mb-5">
                        <h2 class="mb-4"><?php echo __('copyright_unauthorized', 'Unauthorized Use'); ?></h2>
                        <p><?php echo __('copyright_unauthorized_text1', 'Any unauthorized use of the materials on this website may violate copyright, trademark, and other applicable laws and could result in criminal or civil penalties.'); ?></p>
                        <p><?php echo __('copyright_unauthorized_text2', 'You may not modify, reproduce, distribute, display, sell, or create derivative works of any materials on this website without prior written consent from Octaverum AI.'); ?></p>
                    </div>

                    <div class="copyright-section mb-5">
                        <h2 class="mb-4"><?php echo __('copyright_license', 'Limited License'); ?></h2>
                        <p><?php echo __('copyright_license_text', 'We grant you a limited, non-exclusive, non-transferable license to access and use our website for personal, non-commercial purposes. This license does not include:'); ?></p>
                        <ul>
                            <li><?php echo __('copyright_license_text1', 'Modifying or copying the website materials'); ?></li>
                            <li><?php echo __('copyright_license_text2', 'Using the materials for any commercial purpose'); ?></li>
                            <li><?php echo __('copyright_license_text3', 'Attempting to decompile or reverse engineer any software contained on the website'); ?></li>
                            <li><?php echo __('copyright_license_text4', 'Removing any copyright or other proprietary notations'); ?></li>
                            <li><?php echo __('copyright_license_text5', 'Transferring the materials to another person or "mirroring" the materials on any other server'); ?></li>
                        </ul>
                    </div>

                    <div class="copyright-section mb-5">
                        <h2 class="mb-4"><?php echo __('copyright_dmca', 'DMCA Compliance'); ?></h2>
                        <p><?php echo __('copyright_dmca_text1', 'If you believe that any content on our website infringes upon your copyright, please send a notification of claimed copyright infringement to our designated agent:'); ?></p>
                        <p class="ms-4">
                            <a href="mailto:copyright@octaverum.com" class="text-neon-pink">copyright@octaverum.com</a>
                        </p>
                        <p><?php echo __('copyright_dmca_text2', 'Your notification should include:'); ?></p>
                        <ul>
                            <li><?php echo __('copyright_dmca_list1', 'Identification of the copyrighted work claimed to be infringed'); ?></li>
                            <li><?php echo __('copyright_dmca_list2', 'Identification of the material that is claimed to be infringing'); ?></li>
                            <li><?php echo __('copyright_dmca_list3', 'Your contact information (name, address, telephone number, and email)'); ?></li>
                            <li><?php echo __('copyright_dmca_list4', 'A statement that you have a good faith belief that the use is not authorized'); ?></li>
                            <li><?php echo __('copyright_dmca_list5', 'A statement, under penalty of perjury, that the information is accurate and that you are authorized to act on behalf of the copyright owner'); ?></li>
                        </ul>
                    </div>

                    <div class="copyright-section">
                        <h2 class="mb-4"><?php echo __('copyright_questions', 'Questions'); ?></h2>
                        <p><?php echo __('copyright_questions_text', 'If you have any questions about our copyright notice, please contact us at:'); ?></p>
                        <p class="ms-4">
                            <a href="mailto:legal@octaverum.com" class="text-neon-pink">legal@octaverum.com</a>
                        </p>
                    </div>

                    <div class="copyright-meta mt-5 text-muted">
                        <p><?php echo __('copyright_last_updated', 'Last Updated'); ?>: March 18, 2025</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>