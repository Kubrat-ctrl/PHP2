<?php
  /* Основные настройки */
  define('DB_HOST', '127.0.1.31');
  define('DB_LOGIN', 'root');
  define('DB_PASSWORD', '');
  define('DB_NAME', 'gbook');

  $mysqli = new mysqli(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME);
  if ($mysqli->connect_error) {
    die('Ошибка подключения к базе данных: ' . $mysqli->connect_error);
  }
  $mysqli->set_charset('utf8');
  /* Основные настройки */

  /* Сохранение записи в БД */
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = strip_tags(trim($_POST['name'] ?? ''));
    $email = strip_tags(trim($_POST['email'] ?? ''));
    $msg = strip_tags(trim($_POST['msg'] ?? ''));

    if ($name && $email && $msg) {
      $sql = 'INSERT INTO msgs (name, email, msg) VALUES (?, ?, ?)';
      $stmt = $mysqli->prepare($sql);
      if ($stmt) {
        $stmt->bind_param('sss', $name, $email, $msg);
        $stmt->execute();
        $stmt->close();
      } else {
        echo '<p>Ошибка при сохранении записи: ', $mysqli->error, '</p>';
      }
    }
  }
  /* Сохранение записи в БД */

  /* Удаление записи из БД */
  if (isset($_GET['del'])) {
    $del = (int) $_GET['del'];
    if ($del > 0) {
      $sql = 'DELETE FROM msgs WHERE id = ?';
      $stmt = $mysqli->prepare($sql);
      if ($stmt) {
        $stmt->bind_param('i', $del);
        $stmt->execute();
        $stmt->close();
      } else {
        echo '<p>Ошибка при удалении записи: ', $mysqli->error, '</p>';
      }
    }
  }
  /* Удаление записи из БД */
?>
<h3>Оставьте запись в нашей Гостевой книге</h3>

<form method="post" action="<?= $_SERVER['REQUEST_URI'] ?>">
  Имя: <br /><input type="text" name="name" /><br />
  Email: <br /><input type="text" name="email" /><br />
  Сообщение: <br /><textarea name="msg"></textarea><br />

  <br />

  <input type="submit" value="Отправить!" />

</form>
<?php
  /* Вывод записей из БД */
  $sql = "SELECT id, name, email, msg, UNIX_TIMESTAMP(datetime) as dt FROM msgs ORDER BY id DESC";
  $result = $mysqli->query($sql);
  $mysqli->close();

  $total = $result->num_rows;
  echo '<p>Всего записей в гостевой книге: ', $total, '</p>';

  while ($row = $result->fetch_assoc()) {
    echo '<p>';
    echo '<a href="mailto:', htmlspecialchars($row['email']), '">', htmlspecialchars($row['name']), '</a> ';
    echo date('d-m-Y в H:i', $row['dt']);
    echo ' написал<br />';
    echo nl2br(htmlspecialchars($row['msg']));
    echo '</p>';
    echo '<p align="right">';
    echo '<a href="index.php?id=gbook&del=', $row['id'], '">Удалить</a>';
    echo '</p>';
  }
  /* Вывод записей из БД */
?>