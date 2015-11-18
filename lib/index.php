<?php
define('PROJECT_NAME', 'pegasus_lib');
define('BASE_PATH', dirname(__FILE__));
define('DEBUG', false);
define('VERSION', '1.0.0');
define('DISABLE_PROPEL', true);

// Include base framework
require_once('/var/www/pegasus/pegasus.php');

error_reporting(E_ALL);

Request::create();

View::create();
View::template_dir(BASE_PATH . '/templates/');
View::compile_dir(BASE_PATH . '/templates/');
View::assign('content', 'Pegasus Error: no lib execution defined');

if (file_exists('controllers/' . Request::module() . '.php')) {
    include('controllers/' . Request::module() . '.php');
    $controller = new SiteController();
    $controller->process();
    View::showContainer();
} else {
    Pegasus::error('Bad Function', 'Pegasus runtime library called with invalid procedure request!');
}

