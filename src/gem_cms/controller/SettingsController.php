<?php

/**
* CMS Settings Controller
*
* @copyright   Copyright (c) 2018 Gert-Jan Wille (http://www.gert-janwille.com)
* @version     v1.0.0
* @author      Gert-Jan Wille <hello@gert-janwille.be>
*
*/

require_once __DIR__ . '/CMSController.php';

class SettingsController extends CMSController {

  public function index() {
    global $db;
    $this->set('title', 'Settings');
    $this->set('settings', $db->get('settings'));

    if ($this->action === 'add key') $this->handleAddNewKey($_POST);
    if ($this->getAction === 'delete') $this->handleRemoveKey($_GET);
    if ($this->action === 'change') $this->handleChangeValue($_POST);
  }

  private function handleAddNewKey($data) {
    global $db;

    $errors = $this->validateData($data, ['name', 'value']);
    if (!empty($errors)) return $_SESSION['errors'] = $errors;

    $db->insert ('settings', [
      'name' => $data['name'],
      'value' => $data['value']
    ]);

    $this->redirect('/cms?content=settings');
  }

  private function handleRemoveKey($data) {
    global $db;

    $errors = $this->validateData($data, ['id']);
    if (!empty($errors)) return $_SESSION['errors'] = $errors;

    $db->where('id', $data['id']);
    $db->delete('settings');
    $this->redirect('/cms?content=settings');
  }

  private function handleChangeValue($data) {
    global $db;

    $errors = $this->validateData($data, ['id']);
    if (!empty($errors)) return $_SESSION['errors'] = $errors;

    $db->where ('id', $data['id']);
    $db->update ('settings', [
      'value' => $data['value']
    ]);

    $this->redirect('/cms?content=settings');
  }
}
