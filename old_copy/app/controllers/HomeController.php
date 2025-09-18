<?php
class HomeController extends Controller
{
  public function index()
  {
    // Set page title
    $data['pageTitle'] = 'Niramoy Health - Digital Healthcare Platform';
    $data['currentPage'] = 'home';

    // Load the home view
    $this->view('home/index', $data);
  }

  public function about()
  {
    // Set page title
    $data['pageTitle'] = 'About Us - Niramoy Health';
    $data['currentPage'] = 'about';

    // Load the about view
    $this->view('home/about', $data);
  }

  public function contact()
  {
    // Set page title
    $data['pageTitle'] = 'Contact Us - Niramoy Health';
    $data['currentPage'] = 'contact';

    // Load the contact view
    $this->view('home/contact', $data);
  }
}
