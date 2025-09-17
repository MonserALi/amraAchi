<?php
class Controller
{
  // Load a view with data
  public function view($view, $data = [])
  {
    // Extract data to make variables available in the view
    extract($data);

    // Check if the view file exists
    $viewPath = __DIR__ . '/../app/views/' . $view . '.php';
    if (file_exists($viewPath)) {
      require_once $viewPath;
    } else {
      // If view doesn't exist, show an error
      echo "View not found: " . $view;
    }
  }

  // Load a model
  public function model($model)
  {
    // Check if the model file exists
    $modelPath = __DIR__ . '/../app/models/' . $model . '.php';
    if (file_exists($modelPath)) {
      require_once $modelPath;
      return new $model();
    } else {
      // If model doesn't exist, show an error
      echo "Model not found: " . $model;
      return false;
    }
  }

  // Redirect to a URL
  public function redirect($url)
  {
    header('Location: ' . BASE_URL . $url);
    exit;
  }

  // Check if the request is a POST request
  public function isPost()
  {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
  }

  // Check if the request is a GET request
  public function isGet()
  {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
  }

  // Get POST data
  public function getPost($key = null)
  {
    if ($key === null) {
      return $_POST;
    }
    return isset($_POST[$key]) ? $_POST[$key] : null;
  }

  // Get GET data
  public function getGet($key = null)
  {
    if ($key === null) {
      return $_GET;
    }
    return isset($_GET[$key]) ? $_GET[$key] : null;
  }

  // Set session data
  public function setSession($key, $value)
  {
    $_SESSION[$key] = $value;
  }

  // Get session data
  public function getSession($key)
  {
    return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
  }

  // Unset session data
  public function unsetSession($key)
  {
    if (isset($_SESSION[$key])) {
      unset($_SESSION[$key]);
    }
  }

  // Check if user is logged in
  public function isLoggedIn()
  {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
  }

  // Get current user ID
  public function getUserId()
  {
    return $this->isLoggedIn() ? $_SESSION['user_id'] : null;
  }

  // Get current user role
  public function getUserRole()
  {
    return $this->isLoggedIn() ? $_SESSION['user_role'] : null;
  }

  // Require user to be logged in
  public function requireLogin()
  {
    if (!$this->isLoggedIn()) {
      $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
      $this->redirect('login');
    }
  }

  // Require user to have a specific role
  public function requireRole($role)
  {
    $this->requireLogin();

    if ($this->getUserRole() !== $role) {
      $this->redirect('dashboard');
    }
  }

  // Send JSON response
  public function json($data, $statusCode = 200)
  {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
  }
}
