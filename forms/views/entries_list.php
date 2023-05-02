<?php
require_once('header_view.php');
require_once('footer_view.php');
require_once(dirname(__FILE__) . '/../services/utils.php');

function entries_list($title, $event, $items, $base_url, $edit) {
	show_header($title);
?>
<div>
  <a href="<?php echo $base_url; ?>?action=admin">管理者メニュー</a> /
  <a href="<?php echo $base_url; ?>?event=<?php echo $event ?>">登録フォーム</a>
</div>
<h2>申し込み一覧</h2>
<p>
<?php
    if ($edit) {
        echo '<a href="' . $base_url . '?event=' . $event . '&action=list">戻る</a>';
    } else {
        echo '<a href="' . $base_url . '?event=' . $event . '&action=list&edit=y">削除 / 復活</a>';
    }
?>
  ※削除の操作は取り消すことができます。
</p>
<div class="fixed-head-table">
<table>
  <thead>
    <tr>
<?php
    if ($edit) {
        echo '<th>操作</th>';
    }
?>
      <th>timestamp</th>
<?php
    foreach ($items as $item) {
        if (!array_key_exists('name', $item)) { continue; }
        echo '<th>' . $item['name'] . '</th>';
    }
?>
    </tr>
  </thead>
  <tbody>
<?php
    $dbh = connectDb();

    if ($edit) {
  	  $stmt = $dbh->prepare(<<<SQL
SELECT strftime('%Y/%m/%d %H:%M:%S', ts, 'localtime') as ts,
       id,
       data,
       deleted_at
  FROM entries
 WHERE event = ?
 ORDER BY ts
SQL);
    } else {
  	  $stmt = $dbh->prepare(<<<SQL
SELECT strftime('%Y/%m/%d %H:%M:%S', ts, 'localtime') as ts,
       id,
       data
  FROM entries
 WHERE event = ?
   AND deleted_at IS NULL
 ORDER BY ts
SQL);
    }

  	$stmt->execute(array($event));
  	$res = $stmt->fetchAll();

  	foreach ($res as $row) {
  	    $data = json_decode($row['data'], true);
        echo ($edit && $row['deleted_at']) ? '<tr class="deleted">' : '<tr>';

        if ($edit) {
?>
    <td>
      <form method="POST" action="<?php echo $base_url; ?>">
        <input type="hidden" name="event" value="<?php echo $event; ?>">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
<?php
            echo $row['ts'] . '<br>';
            if ($row['deleted_at']) {
                echo '<input type="hidden" name="action" value="restore">';
                echo '<button style="width: auto;">復活</button>';
            } else {
                echo '<input type="hidden" name="action" value="delete">';
                echo '<button style="width: auto;">削除</button>';
            }
?>
      </form>
    </td>
<?php
        } else {
            echo '<td>' . $row['ts'] . '</td>';
        }

        foreach ($items as $item) {
            if (!array_key_exists('name', $item)) { continue; }
            echo '<td>' . nl2br(htmlspecialchars(array_key_exists($item['name'], $data) ? $data[$item['name']] : '')) . '</td>';
        }

        echo '</tr>';
    }
?>
  </tbody>
</table>
</div>
<?php
    show_footer();
}
?>
