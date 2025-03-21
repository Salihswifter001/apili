<?php
/**
 * Custom Error Handler
 * Shows detailed error information for debugging
 */

// Set error reporting level based on environment
if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
}

// Custom error handler function
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    // Log the error
    error_log("Error [$errno] $errstr in $errfile on line $errline");
    
    // For fatal errors, display user-friendly message in production
    if (in_array($errno, [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        echo "<h1>Application Error</h1>";
        echo "<p>An unexpected error occurred. Please try again later.</p>";
        
        // In development, show more details
        if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
            echo "<h2>Error Details</h2>";
            echo "<p>Error: [$errno] $errstr</p>";
            echo "<p>File: $errfile on line $errline</p>";
            
            // Show backtrace
            echo "<h3>Backtrace:</h3>";
            echo "<pre>";
            debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            echo "</pre>";
        }
        
        exit(1);
    }
    
    // Let PHP handle other errors
    return false;
}

// Set custom error handler
set_error_handler("customErrorHandler", E_ALL);

// Handler for uncaught exceptions
function exceptionHandler($exception) {
    error_log("Uncaught Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine());
    
    echo "<h1>Application Error</h1>";
    echo "<p>An unexpected error occurred. Please try again later.</p>";
    
    // In development, show more details
    if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
        echo "<h2>Exception Details</h2>";
        echo "<p>" . $exception->getMessage() . "</p>";
        echo "<p>File: " . $exception->getFile() . " on line " . $exception->getLine() . "</p>";
        
        // Show stack trace
        echo "<h3>Stack Trace:</h3>";
        echo "<pre>" . $exception->getTraceAsString() . "</pre>";
    }
    
    exit(1);
}

// Set exception handler
set_exception_handler("exceptionHandler");

// Define environment
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'development'); // Temporarily changed to 'development' to show error messages for debugging
}