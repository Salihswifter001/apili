<?php require APPROOT . '/views/inc/header.php'; ?>
<?php $langText = $data['langText']; ?>

<div class="pricing-section">
    <div class="pricing-header">
        <h1><?php echo $data['title']; ?></h1>
        <p><?php echo $data['description']; ?></p>
        
        <?php flash('subscription_success'); ?>
        
        <div class="current-plan-info">
            <h3><?php echo $langText['pricing_current_plan']; ?>: 
                <span class="highlight-plan">
                    <?php 
                    switch($data['user']->subscription_tier) {
                        case 'free':
                            echo $langText['plan_free'];
                            break;
                        case 'premium':
                            echo $langText['plan_premium'];
                            break;
                        case 'professional':
                            echo $langText['plan_pro'];
                            break;
                        default:
                            echo $langText['plan_free'];
                    }
                    ?>
                </span>
            </h3>
        </div>
    </div>

    <div class="pricing-container">
        <!-- Free Plan -->
        <div class="pricing-plan <?php echo ($data['user']->subscription_tier == 'free') ? 'active-plan' : ''; ?>">
            <div class="plan-header free-header">
                <h2><?php echo $langText['plan_free']; ?></h2>
            </div>
            
            <div class="plan-price">
                <span class="price-value">0₺</span>
            </div>
            
            <div class="plan-badge free-badge">
                <span><?php echo $langText['plan_starter']; ?></span>
            </div>
            
            <div class="plan-features">
                <div class="feature-highlight">
                    <div class="feature-item">
                        <span class="feature-title"><?php echo $langText['pricing_monthly_limit']; ?>:</span>
                        <span class="feature-value">10/mo</span>
                    </div>
                    
                    <div class="feature-item">
                        <span class="feature-title"><?php echo $langText['pricing_quality']; ?>:</span>
                        <span class="feature-value"><?php echo $langText['plan_quality_standard']; ?></span>
                    </div>
                </div>
                
                <ul class="feature-list">
                    <li><i class="fas fa-check-circle"></i> <?php echo $langText['plan_parameters_basic']; ?></li>
                    <li><i class="fas fa-check-circle"></i> <?php echo $langText['plan_mp3']; ?></li>
                    <li><i class="fas fa-check-circle"></i> <?php echo $langText['plan_personal_use']; ?></li>
                </ul>
            </div>
            
            <div class="plan-action">
                <?php if($data['user']->subscription_tier == 'free'): ?>
                    <button class="action-button free-button current-plan-btn" disabled>
                        <?php echo $langText['pricing_current_plan']; ?>
                    </button>
                <?php else: ?>
                    <form action="<?php echo URL_ROOT; ?>/users/subscription" method="POST">
                        <input type="hidden" name="subscription_tier" value="free">
                        <button type="submit" class="action-button free-button">
                            <?php echo $langText['plan_free']; ?>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Premium Plan -->
        <div class="pricing-plan premium-plan <?php echo ($data['user']->subscription_tier == 'premium') ? 'active-plan' : ''; ?>">
            <div class="plan-popular"><?php echo $langText['plan_popular']; ?></div>
            <div class="plan-header premium-header">
                <h2><?php echo $langText['plan_premium']; ?></h2>
            </div>
            
            <div class="plan-price">
                <span class="price-value"><?php echo PREMIUM_PRICE; ?>₺</span>
                <span class="price-period">/mo</span>
            </div>
            
            <div class="plan-badge premium-badge">
                <span><?php echo $langText['plan_popular']; ?></span>
            </div>
            
            <div class="plan-features">
                <div class="feature-highlight">
                    <div class="feature-item premium-feature">
                        <span class="feature-title"><?php echo $langText['pricing_monthly_limit']; ?>:</span>
                        <span class="feature-value">50/mo</span>
                    </div>
                    
                    <div class="feature-item premium-feature">
                        <span class="feature-title"><?php echo $langText['pricing_quality']; ?>:</span>
                        <span class="feature-value"><?php echo $langText['plan_quality_high']; ?></span>
                    </div>
                </div>
                
                <ul class="feature-list">
                    <li><i class="fas fa-check-circle"></i> <?php echo $langText['plan_parameters_advanced']; ?></li>
                    <li><i class="fas fa-check-circle"></i> <?php echo $langText['plan_formats_multiple']; ?></li>
                    <li><i class="fas fa-check-circle"></i> <?php echo $langText['plan_lyrics']; ?></li>
                    <li><i class="fas fa-check-circle"></i> <?php echo $langText['plan_commercial_limited']; ?></li>
                </ul>
            </div>
            
            <div class="plan-action">
                <?php if($data['user']->subscription_tier == 'premium'): ?>
                    <button class="action-button premium-button current-plan-btn" disabled>
                        <?php echo $langText['pricing_current_plan']; ?>
                    </button>
                <?php else: ?>
                    <form action="<?php echo URL_ROOT; ?>/users/subscription" method="POST">
                        <input type="hidden" name="subscription_tier" value="premium">
                        <button type="submit" class="action-button premium-button">
                            <?php echo $langText['pricing_upgrade_btn']; ?>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Professional Plan -->
        <div class="pricing-plan <?php echo ($data['user']->subscription_tier == 'professional') ? 'active-plan' : ''; ?>">
            <div class="plan-header pro-header">
                <h2><?php echo $langText['plan_pro']; ?></h2>
            </div>
            
            <div class="plan-price">
                <span class="price-value"><?php echo PRO_PRICE; ?>₺</span>
                <span class="price-period">/mo</span>
            </div>
            
            <div class="plan-badge pro-badge">
                <span><?php echo $langText['plan_unlimited']; ?></span>
            </div>
            
            <div class="plan-features">
                <div class="feature-highlight">
                    <div class="feature-item pro-feature">
                        <span class="feature-title"><?php echo $langText['pricing_monthly_limit']; ?>:</span>
                        <span class="feature-value"><?php echo $langText['plan_unlimited']; ?></span>
                    </div>
                    
                    <div class="feature-item pro-feature">
                        <span class="feature-title"><?php echo $langText['pricing_quality']; ?>:</span>
                        <span class="feature-value"><?php echo $langText['plan_quality_studio']; ?></span>
                    </div>
                </div>
                
                <ul class="feature-list">
                    <li><i class="fas fa-check-circle"></i> <?php echo $langText['plan_parameters_full']; ?></li>
                    <li><i class="fas fa-check-circle"></i> <?php echo $langText['plan_commercial_license']; ?></li>
                    <li><i class="fas fa-check-circle"></i> <?php echo $langText['plan_priority']; ?></li>
                    <li><i class="fas fa-check-circle"></i> <?php echo $langText['plan_stems']; ?></li>
                    <li><i class="fas fa-check-circle"></i> <?php echo $langText['plan_lyrics']; ?></li>
                </ul>
            </div>
            
            <div class="plan-action">
                <?php if($data['user']->subscription_tier == 'professional'): ?>
                    <button class="action-button pro-button current-plan-btn" disabled>
                        <?php echo $langText['pricing_current_plan']; ?>
                    </button>
                <?php else: ?>
                    <form action="<?php echo URL_ROOT; ?>/users/subscription" method="POST">
                        <input type="hidden" name="subscription_tier" value="professional">
                        <button type="submit" class="action-button pro-button">
                            <?php echo $langText['pricing_upgrade_btn']; ?>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="payment-processing-info">
        <h3><?php echo $langText['pricing_faq_q2']; ?></h3>
        <p><?php echo $langText['pricing_faq_a2']; ?></p>
        
        <h3><?php echo $langText['pricing_faq_q1']; ?></h3>
        <p><?php echo $langText['pricing_faq_a1']; ?></p>
    </div>
