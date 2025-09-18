<?php
class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();
        
        // Check if controller exists
        if ($url && isset($url[0]) && file_exists(__DIR__ . '/../app/controllers/' . $url[0] . 'Controller.php')) {
            $this->controller = $url[0] . 'Controller';
            unset($url[0]);
        }
        
        // Check if the file exists before requiring it
        $controllerPath = __DIR__ . '/../app/controllers/' . $this->controller . '.php';
        if (file_exists($controllerPath)) {
            require_once $controllerPath;
            $this->controller = new $this->controller;
            
            // Check if method exists
            if (isset($url[1]) && method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
            
            // Get parameters
            $this->params = $url ? array_values($url) : [];
            
            // Call the method with parameters
            call_user_func_array([$this->controller, $this->method], $this->params);
        } else {
            // If controller doesn't exist, show 404 error
            $this->error404();
        }
    }
    
    protected function parseUrl() {
        if (isset($_GET['url'])) {
            $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
            return $url;
        }
        return null;
    }
    
    protected function error404() {
        // Check if ErrorController exists before requiring it
        $errorControllerPath = __DIR__ . '/../app/controllers/ErrorController.php';
        if (file_exists($errorControllerPath)) {
            require_once $errorControllerPath;
            $controller = new ErrorController();
            $controller->notFound();
        } else {
            // If ErrorController doesn't exist, display a simple 404 message
            header("HTTP/1.0 404 Not Found");
            echo '<h1>404 - Page Not Found</h1>';
            echo '<p>The page you are looking for could not be found.</p>';
            echo '<p><a href="' . BASE_URL . '">Go to Homepage</a></p>';
            exit;
        }
    }
}