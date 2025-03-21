<?php
// Hata ayıklama ve hata raporlama
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Hata günlüğünü ayarla
ini_set('log_errors', 1);
ini_set('error_log', dirname(dirname(__FILE__)) . '/logs/error.log');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'octaverum_admin');  // Updated to correct username
define('DB_PASS', 'Ach123123123!');    // Keeping existing password
define('DB_NAME', 'octaverum_admin');  // Keep existing database name

// App root (define both versions for compatibility)
define('APP_ROOT', dirname(dirname(__FILE__)));
define('APPROOT', dirname(dirname(__FILE__))); // Without underscore for backward compatibility

// URL Root (for links)
define('URL_ROOT', 'http://octaverum.com');  // Tam URL ile değiştiriyorum
define('URLROOT', URL_ROOT); // Add compatibility constant for code using URLROOT without underscore

// Site name
define('SITE_NAME', 'Octaverum AI');

// App version
define('APP_VERSION', '1.0.0');

// API configuration
define('API_KEY', 'your_ai_service_api_key');
define('API_ENDPOINT', 'https://api.ai-music-service.com/generate');

// PiAPI configuration
define('PIAPI_KEY', '4cacc2db99c9c028214ef5abd59918154d73845d812f3ff5645f37e20b37c2af');
define('PIAPI_ENDPOINT', 'https://api.piapi.ai');

// Subscription tiers
define('FREE_GENERATION_LIMIT', 10);
define('PREMIUM_GENERATION_LIMIT', 50);
define('PREMIUM_PRICE', 99.90);
define('PRO_PRICE', 199.90);

// Default language
define('DEFAULT_LANGUAGE', 'en');

// Available languages
define('AVAILABLE_LANGUAGES', [
    'en' => 'English',
    'tr' => 'Turkish',
    'es' => 'Spanish',
    'fr' => 'French',
    'de' => 'German'
]);