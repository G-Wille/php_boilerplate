<?php

$menu = array();

$menu['dashboard'] = array(
  'controller' => 'Main',
  'action' => 'index'
);

$menu['content'] = array(
  'controller' => 'Content',
  'action' => 'index'
);

$menu['settings'] = array(
  'controller' => 'Settings',
  'action' => 'index'
);

$menu['logout'] = array(
  'controller' => 'Main',
  'action' => 'logout',
  'render' => false
);



// Invisible Items
$menu['login'] = array(
  'controller' => 'Main',
  'action' => 'login',
  'hidden' => true
);

$menu['user'] = array(
  'controller' => 'Main',
  'action' => 'user',
  'hidden' => true
);

$menu['content-detail'] = array(
  'controller' => 'Content',
  'action' => 'detail',
  'hidden' => true
);