</div>

<style>
/* Modern Pricing Styles */
.pricing-section {
    background-color: #0e0e1a;
    color: white;
    padding: 60px 20px;
    text-align: center;
}

.pricing-header {
    margin-bottom: 60px;
}

.pricing-header h1 {
    color: #0ff7ef;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 15px;
    text-shadow: 0 0 15px rgba(15, 247, 239, 0.4);
}

.pricing-header p {
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto;
    opacity: 0.9;
}

.current-plan-info {
    margin-top: 30px;
    background-color: rgba(255, 255, 255, 0.05);
    padding: 15px;
    border-radius: 10px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.current-plan-info h3 {
    margin: 0;
    font-size: 1.2rem;
}

.highlight-plan {
    position: relative;
    padding: 0 5px;
}

.highlight-plan::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -3px;
    width: 100%;
    height: 2px;
    background-color: #0ff7ef;
}

.pricing-container {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    max-width: 1200px;
    margin: 0 auto;
    gap: 30px;
}

.pricing-plan {
    background-color: #15151f;
    border-radius: 12px;
    width: 300px;
    text-align: center;
    padding-bottom: 30px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
}

.pricing-plan:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
}

.active-plan {
    box-shadow: 0 0 30px rgba(15, 247, 239, 0.3);
    border: 2px solid #0ff7ef;
}

.active-plan.premium-plan {
    box-shadow: 0 0 30px rgba(240, 173, 78, 0.3);
    border: 2px solid #f0ad4e;
}

