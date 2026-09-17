<?php
  $visitCounter = 0;
  if (isset($_COOKIE['visitCounter'])) {
    $visitCounter = (int) $_COOKIE['visitCounter'];
  }
  $visitCounter++;

  $lastVisit = '';
  if (isset($_COOKIE['lastVisit'])) {
    $lastVisit = date('d-m-Y H:i:s', (int) $_COOKIE['lastVisit']);
  }

  // Устанавливаем куки только один раз в день
  if (!isset($_COOKIE['lastVisit']) || date('d-m-Y', $_COOKIE['lastVisit']) != date('d-m-Y')) {
    setcookie('visitCounter', $visitCounter, time() + 60 * 60 * 24 * 365);
    setcookie('lastVisit', time(), time() + 60 * 60 * 24 * 365);
  }
