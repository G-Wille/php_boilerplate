<?php

/**
* CMS Content Controller
*
* @copyright   Copyright (c) 2018 Gert-Jan Wille (http://www.gert-janwille.com)
* @version     v1.0.0
* @author      Gert-Jan Wille <hello@gert-janwille.be>
*
*/

require_once __DIR__ . '/CMSController.php';

class ContentController extends CMSController {

  public function index() {
    global $db;
    $this->set('title', 'Content');
    $this->set('content', $db->get('content'));
  }

  public function detail() {
    global $db;

    if ($this->action === 'update') $this->handleUpdateText($_POST);

    if (empty($_GET['id'])) $this->redirect('/cms?content=content');

    $db->where("id", $_GET['id']);
    $detail = $db->getOne ("content");

    $this->set('title', 'Detail - ' . $detail['name']);
    $this->set('detail', $detail);
  }

  private function handleUpdateText($data) {
    global $db;

    $errors = $this->validateData($data, ['text']);
    if (!empty($errors)) return $_SESSION['errors'] = $errors;

    $db->where ('id', $data['id']);
    $db->update ('content', [
      'text' => $data['text']
    ]);

    $this->redirect('/cms?content=content');
  }

}
