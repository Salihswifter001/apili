<?php
/**
 * Audio Helper Functions
 * Utilities for handling audio file operations
 */

/**
 * Get safe audio file path - with fallbacks for missing files
 *
 * @param string $audioUrl URL or path to audio file
 * @return string|null Full path to audio file or null if not found
 */
function getSafeAudioPath($audioUrl) {
    // Debug the audio URL
    debug_log("Looking for audio file", $audioUrl);
    
    // Initialize paths
    $documentRoot = $_SERVER['DOCUMENT_ROOT'];
    $appRoot = dirname(dirname(__DIR__)); // Path to the application root
    
    // Possible file paths to check
    $pathsToCheck = [];
    
    // Absolute URL case
    if (filter_var($audioUrl, FILTER_VALIDATE_URL)) {
        $filePath = parse_url($audioUrl, PHP_URL_PATH);
        $filePath = ltrim($filePath, '/');
        $pathsToCheck[] = $documentRoot . '/' . $filePath;
    } 
    
    // Relative URL/path cases
    $relativePath = ltrim($audioUrl, '/');
    $pathsToCheck[] = $documentRoot . '/' . $relativePath;
    $pathsToCheck[] = $appRoot . '/public/' . $relativePath;
    
    // For URLs that start with URL_ROOT
    if (strpos($audioUrl, URL_ROOT) === 0) {
        $withoutUrlRoot = str_replace(URL_ROOT, '', $audioUrl);
        $withoutUrlRoot = ltrim($withoutUrlRoot, '/');
        $pathsToCheck[] = $documentRoot . '/' . $withoutUrlRoot;
        $pathsToCheck[] = $appRoot . '/public/' . $withoutUrlRoot;
    }
    
    // Check for sample files if they exist
    for ($i = 1; $i <= 5; $i++) {
        $pathsToCheck[] = $appRoot . '/public/audio/demo/sample_' . $i . '.mp3';
    }
    
    // Try all paths
    foreach ($pathsToCheck as $path) {
        debug_log("Checking path", $path);
        if (file_exists($path)) {
            debug_log("Found audio file at", $path);
            return $path;
        }
    }
    
    // Create a placeholder audio file if all else fails
    $placeholderPath = $appRoot . '/public/audio/placeholder.mp3';
    if (!file_exists($placeholderPath)) {
        generatePlaceholderAudio($placeholderPath);
    }
    
    return file_exists($placeholderPath) ? $placeholderPath : null;
}

/**
 * Generate a placeholder mp3 file
 * 
 * This is a fallback for when real audio files don't exist
 * It creates a basic silent audio file
 *
 * @param string $outputPath Path to save the placeholder file
 * @return bool Success status
 */
function generatePlaceholderAudio($outputPath) {
    // Ensure the directory exists
    $dir = dirname($outputPath);
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0755, true)) {
            error_log("Failed to create directory for placeholder audio: $dir");
            return false;
        }
    }
    
    // Create minimal MP3 file (essentially silence)
    // This is a minimal MP3 header with padding to create a silent file
    $silenceData = base64_decode(
        "//uQxAAAAAAAAAAAAAAAAAAAAAAASW5mbwAAAA8AAAABAAADQgD////////////////////////////////////" .
        "//////////////////////////////////////////////////8AAAA5TEFNRTMuOTlyAc0AAAAAAAAAABSAJAJAQgAAgAAAA0L2S" .
        "LQzAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
        "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
        "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA"
    );
    
    try {
        if (!file_put_contents($outputPath, $silenceData)) {
            error_log("Failed to write placeholder audio file: $outputPath");
            return false;
        }
        debug_log("Created placeholder audio file at", $outputPath);
        return true;
    } catch (Exception $e) {
        error_log("Exception creating placeholder audio: " . $e->getMessage());
        return false;
    }
}