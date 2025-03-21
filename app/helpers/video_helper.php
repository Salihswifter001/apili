<?php
/**
 * Video Helper Functions
 * Provides functions for video optimization and delivery
 */

/**
 * Get an optimized version of a video URL
 * Adds query parameters for responsive video delivery
 * 
 * @param string $videoPath Path to the video file
 * @param int $width Desired width for video (default 480)
 * @return string Modified video URL with optimization parameters
 */
function get_optimized_video_url($videoPath, $width = 480) {
    $fullUrl = URL_ROOT . '/' . ltrim($videoPath, '/');
    
    // Add timestamp for cache busting during development
    // In production, you should remove this or implement proper cache control
    $timestamp = defined('ENVIRONMENT') && ENVIRONMENT === 'development' ? '&t=' . time() : '';
    
    // Add query parameters that can be used for video optimization
    return $fullUrl . '?width=' . $width . $timestamp;
}

/**
 * Generate responsive video source elements for different viewport sizes
 * 
 * @param string $videoPath Base path to the video file
 * @return string HTML for responsive video sources
 */
function responsive_video_sources($videoPath) {
    $output = '';
    
    // Small screen version
    $output .= '<source media="(max-width: 480px)" src="' . 
               get_optimized_video_url($videoPath, 320) . 
               '" type="video/mp4">';
    
    // Medium screen version
    $output .= '<source media="(max-width: 768px)" src="' . 
               get_optimized_video_url($videoPath, 480) . 
               '" type="video/mp4">';
    
    // Default/large screen version
    $output .= '<source src="' . 
               get_optimized_video_url($videoPath, 720) . 
               '" type="video/mp4">';
    
    return $output;
}