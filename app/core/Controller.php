<?php
/**
 * Base Controller
 * Loads models and views
 */
class Controller {
    // Load model
    public function model($model) {
        // Require model file
        require_once '../app/models/' . $model . '.php';

        // Instantiate model
        return new $model();
    }

    // Load view
    public function view($view, $data = []) {
        // Check for view file
        if (file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        } else {
            // View does not exist
            die('View does not exist');
        }
    }

    // Redirect to another page
    public function redirect($url) {
        // Handle empty URL_ROOT correctly to avoid double slashes
        if (empty(URL_ROOT)) {
            header('location: /' . $url);
        } else {
            header('location: ' . URL_ROOT . '/' . $url);
        }
        exit;
    }

    // Check if request is POST
    public function isPost() {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    // Get POST data
    public function getPostData() {
        return $_POST;
    }

    // Get sanitized POST data
    public function getSanitizedPostData() {
        $data = [];
        foreach ($_POST as $key => $value) {
            $data[$key] = htmlspecialchars(trim($value));
        }
        return $data;
    }
}