<?php

/**
* GEM - Content Management System
*
* @copyright   Copyright (c) 2018 Gert-Jan Wille (http://www.gert-janwille.com)
* @version     v1.0.0
* @author      Gert-Jan Wille <hello@gert-janwille.be>
*
*/

class GEM {

  function __construct() {
    global $menu;

    foreach(glob(WWW_ROOT . 'gem_cms/modules/*.php') as $f) require_once($f);

    if(empty($_GET['content'])) $_GET['content'] = 'dashboard';
    if(empty($menu[$_GET['content']])) {
      header('Location: /cms');
      exit();
    }

    if (empty($_SESSION['user'])) $_GET['content'] = 'login';

    $route = $menu[$_GET['content']];
    $controllerName = $route['controller'] . 'Controller';

    require_once WWW_ROOT .'gem_cms' . DS . 'controller' . DS . $controllerName . ".php";

    $controllerObj = new $controllerName();
    $controllerObj->route = $route;
    $controllerObj->filter();
    $controllerObj->render();
  }

}
