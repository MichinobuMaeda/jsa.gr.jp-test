<?php
// JSA 申し込み受付フォーム
// 
// 設定ファイル
//   [イベントID].php
// データファイル
//   database.sqlite3 ( SQLite 3 形式 )
// 入力フォームのURL
//   https://jsa.gr.jp/05event/form.php?event=[イベントID]
// 管理者ログインのURL
//   https://jsa.gr.jp/05event/form.php?action=login
//
// [イベントID] の例: 24sogaku -- 第24回総合学術研究集会

require_once('views/form_view.php');
require_once('views/form_prepared_view.php');
require_once('views/form_closed_view.php');
require_once('views/form_success_view.php');
require_once('views/login_view.php');
require_once('views/admin_view.php');
require_once('views/entries_list.php');
require_once('views/error_view.php');
require_once('services/account_service.php');
require_once('services/entry_service.php');
require_once('services/utils.php');

// 暗号化の種
$secret = 'w8eO#&*^0dOIHheU898*913nGiSH*$8S*hg&^S%)s';

// このスクリプトのURL
$request_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' .
    $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

// このスクリプトのURL ( ? 以降のパラメータを除く )
$base_url = preg_replace('/\?.*/', '', $request_url);

// 認証のためのセッションを開始する。
session_name('event_form');
session_start();

$action = false;
$event = false;

// HTTP REQUEST METHOD: GET ( ページの表示 ) の場合
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        $action = (isset($_GET['action']) && $_GET['action']) ? $_GET['action'] : 'form';
        $event = (isset($_GET['event']) && $_GET['event']) ? $_GET['event'] : false;

        if ($event) {
            require_once($event . '.php');
        }

        $_SESSION['form']  = false;

        if ($action == 'form') {
            if ($status == 'prepared') {
                show_form_prepared($title, $url);
            } elseif ($status == 'active') {
                $_SESSION['form']  = true;
                show_form($title, $base_url, $event, $url, $items);
            } elseif ($status == 'closed') {
                show_form_closed($title, $url);
            } else {
                header("HTTP/1.1 400 Bad Request");
            }
        } elseif ($action == 'form_success') {
            show_form_success($title, $url);
        } elseif ($action == 'error') {
            show_error($title, $url);
        } elseif ($action == 'login') {
            show_login($title);

        // At least one priv is required
        } elseif (!isset($_SESSION['email']) || count(getPrivs($_SESSION['email'])) == 0) {
            header('Location: ' . $base_url . '?action=login');
        } elseif ($action == 'admin') {
            show_admin($title, $base_url);
            
        // The privilege of given event is required.
        } elseif (!in_array($event, getPrivs($_SESSION['email']))) {
            header('Location: ' . $base_url . '?action=admin');
        } elseif ($action == 'list') {
            $edit = isset($_GET['edit']) && $_GET['edit'] == 'y';
            entries_list($title, $event, $items, $base_url, $edit);
        } else {
            header("HTTP/1.1 400 Bad Request");
        }
    } catch (Exception $e) {
        var_dump($e);
    }

// HTTP REQUEST METHOD: POST ( データの更新 ) の場合
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $action = (isset($_POST['action']) && $_POST['action']) ? $_POST['action'] : false;
        $event = (isset($_POST['event']) && $_POST['event']) ? $_POST['event'] : false;

        if ($event) {
            require_once($event . '.php');
        }

        if ($action == 'form') {
            if (!isset($_SESSION['form']) || !$_SESSION['form']) {
                throw new Exception('Invalid session state.');
            }
            unset($_SESSION['form']);

            save_entry($event, $title, $from, $reply, $items);
            header('Location: ' . $base_url . '?event=' . $event . '&action=form_success');
        } elseif ($action == 'login') {
            if (login($secret)) {
                header('Location: ' . $base_url . '?action=admin');
            } else {
                header('Location: ' . $base_url . '?action=login&message=failed');
            }
        } elseif ($action == 'logout') {
            logout();
            header('Location: ' . $base_url . '?action=login');
        } elseif ($action == 'change_password') {
            change_password($secret);
            header('Location: ' . $base_url . '?action=admin');
            
        // The privilege of given event is required.
        } elseif ($event && !in_array($event, getPrivs($_SESSION['email']))) {
            header("HTTP/1.1 403 Forbidden");
        } elseif ($action == 'delete') {
            delete_entry($event, $_POST['id']);
            header('Location: ' . $base_url . '?event=' . $event . '&action=list&edit=y');
        } elseif ($action == 'restore') {
            restore_entry($event, $_POST['id']);
            header('Location: ' . $base_url . '?event=' . $event . '&action=list&edit=y');

        // The privilege 'sys' is required.
        } elseif (!in_array('sys', getPrivs($_SESSION['email']))) {
            header("HTTP/1.1 403 Forbidden");
        } elseif ($action == 'update_account') {
            update_account();
            header('Location: ' . $base_url . '?action=admin');
        } elseif ($action == 'create_account') {
            create_account($base_url, $secret, $from);
            header('Location: ' . $base_url . '?action=admin');
        } elseif ($action == 'reset_password') {
            reset_password($base_url, $secret, $from);
            header('Location: ' . $base_url . '?action=admin');
        } else {
          header("HTTP/1.1 400 Bad Request");
        }
    } catch (Exception $e) {
//         var_dump($e);
        header('Location: ' . $base_url . '?event=' . $event . '&action=error');
    }

// HTTP METHOD が GET でも POST でもない場合
} else {
    header("HTTP/1.1 405 Method Not Allowed");
}
?>
