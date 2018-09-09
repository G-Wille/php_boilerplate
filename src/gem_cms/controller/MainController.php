<?php

/**
* CMS Main Controller
*
* @copyright   Copyright (c) 2018 Gert-Jan Wille (http://www.gert-janwille.com)
* @version     v1.0.0
* @author      Gert-Jan Wille <hello@gert-janwille.be>
*
*/

require_once __DIR__ . '/CMSController.php';

class MainController extends CMSController {

  public function index() {
    $this->set('title', 'Dashboard');
    $this->set('stats', Analytics::getStats());
  }

  public function login() {
    $this->set('title', (!isset($_GET['forgotpassword'])) ? 'Login' : 'Request new password');

    if (!empty($_SESSION['user'])) $this->redirect('/cms');
    if ($this->action !== 'login' && $this->action !== 'request new password') return;


    $errors = $this->validateData($_POST, isset($_POST['forgotpassword']) ? ['username'] : ['username', 'password']);
    if (!empty($errors)) return $_SESSION['errors'] = $errors;

    global $db;
    $user = $db->where ("username", $_POST['username'])->getOne ("users");


    if (isset($_POST['forgotpassword'])) return $this->sendMail($user['username']);
    if(!password::checkPassword($_POST['password'], $user['password'])) return $_SESSION['error'] = "Wrong combination!";

    $_SESSION['user'] = JWT::create($user, $this->secret);
    $this->redirect('/cms');
  }

  public function logout() {
    $this->set('title', 'Logout');
    unset($_SESSION['user']);
    $this->redirect('/cms');
  }

  public function user() {
    $this->set('title', JWT::content($_SESSION['user'], $this->secret)['username']);
    if ($this->action === 'reset password') return $this->resetPassword($_POST);
  }


  private function sendMail($mail) {
    global $db;

    $newpassword = password::generate();

    $db->where('username', $mail);
    $db->update('users', [
      'password' => password::encodePassword($newpassword)
    ]);

    mailer::sendMail($mail, 'GEM - Reset Your Password', 'Login with this email and following password: <b>'.$newpassword.'</b>');
    $this->redirect('/cms');
  }

  private function resetPassword($data) {
    global $db;

    $db->where('username', JWT::content($_SESSION['user'], $this->secret)['username']);
    $db->update('users', [
      'password' => password::encodePassword($data['password'])
    ]);

    $this->redirect('/cms?content=user');
  }
}
