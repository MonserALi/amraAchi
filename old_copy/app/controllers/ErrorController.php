<?php
class ErrorController extends Controller
{
  public function notFound()
  {
    // Set page title
    $data['pageTitle'] = 'Page Not Found - Niramoy Health';

    // Set HTTP response code to 404
    http_response_code(404);

    // Load the 404 view
    $this->view('errors/404', $data);
  }

  public function serverError()
  {
    // Set page title
    $data['pageTitle'] = 'Server Error - Niramoy Health';

    // Set HTTP response code to 500
    http_response_code(500);

    // Load the 500 view
    $this->view('errors/500', $data);
  }
}
