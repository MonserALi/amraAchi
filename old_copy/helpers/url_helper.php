<?php
// URL Helper Functions

// Base URL
function base_url($path = '')
{
  return BASE_URL . ltrim($path, '/');
}

// Current URL
function current_url()
{
  $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
  return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

// Redirect to a URL
function redirect($path = '')
{
  $url = base_url($path);
  header("Location: $url");
  exit;
}

// Redirect back to previous page
function redirect_back()
{
  if (isset($_SERVER['HTTP_REFERER'])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
  } else {
    redirect();
  }
  exit;
}

// Generate a URL for a route
function route($path, $params = [])
{
  $url = base_url($path);

  if (!empty($params)) {
    $url .= '?' . http_build_query($params);
  }

  return $url;
}

// Generate a secure URL with CSRF token
function secure_url($path, $params = [])
{
  $params['csrf_token'] = csrf_token();
  return route($path, $params);
}

// Generate a URL for an asset
function asset($path)
{
  return base_url('public/' . ltrim($path, '/'));
}

// Generate a URL for an image
function image_url($path)
{
  return asset('images/' . ltrim($path, '/'));
}

// Generate a URL for a CSS file
function css_url($path)
{
  return asset('css/' . ltrim($path, '/'));
}

// Generate a URL for a JS file
function js_url($path)
{
  return asset('js/' . ltrim($path, '/'));
}

// Generate a URL for a file upload
function upload_url($path)
{
  return asset('uploads/' . ltrim($path, '/'));
}

// Check if current URL matches a path
function is_current_path($path)
{
  $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $basePath = parse_url(base_url($path), PHP_URL_PATH);

  return $currentPath === $basePath;
}

// Check if current URL starts with a path
function is_current_path_startswith($path)
{
  $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $basePath = parse_url(base_url($path), PHP_URL_PATH);

  return strpos($currentPath, $basePath) === 0;
}

// Get previous URL
function previous_url()
{
  return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url();
}

// Generate a pagination URL
function pagination_url($page, $params = [])
{
  $currentParams = $_GET;
  unset($currentParams['url']);

  $params = array_merge($currentParams, $params);
  $params['page'] = $page;

  return current_url() . '?' . http_build_query($params);
}

// Sanitize URL
function sanitize_url($url)
{
  return filter_var($url, FILTER_SANITIZE_URL);
}

// Validate URL
function is_valid_url($url)
{
  return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

// Get URL parameters as an array
function get_url_params()
{
  return $_GET;
}

// Get a specific URL parameter
function get_url_param($key, $default = null)
{
  return isset($_GET[$key]) ? $_GET[$key] : $default;
}

// Add a parameter to the current URL
function add_url_param($key, $value)
{
  $params = get_url_params();
  $params[$key] = $value;

  return current_url() . '?' . http_build_query($params);
}

// Remove a parameter from the current URL
function remove_url_param($key)
{
  $params = get_url_params();
  unset($params[$key]);

  $queryString = empty($params) ? '' : '?' . http_build_query($params);

  return current_url() . $queryString;
}
