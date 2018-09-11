<?php

/**
* Base Controller
*
* @copyright   Copyright (c) 2018 Gert-Jan Wille (http://www.gert-janwille.com)
* @version     v1.0.0
* @author      Gert-Jan Wille <hello@gert-janwille.be>
*
*/

class Controller {

  public $route , $action, $getAction;
  protected $viewVars = array();
  protected $isAjax = false;
  protected $env = 'development';

  public function filter() {
    $this->action = (isset($_POST['action'])) ? strtolower($_POST['action']) : null;
    $this->getAction = (isset($_GET['action'])) ? strtolower($_GET['action']) : null;

    if(basename(dirname(dirname(__FILE__))) != 'src') $this->env = 'production';
    if(!empty($_SERVER['HTTP_ACCEPT']) && strtolower($_SERVER['HTTP_ACCEPT']) == 'application/json') $this->isAjax = true;

    $this->set('css', '<link rel="stylesheet" href="http://localhost:3000/css/style.css">');
    $this->set('js', '<script src="http://localhost:3000/js/script.js"></script>');

    if($this->env == 'production') {
      $this->set('css', '<link rel="stylesheet" href="css/style.css">');
      $this->set('js', '<script src="js/main.js"></script>');
    }

    call_user_func(array($this, $this->route['action']));
  }

  public function render() {
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

  private function createViewVarWithContent() {
    extract($this->viewVars, EXTR_OVERWRITE);
    ob_start();
    require WWW_ROOT . 'view' . DS . strtolower($this->route['controller']) . DS . $this->route['action'] . '.php';
    $content = ob_get_clean();
    $this->set('content', $content);
  }

  private function renderInLayout() {
    extract($this->viewVars, EXTR_OVERWRITE);
    include WWW_ROOT . 'view' . DS . 'layout.php';
  }

  private function cleanupSessionMessages() {
    unset($_SESSION['info']);
    unset($_SESSION['error']);
  }
}
