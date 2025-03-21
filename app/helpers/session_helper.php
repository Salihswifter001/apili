<?php
/**
 * Session Helper Functions
 */

// Flash message helper
// EXAMPLE - flash('register_success', 'You are now registered');
// DISPLAY IN VIEW - echo flash('register_success');
function flash($name = '', $message = '', $class = 'alert alert-success') {
    if (!empty($name)) {
        // Set flash message
        if (!empty($message) && empty($_SESSION[$name])) {
            if (!empty($_SESSION[$name])) {
                unset($_SESSION[$name]);
            }

            if (!empty($_SESSION[$name . '_class'])) {
                unset($_SESSION[$name . '_class']);
            }

            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
        } elseif (empty($message) && !empty($_SESSION[$name])) {
            // Display flash message
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
            echo '<div class="' . $class . '" id="flash-message">' . $_SESSION[$name] . '</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Get current user data
function getCurrentUser() {
    return isset($_SESSION['user_data']) ? $_SESSION['user_data'] : null;
}

// Get user subscription tier
function getUserTier() {
    if (isset($_SESSION['user_data'])) {
        return $_SESSION['user_data']->subscription_tier;
    }
    return 'free';
}

// Check if user has reached their generation limit
function checkGenerationLimit() {
    if (!isLoggedIn()) {
        return false;
    }

    $tier = getUserTier();
    $currentGenCount = $_SESSION['user_data']->monthly_generations ?? 0;
    
    switch ($tier) {
        case 'free':
            return $currentGenCount < FREE_GENERATION_LIMIT;
        case 'premium':
            return $currentGenCount < PREMIUM_GENERATION_LIMIT;
        case 'professional':
            return true; // Unlimited
        default:
            return false;
    }
}

// Increment generation count
function incrementGenerationCount() {
    if (isLoggedIn() && isset($_SESSION['user_data'])) {
        if (!isset($_SESSION['user_data']->monthly_generations)) {
            $_SESSION['user_data']->monthly_generations = 0;
        }
        $_SESSION['user_data']->monthly_generations++;
    }
}