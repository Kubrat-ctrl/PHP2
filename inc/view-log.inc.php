<?php
  $logFile = 'log/' . PATH_LOG;
  if (file_exists($logFile)) {
    $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    echo '<ul>';
    foreach ($lines as $line) {
      [$dt, $page, $ref] = explode('|', $line);
      echo '<li>', date('d-m-Y H:i:s', (int) $dt), ' - ', htmlspecialchars($page), ' -> ', htmlspecialchars($ref), '</li>';
    }
    echo '</ul>';
  } else {
    echo '<p>Журнал посещений пока пуст.</p>';
  }
