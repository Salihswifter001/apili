<?php
/**
 * Language Helper
 * Helper functions to handle language and translation
 */

/**
 * Get a translated string based on the user's selected language
 * 
 * @param string $key The language key to retrieve
 * @param string $default Default value if key is not found
 * @return string The translated string
 */
function __(string $key, string $default = ''): string {
    static $language = null;
    static $translations = [];
    
    // Get current language
    $currentLanguage = getCurrentLanguage();
    
    // If language has changed or we haven't loaded the language yet
    if ($language === null || $language !== $currentLanguage) {
        // Update stored language
        $language = $currentLanguage;
        
        // Load the language file
        $langFile = APPROOT . '/languages/' . $language . '.php';
        if (file_exists($langFile)) {
            $translations = require $langFile;
        } else {
            // Fallback to English if language file doesn't exist
            $langFile = APPROOT . '/languages/en.php';
            if (file_exists($langFile)) {
                $translations = require $langFile;
            }
        }
    }
    
    // Return the translation if it exists, otherwise return the key or default
    return $translations[$key] ?? ($default ?: $key);
}

/**
 * Get the current language code
 * 
 * @return string The current language code (e.g., 'en', 'tr')
 */
function getCurrentLanguage(): string {
    if (isset($_SESSION['user_data']) && isset($_SESSION['user_data']->language)) {
        return $_SESSION['user_data']->language;
    } elseif (isset($_SESSION['language'])) {
        return $_SESSION['language'];
    }
    
    return 'en'; // Default to English
}

/**
 * Get array of available languages for the dropdown
 * 
 * @return array Associative array of language codes and names
 */
function getAvailableLanguages(): array {
    return [
        'en' => __('english'),
        'tr' => __('turkish'),
        'es' => __('spanish'),
        'fr' => __('french'),
        'de' => __('german')
    ];
}