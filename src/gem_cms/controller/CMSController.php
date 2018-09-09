<?php

/**
* CMS Base Controller
*
* @copyright   Copyright (c) 2018 Gert-Jan Wille (http://www.gert-janwille.com)
* @version     v1.0.0
* @author      Gert-Jan Wille <hello@gert-janwille.be>
*
*/

class CMSController {

  public $route, $secret, $action;
  protected $viewVars = array();
  protected $env = 'development';

  public function filter() {
    if(basename(WWW_ROOT) != 'src') $this->env = 'production';

    $this->set('css', '<link rel="stylesheet" href="http://localhost:3000/css/style.css">');
    $this->set('js', '<script src="http://localhost:3000/js/script.js"></script>');

    if($this->env == 'production') {
      $this->set('css', '<link rel="stylesheet" href="/css/style.css">');
      $this->set('js', '<script src="/js/main.js"></script>');
    }

  }

  public function render() {
    $this->secret = '!{6pNdD+#<Tz{d/';
    $this->action = (isset($_POST['action'])) ? strtolower($_POST['action']) : null;
    $this->getAction = (isset($_GET['action'])) ? strtolower($_GET['action']) : null;

    call_user_func(array($this, $this->route['action']));

    $this->renderNavigationItems();
    $this->createViewVarWithContent();
    $this->renderInLayout();
    $this->cleanupSessionMessages();
  }

  public function set($variableName, $value) {
    $this->viewVars[$variableName] = $value;
  }

  public function redirect($url) {
    header("Location: {$url}");
    exit();
  }

  public function validateData($data, $keys) {
    $errors = array();
    foreach ($keys as $key => $value) {
      if (empty($data[$value])) $errors[$value] = 'Pleas add a valid ' . $value;
    }
    return $errors;
  }


  private function createViewVarWithContent() {
    extract($this->viewVars, EXTR_OVERWRITE);
    ob_start();
    require WWW_ROOT . 'gem_cms' . DS . 'view' . DS . strtolower($this->route['controller']) . DS . $this->route['action'] . '.php';
    $content = ob_get_clean();
    $this->set('content', $content);
  }

  private function renderInLayout() {
    extract($this->viewVars, EXTR_OVERWRITE);
    include WWW_ROOT . 'gem_cms' . DS . 'view' . DS . 'layout.php';
  }

  private function cleanupSessionMessages() {
    unset($_SESSION['info']);
    unset($_SESSION['errors']);
  }

  private function renderNavigationItems() {
    global $menu;

    $items = array();
    foreach ($menu as $key => $value) {
      if (isset($value['hidden'])) {
        if ($value['hidden'] !== true) array_push($items, $key);
      } else {
        array_push($items, $key);
      }
    }

    $this->set('navItems', $items);
  }
}
