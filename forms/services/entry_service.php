<?php
require_once('utils.php');

function save_entry($event, $title, $from, $reply, $items) {
    $data = [];
    foreach ($_POST as $key => $val) {
        if ($key == 'action' || $key == 'event') { continue; }
        $data[$key] = $val;
    }

    $dbh = connectDb();
    $stmt = $dbh->prepare(<<<SQL
INSERT INTO entries (event, data) VALUES (?, ?)
SQL);
    $stmt->execute(array($event, json_encode($data, JSON_UNESCAPED_UNICODE)));

    // 設定にお問い合わせ先 $from と 'email' 項目がある場合、登録内容をメールする。
    if (isset($from) && $from && isset($_POST['email']) && $_POST['email']) {
        $message = [
            $reply,
            "",
        ];

        foreach ($items as $item) {
            if (array_key_exists('name', $item) && isset($_POST[$item['name']]) && $_POST[$item['name']]) {
                if ($item['type'] == 'text' ||
                    $item['type'] == 'password' ||
                    $item['type'] == 'number' ||
                    $item['type'] == 'email' ||
                    $item['type'] == 'radio'
                ) {
                    $message[] = $item['label'] . ': ' . $_POST[$item['name']];
                } elseif ($item['type'] == 'textarea') {
                    $message[] = $item['label'] . ':';
                    $message[] = '-----';
                    $message[] = $_POST[$item['name']];
                    $message[] = '-----';
                } elseif ($item['type'] == 'checkbox') {
                    $message[] = $item['label'];
                }
            }
        }

        mb_send_mail(
            $_POST['email'],
            '登録受付: ' . $title,
            implode("\n", $message),
            ['From' => $from],
        );
    }
}

function delete_entry($event, $id) {
    $dbh = connectDb();
    $stmt = $dbh->prepare(<<<SQL
UPDATE entries
   SET deleted_at = CURRENT_TIMESTAMP
 WHERE id = CAST(? AS INTEGER)
   AND event = ?
SQL);
    $stmt->execute(array(intval($id), $event));
    return $stmt->rowCount();
}

function restore_entry($event, $id) {
    $dbh = connectDb();
    $stmt = $dbh->prepare(<<<SQL
UPDATE entries
   SET deleted_at = NULL
 WHERE id = CAST(? AS INTEGER)
   AND event = ?
SQL);
    $stmt->execute(array(intval($id), $event));
    return $stmt->rowCount();
}
