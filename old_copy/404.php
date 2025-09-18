<?php
require_once 'config/app.php';
require_once 'config/database.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';
require_once 'core/Database.php';

// Create a simple error controller
class ErrorController extends Controller
{
  public function notFound()
  {
    // Set HTTP response code to 404
    http_response_code(404);

    // Set page title
    $data['pageTitle'] = 'Page Not Found - Niramoy Health';

    // Load the 404 view
    $this->view('errors/404', $data);
  }

  public function serverError()
  {
    // Set HTTP response code to 500
    http_response_code(500);

    // Set page title
    $data['pageTitle'] = 'Server Error - Niramoy Health';

    // Load the 500 view
    $this->view('errors/500', $data);
  }
}

// Initialize the error controller
$errorController = new ErrorController();
$errorController->notFound();
