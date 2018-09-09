<?php

/**
* Analytics Class
*
* @copyright   Copyright (c) 2018 Gert-Jan Wille (http://www.gert-janwille.com)
* @version     v1.0.0
* @author      Gert-Jan Wille <hello@gert-janwille.be>
*
*/


class Analytics {

  function __construct() {
    (empty($_COOKIE['analytics'])) ? $this->createcookie() : $this->getcookie($_COOKIE['analytics']);
  }

  private function createcookie() {
    global $db;

    $db->where("visitor", $_SERVER['REMOTE_ADDR']);
    if ($db->getOne("analytics")) return $this->getcookie($_SERVER['REMOTE_ADDR']);

    $db->insert ('analytics', [
      'visitor' => $_SERVER['REMOTE_ADDR'],
      'user_agent' => $_SERVER['HTTP_USER_AGENT']
    ]);

    setcookie('analytics', $_SERVER['REMOTE_ADDR'], time() + (86400 * 30), "/") ? $this->track() : null;
    $_SESSION['analytics'] = true;
  }

  private function getcookie($cookie) {
    global $db;

    if (isset($_SESSION['analytics'])) return $this->track();

    $db->where ("visitor", $cookie);
    $db->update ('analytics', [
      'visits' => $db->inc(1),
    ]);

    $_SESSION['analytics'] = $cookie;
    $this->track();
  }

  private function track() {
    if (!isset($_SESSION['previous_location'])) $_SESSION['previous_location'] = null;

    // Tracking Code...
  }

  public function detectChange($route) {
    global $db;

    if ($_SESSION['previous_location'] !== $route['action']) {
      $db->where ("visitor", $_SESSION['analytics']);
      $db->update ('analytics', [
        'page_clicks' => $db->inc(1),
      ]);
    }

    $_SESSION['previous_location'] = $route['action'];
  }

  private static function getUniqueVisitors() {
    global $db;
    return $db->getValue ("analytics", "count(*)");
  }

  private static function getTotalVisits() {
    global $db;
    return $db->getValue ("analytics", "sum(visits)");
  }

  private static function getAvgClicks() {
    global $db;
    $avg = $db->query("SELECT AVG(visits) as avg FROM analytics")[0];
    return round(floatval($avg['avg']), 2);
  }

  private static function getResults() {
    global $db;
    $thisweek = $db->query("select created from analytics where created between date_sub(now(),INTERVAL 1 WEEK) and now();");
    $lastweek = $db->query("SELECT created FROM analytics WHERE created >= curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY AND created < curdate() - INTERVAL DAYOFWEEK(curdate())-1 DAY");

    return [
      "this_week" => $thisweek,
      "last_week" => $lastweek
    ];
  }

  private static function getVisitorsThisWeek() {
    $stats = [100, 100, 100, 100, 100, 100, 100];
    $week = self::getResults();

    foreach ($week['this_week'] as $key => $value) $stats = self::generateStats($value['created'], $stats);

    return [
      "total" => count($week['this_week']),
      "chart" => $stats,
      'percentage' => self::getPercentageChange(count($week['this_week']), count($week['last_week']))
    ];
  }

  private static function getVisitorsLastWeek() {
    $stats = [100, 100, 100, 100, 100, 100, 100];
    $week = self::getResults();

    foreach ($week['last_week'] as $key => $value) $stats = self::generateStats($value['created'], $stats);

    return [
      "total" => count($week['last_week']),
      "chart" => $stats,
      'percentage' => self::getPercentageChange(count($week['last_week']), count($week['this_week']))
    ];
  }

  private static function getVisitorsThisYear() {
    global $db;
    $stats = [100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100];
    $year = $db->query('SELECT * FROM analytics WHERE YEAR(created) = YEAR(CURDATE()) OR YEAR(created) = YEAR(CURDATE()) + 1');

    foreach ($year as $key => $value) $stats = self::generateStats($value['created'], $stats, 'year');

    return [
      "total" => count($year),
      "chart" => $stats,
    ];
  }

  public static function getStats() {
    return[
      "total_unique_visitors" => self::getUniqueVisitors(),
      "total_visits" => self::getTotalVisits(),
      "average_clicks" => self::getAvgClicks(),
      "visitors_this_week" => self::getVisitorsThisWeek(),
      "visitors_last_week" => self::getVisitorsLastWeek(),
      "visitors_this_year" => self::getVisitorsThisYear()
    ];
  }

  private static function getPercentageChange($oldNumber, $newNumber) {
    $decreaseValue = $oldNumber - $newNumber;
    if ($oldNumber === 0) return 0;
    return round(($decreaseValue / $oldNumber) * 100, 2);
  }

  private static function generateStats($v, $stats, $t = 'day') {
    $now = new DateTime();
    $date = new DateTime($v);

    if ($t === 'day') {
      switch ($date->format('D')) {
        case $now->modify('-0 day')->format('D'):
          $stats[6] -= 10;
          break;
        case $now->modify('-1 day')->format('D'):
          $stats[5] -= 10;
          break;
        case $now->modify('-2 day')->format('D'):
          $stats[4] -= 10;
          break;
        case $now->modify('-3 day')->format('D'):
          $stats[3] -= 10;
          break;
        case $now->modify('-4 day')->format('D'):
          $stats[2] -= 10;
          break;
        case $now->modify('-5 day')->format('D'):
          $stats[1] -= 10;
          break;
        case $now->modify('-6 day')->format('D'):
          $stats[0] -= 10;
          break;
      }
    }else {
      switch ($date->format('M')) {
        case $now->modify('-0 month')->format('M'):
          $stats[11] -= 10;
          break;
        case $now->modify('-1 month')->format('M'):
          $stats[10] -= 10;
          break;
        case $now->modify('-2 month')->format('M'):
          $stats[9] -= 10;
          break;
        case $now->modify('-3 month')->format('M'):
          $stats[8] -= 10;
          break;
        case $now->modify('-4 month')->format('M'):
          $stats[7] -= 10;
          break;
        case $now->modify('-5 month')->format('M'):
          $stats[6] -= 10;
          break;
        case $now->modify('-6 month')->format('M'):
          $stats[5] -= 10;
          break;
        case $now->modify('-7 month')->format('M'):
          $stats[4] -= 10;
          break;
        case $now->modify('-8 month')->format('M'):
          $stats[3] -= 10;
          break;
        case $now->modify('-9 month')->format('M'):
          $stats[2] -= 10;
          break;
        case $now->modify('-10 month')->format('M'):
          $stats[1] -= 10;
          break;
        case $now->modify('-11 month')->format('M'):
          $stats[0] -= 10;
          break;
      }
    }

    return $stats;
  }
}