.active-plan:nth-child(3) {
    box-shadow: 0 0 30px rgba(255, 65, 199, 0.3);
    border: 2px solid #ff41c7;
}

.premium-plan {
    transform: scale(1.05);
    box-shadow: 0 10px 30px rgba(240, 173, 78, 0.2);
    z-index: 1;
}

.premium-plan:hover {
    transform: translateY(-10px) scale(1.05);
}

.plan-popular {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    background-color: #f0ad4e;
    color: white;
    padding: 7px 0;
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 1px;
}

.plan-header {
    padding: 25px 0 15px;
}

.plan-header h2 {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0;
}

.plan-price {
    padding: 15px 0 20px;
}

.price-value {
    font-size: 2.8rem;
    font-weight: 700;
    font-family: 'Orbitron', sans-serif;
}

.price-period {
    font-size: 1rem;
    opacity: 0.8;
}

.plan-badge {
    width: 70%;
    margin: 0 auto 25px;
    padding: 8px 0;
    border-radius: 25px;
    font-weight: 600;
}

.plan-features {
    padding: 0 25px;
    margin-bottom: 30px;
}

.feature-highlight {
    margin-bottom: 20px;
}

.feature-item {
    margin-bottom: 10px;
    border-radius: 6px;
    padding: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.feature-list {
    list-style: none;
    padding: 0;
    text-align: left;
}

.feature-list li {
    padding: 8px 0;
    display: flex;
    align-items: center;
}

.feature-list i {
    margin-right: 10px;
}

.plan-action {
    padding: 0 25px;
}

.action-button {
    display: block;
    width: 100%;
    padding: 12px 0;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.current-plan-btn {
    background-color: rgba(255, 255, 255, 0.1) !important;
    color: rgba(255, 255, 255, 0.7) !important;
    cursor: default;
    box-shadow: none !important;
}

.payment-processing-info {
    margin-top: 60px;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
    background-color: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
    padding: 25px;
    text-align: left;
}

.payment-processing-info h3 {
    color: #0ff7ef;
    margin-top: 0;
    margin-bottom: 10px;
}

.payment-processing-info p {
    margin-bottom: 25px;
}

.payment-processing-info p:last-child {
    margin-bottom: 0;
}

/* Free Plan Styles */
.free-header h2 {
    color: #0ff7ef;
}

.free-badge {
    background-color: rgba(15, 247, 239, 0.1);
    color: #0ff7ef;
}

.feature-item {
    background-color: rgba(15, 247, 239, 0.05);
}

.feature-title {
    color: #0ff7ef;
    font-weight: 500;
}

.feature-list i {
    color: #0ff7ef;
}

.free-button {
    background-color: #0ff7ef;
    color: #000;
}

.free-button:hover {
    background-color: #0ae0d5;
    box-shadow: 0 0 20px rgba(15, 247, 239, 0.5);
}

/* Premium Plan Styles */
.premium-header h2 {
    color: #f0ad4e;
}

.premium-badge {
    background-color: rgba(240, 173, 78, 0.1);
    color: #f0ad4e;
}

.premium-feature {
    background-color: rgba(240, 173, 78, 0.05);
}

.premium-feature .feature-title {
    color: #f0ad4e;
}

.premium-plan .feature-list i {
    color: #f0ad4e;
}

.premium-button {
    background-color: #f0ad4e;
    color: #000;
}

.premium-button:hover {
    background-color: #e09d43;
    box-shadow: 0 0 20px rgba(240, 173, 78, 0.5);
}

/* Pro Plan Styles */
.pro-header h2 {
    color: #ff41c7;
}

.pro-badge {
    background-color: rgba(255, 65, 199, 0.1);
    color: #ff41c7;
}

.pro-feature {
    background-color: rgba(255, 65, 199, 0.05);
}

.pro-feature .feature-title {
    color: #ff41c7;
}

.pricing-plan:nth-child(3) .feature-list i {
    color: #ff41c7;
}

.pro-button {
    background-color: #ff41c7;
    color: #000;
}

.pro-button:hover {
    background-color: #e837b2;
    box-shadow: 0 0 20px rgba(255, 65, 199, 0.5);
}

/* Responsive Adjustments */
@media (max-width: 992px) {
    .pricing-container {
        flex-direction: column;
        align-items: center;
    }
    
    .pricing-plan {
        width: 350px;
        margin-bottom: 30px;
    }
    
    .premium-plan {
        transform: none;
        order: -1;
    }
    
    .premium-plan:hover {
        transform: translateY(-10px);
    }
}

@media (max-width: 576px) {
    .pricing-plan {
        width: 100%;
        max-width: 320px;
    }
}
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?>