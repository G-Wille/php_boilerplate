<?php
/**
* Main Entrence File
*
* @copyright   Copyright (c) 2018 Gert-Jan Wille (http://www.gert-janwille.com)
* @version     v1.0.0
* @author      Gert-Jan Wille <hello@gert-janwille.be>
*
*/

ini_set('display_errors', true);
error_reporting(E_ALL);
session_start();

define('DS', DIRECTORY_SEPARATOR);
define('WWW_ROOT', __DIR__ . DS);

require  WWW_ROOT . '/libs/' . DS . 'autoload.php';

$dotenv = new Dotenv\Dotenv(WWW_ROOT);
$dotenv->load();

global $db;

// Require all files.
foreach(glob(WWW_ROOT . '/libs/*.php') as $f) require_once($f);
foreach(glob(WWW_ROOT . '/class/*.php') as $f) require_once($f);
_require_all(WWW_ROOT . '/modules/', 1);

// Connect to DB;
$db = new MysqliDb ($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

// Start analytics
$analytics = new Analytics();

if(empty($_GET['page'])) $_GET['page'] = 'home';

// Check if cms is available.
if($_GET['page'] === 'cms') {
  $path = WWW_ROOT . 'gem_cms' . DS . "index.php";
  if (!file_exists($path)) header('Location: index.php');

  require_once $path;
  $CMS = new GEM();
  exit();
}

if(empty($routes[$_GET['page']])) {
  header('Location: /');
  exit();
}

$route = $routes[$_GET['page']];
$controllerName = $route['controller'] . 'Controller';

require_once WWW_ROOT . 'controller' . DS . $controllerName . ".php";

$controllerObj = new $controllerName();
$controllerObj->route = $route;
$controllerObj->filter();

$analytics->detectChange($route);

if (!$route['json']) $controllerObj->render();

function _require_all($dir, $depth=0) {
  if ($depth > 5) return;

  $scan = glob("$dir/*");
  foreach ($scan as $path) {
    if (preg_match('/\.php$/', $path)) {
        require_once $path;
    } elseif (is_dir($path)) {
        _require_all($path, $depth+1);
      }
    }
}
