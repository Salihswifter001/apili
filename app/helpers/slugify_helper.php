<?php
/**
 * URL Slugify Helper
 * Create URL-friendly versions of strings for filenames, URLs, etc.
 */

/**
 * Convert a string to a URL-friendly slug
 * Removes special characters, converts spaces to hyphens, and ensures lowercase
 * 
 * @param string $text The text to convert to a slug
 * @return string URL-friendly slug
 */
function slugify($text) {
    // Replace non letter or digit characters with a hyphen
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    
    // Transliterate
    if (function_exists('transliterator_transliterate')) {
        // Use intl extension if available
        $text = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $text);
    } else {
        // Fallback for servers without intl extension
        $text = iconv('utf-8', 'us-ascii//TRANSLIT//IGNORE', $text);
    }
    
    // Remove anything that's not a letter, number, or hyphen
    $text = preg_replace('~[^-\w]+~', '', $text);
    
    // Trim hyphens from beginning and end
    $text = trim($text, '-');
    
    // Replace consecutive hyphens with a single hyphen
    $text = preg_replace('~-+~', '-', $text);
    
    // Convert to lowercase
    $text = strtolower($text);
    
    // If empty, return a default
    if (empty($text)) {
        return 'n-a';
    }
    
    return $text;
}