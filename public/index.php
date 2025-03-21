<?php
// Main entry point for the application
// Load error handler first (includes error reporting configuration)
require_once '../app/helpers/error_handler.php';

// Start session with error handling
try {
    session_start();
} catch (Exception $e) {
    echo "Session initialization error: " . $e->getMessage();
    exit;
}

// Load configuration and helper functions
require_once '../app/config/config.php';
require_once '../app/helpers/url_helper.php';
require_once '../app/helpers/session_helper.php';
require_once '../app/helpers/slugify_helper.php';
require_once '../app/helpers/audio_helper.php';
require_once '../app/helpers/language_helper.php';
require_once '../app/helpers/video_helper.php';

// Define a debug function for tracing
function debug_log($message, $data = null) {
    if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
        error_log("DEBUG: $message" . ($data !== null ? " - " . print_r($data, true) : ""));
    }
}

// Log application start
debug_log("Application initialized");

// Autoload core libraries
spl_autoload_register(function($className) {
    if (file_exists('../app/core/' . $className . '.php')) {
        require_once '../app/core/' . $className . '.php';
    } elseif (file_exists('../app/controllers/' . $className . '.php')) {
        require_once '../app/controllers/' . $className . '.php';
    } elseif (file_exists('../app/models/' . $className . '.php')) {
        require_once '../app/models/' . $className . '.php';
    } elseif (file_exists('../app/helpers/' . $className . '.php')) {
        require_once '../app/helpers/' . $className . '.php';
    }
});

// Initialize application
$app = new App();