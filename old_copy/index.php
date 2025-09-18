<?php
// Require configuration files
require_once 'config/app.php';
require_once 'config/database.php';
require_once 'config/routes.php';

// Require core files
require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';
require_once 'core/Database.php';
require_once 'core/Session.php';
require_once 'core/Auth.php';

// Require helper files
require_once 'helpers/auth_helper.php';
require_once 'helpers/url_helper.php';
require_once 'helpers/form_helper.php';
require_once 'helpers/date_helper.php';

// Initialize the application
$app = new App();
