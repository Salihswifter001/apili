<?php
/**
 * Main App Class
 * Creates URL & loads core controller
 * URL FORMAT - /controller/method/params
 */
class App {
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct() {
        try {
            $url = $this->getUrl();
            debug_log("URL parts", $url);

            // Look in controllers for first value
            if (isset($url[0]) && !empty($url[0])) {
                $controllerFile = '../app/controllers/' . ucwords($url[0]) . '.php';
                
                if (file_exists($controllerFile)) {
                    // If exists, set as controller
                    $this->currentController = ucwords($url[0]);
                    // Unset 0 Index
                    unset($url[0]);
                    debug_log("Controller found", $this->currentController);
                } else {
                    debug_log("Controller file not found", $controllerFile);
                }
            }

            // Require the controller
            $controllerPath = '../app/controllers/' . $this->currentController . '.php';
            if (!file_exists($controllerPath)) {
                throw new Exception("Controller file not found: " . $controllerPath);
            }
            
            require_once $controllerPath;

            // Check if controller class exists
            if (!class_exists($this->currentController)) {
                throw new Exception("Controller class not found: " . $this->currentController);
            }

            // Instantiate controller class
            $this->currentController = new $this->currentController;

            // Check for second part of url
            if (isset($url[1]) && !empty($url[1])) {
                // Check if method exists in controller
                if (method_exists($this->currentController, $url[1])) {
                    $this->currentMethod = $url[1];
                    // Unset 1 index
                    unset($url[1]);
                    debug_log("Method found", $this->currentMethod);
                } else {
                    debug_log("Method not found", $url[1]);
                    // Method not found - fall back to index
                    $this->currentMethod = 'index';
                }
            }

            // Validate that the method exists
            if (!method_exists($this->currentController, $this->currentMethod)) {
                throw new Exception("Method not found in controller: " . $this->currentMethod);
            }

            // Get params
            $this->params = $url ? array_values($url) : [];
            debug_log("Parameters", $this->params);

            // Call a callback with array of params
            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
            
        } catch (Exception $e) {
            error_log("Routing error: " . $e->getMessage());
            
            // In development, show error details
            if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
                echo '<div style="background: #f8d7da; color: #721c24; padding: 10px; margin: 10px; border-radius: 5px; border: 1px solid #f5c6cb;">
                    <h3>Routing Error</h3>
                    <p>' . htmlspecialchars($e->getMessage()) . '</p>
                    <p>File: ' . htmlspecialchars($e->getFile()) . ' on line ' . $e->getLine() . '</p>
                    <h4>Stack Trace:</h4>
                    <pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>
                </div>';
            } else {
                // In production, redirect to 404 page
                header('Location: ' . URL_ROOT . '/pages/error404');
                exit;
            }
        }
    }

    protected function getUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        
        return [];
    }
}