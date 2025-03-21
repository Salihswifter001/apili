<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="pricing-section">
    <div class="pricing-header">
        <h1>Abonelik Planları</h1>
        <p>Yaratıcı ihtiyaçlarınız için mükemmel planı seçin</p>
    </div>

    <div class="pricing-container">
        <!-- Free Plan -->
        <div class="pricing-plan">
            <div class="plan-header free-header">
                <h2>Free</h2>
            </div>
            
            <div class="plan-price">
                <span class="price-value">0₺</span>
            </div>
            
            <div class="plan-badge free-badge">
                <span>Starter</span>
            </div>
            
            <div class="plan-features">
                <div class="feature-highlight">
                    <div class="feature-item">
                        <span class="feature-title">Songs:</span>
                        <span class="feature-value">10/mo</span>
                    </div>
                    
                    <div class="feature-item">
                        <span class="feature-title">Quality:</span>
                        <span class="feature-value">Standard</span>
                    </div>
                </div>
                
                <ul class="feature-list">
                    <li><i class="fas fa-check-circle"></i> Basic parameters</li>
                    <li><i class="fas fa-check-circle"></i> MP3 downloads</li>
                    <li><i class="fas fa-check-circle"></i> Personal use</li>
                </ul>
            </div>
            
            <div class="plan-action">
                <a href="<?php echo URL_ROOT; ?>/users/register" class="action-button free-button">
                    GET STARTED
                </a>
            </div>
        </div>
        
        <!-- Premium Plan -->
        <div class="pricing-plan premium-plan">
            <div class="plan-popular">POPULAR</div>
            <div class="plan-header premium-header">
                <h2>Premium</h2>
            </div>
            
            <div class="plan-price">
                <span class="price-value">99.90₺</span>
                <span class="price-period">/mo</span>
            </div>
            
            <div class="plan-badge premium-badge">
                <span>Popular</span>
            </div>
            
            <div class="plan-features">
                <div class="feature-highlight">
                    <div class="feature-item premium-feature">
                        <span class="feature-title">Songs:</span>
                        <span class="feature-value">50/mo</span>
                    </div>
                    
                    <div class="feature-item premium-feature">
                        <span class="feature-title">Quality:</span>
                        <span class="feature-value">High</span>
                    </div>
                </div>
                
                <ul class="feature-list">
                    <li><i class="fas fa-check-circle"></i> Advanced parameters</li>
                    <li><i class="fas fa-check-circle"></i> Multiple formats</li>
                    <li><i class="fas fa-check-circle"></i> AI lyrics</li>
                    <li><i class="fas fa-check-circle"></i> Limited commercial use</li>
                </ul>
            </div>
            
            <div class="plan-action">
                <a href="<?php echo URL_ROOT; ?>/users/register" class="action-button premium-button">
                    SIGN UP
                </a>
            </div>
        </div>
        
        <!-- Professional Plan -->
        <div class="pricing-plan">
            <div class="plan-header pro-header">
                <h2>Professional</h2>
            </div>
            
            <div class="plan-price">
                <span class="price-value">199.90₺</span>
                <span class="price-period">/mo</span>
            </div>
            
            <div class="plan-badge pro-badge">
                <span>Unlimited</span>
            </div>
            
            <div class="plan-features">
                <div class="feature-highlight">
                    <div class="feature-item pro-feature">
                        <span class="feature-title">Songs:</span>
                        <span class="feature-value">Unlimited</span>
                    </div>
                    
                    <div class="feature-item pro-feature">
                        <span class="feature-title">Quality:</span>
                        <span class="feature-value">Studio</span>
                    </div>
                </div>
                
                <ul class="feature-list">
                    <li><i class="fas fa-check-circle"></i> Full parameter control</li>
                    <li><i class="fas fa-check-circle"></i> Commercial license</li>
                    <li><i class="fas fa-check-circle"></i> Priority processing</li>
                    <li><i class="fas fa-check-circle"></i> Track stems export</li>
                    <li><i class="fas fa-check-circle"></i> AI-generated lyrics</li>
                </ul>
            </div>
            
            <div class="plan-action">
                <a href="<?php echo URL_ROOT; ?>/users/register" class="action-button pro-button">
                    SIGN UP
                </a>
            </div>
        </div>
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
    padding: 12px 0;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
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