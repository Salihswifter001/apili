<?php
/**
 * URL Helper Functions
 */

// Redirect to page
function redirect($page) {
    // Handle empty URL_ROOT properly
    if (empty(URL_ROOT)) {
        header('location: /' . $page);
    } else {
        header('location: ' . URL_ROOT . '/' . $page);
    }
    exit;
}

// Get current URL
function getCurrentUrl() {
    if (empty(URL_ROOT)) {
        return $_SERVER['REQUEST_URI'];
    } else {
        return URL_ROOT . $_SERVER['REQUEST_URI'];
    }
}

// Check if current URL matches specific path
function isActive($path) {
    $currentPath = trim($_SERVER['REQUEST_URI'], '/');
    $targetPath = trim($path, '/');
    return $currentPath === $targetPath ? 'active' : '';
}