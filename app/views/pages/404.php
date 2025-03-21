<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="error-container">
    <div class="error-content">
        <div class="error-code">404</div>
        <h1>Page Not Found</h1>
        <p>The page you are looking for doesn't exist or may have been moved.</p>
        <div class="error-actions">
            <a href="<?php echo URL_ROOT; ?>" class="btn btn-primary btn-lg">
                <i class="fas fa-home"></i> Go Home
            </a>
            <a href="<?php echo URL_ROOT; ?>/pages/contact" class="btn btn-outline btn-lg">
                <i class="fas fa-envelope"></i> Contact Support
            </a>
        </div>
    </div>
</div>

<style>
.error-container {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 2rem;
}

.error-content {
    max-width: 600px;
    animation: fadeInUp 0.5s ease-out;
}

.error-code {
    font-size: 8rem;
    font-weight: 900;
    font-family: 'Orbitron', sans-serif;
    color: var(--accent-primary);
    text-shadow: 0 0 20px var(--glow-primary);
    margin-bottom: 1rem;
    line-height: 1;
}

.error-content h1 {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.error-content p {
    font-size: 1.1rem;
    color: var(--text-secondary);
    margin-bottom: 2rem;
}

.error-actions {
    display: flex;
    justify-content: center;
    gap: 1rem;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .error-code {
        font-size: 6rem;
    }
    
    .error-actions {
        flex-direction: column;
    }
}
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?>