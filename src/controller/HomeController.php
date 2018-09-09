<?php
/**
* Home Controller
*
* @copyright   Copyright (c) 2018 Gert-Jan Wille (http://www.gert-janwille.com)
* @version     v1.0.0
* @author      Gert-Jan Wille <hello@gert-janwille.be>
*
*/

require_once __DIR__ . '/Controller.php';

class HomeController extends Controller {

  public function index() {
    global $db;

    $this->set('title', 'Home');
    $this->set('content', array_column($db->get('content'), 'text', 'name'));
  }

}
