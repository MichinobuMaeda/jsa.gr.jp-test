<?php
require_once('utils.php');

function add_col_deleted_at($dbh) {
    $stmt = $dbh->prepare(<<<SQL
SELECT * from entries
 LIMIT 1
SQL);
    $stmt->execute();
    $res = $stmt->fetch();

    if ($res && !array_key_exists('deleted_at', $res)) {
        $stmt = $dbh->prepare(<<<SQL
ALTER TABLE entries
 ADD COLUMN deleted_at TIMESTAMP
SQL);
        $stmt->execute();
    }
}

function login($secret) {
	$dbh = connectDb();
	$stmt = $dbh->prepare("SELECT count(*) FROM accounts");
	$stmt->execute();
	$res = $stmt->fetch();

	if ($res[0]) {
		$stmt = $dbh->prepare("SELECT email FROM accounts WHERE email = ? and password = ?");
		$stmt->execute(array(
			$_POST['email'],
            hash('sha256', $_POST['password'] . $secret),
        ));
        $res = $stmt->fetch();

        if ($res) {
            $_SESSION['email'] = $_POST['email']; 
            add_col_deleted_at($dbh);
            return true;

        } else {
            unset($_SESSION['email']);
            return false;
        }
    } else {
        $initial_privs = ['sys'];
        $stmt = $dbh->prepare(<<<EOF
INSERT INTO accounts (email, password, priv) VALUES (?, ?, ?)
EOF);
        $stmt->execute(array(
            $_POST['email'],
            hash('sha256', $_POST['password'] . $secret),
            implode(",", $initial_privs),
        ));
        $_SESSION['email'] = $_POST['email'];
        add_col_deleted_at($dbh);
        return true;
    }
}

function logout() {
	unset($_SESSION['email']);
	unset($_SESSION['priv']);
}

function update_account() {
    $dbh = connectDb();
    $stmt = $dbh->prepare(<<<EOF
UPDATE accounts SET priv = ? WHERE email = ?
EOF);
    $stmt->execute(array(
		preg_replace('/\s/', '', $_POST['priv']),
		$_POST['email'],
    ));
}

function create_account($base_url, $secret, $from) {
    $password = randomPassword(8);
    $dbh = connectDb();
    $stmt = $dbh->prepare(<<<EOF
INSERT INTO accounts (email, password, priv) VALUES (?, ?, ?)
EOF);
    $stmt->execute(array(
      	$_POST['email'],
      	hash('sha256', $password . $secret),
      	preg_replace('/\s/', '', $_POST['priv']),
    ));
    
    // 本人にメールで通知する。
    $message = [
      	'JSAイベント参加受付システムの管理者アカウントを作成しました。',
      	$base_url . '?action=login',
      	'からログインしてください。',
      	'パスワードは',
      	$password,
      	'です。'
    ];

    mb_send_mail(
      	$_POST['email'],
      	'JSAイベント参加受付システム 管理者アカウント作成',
      	implode("\n", $message),
      	['From' => $from],
    );
}

function reset_password($base_url, $secret, $from) {
    $password = randomPassword(8);
    $dbh = connectDb();
    $stmt = $dbh->prepare("UPDATE accounts SET password = ? WHERE email = ?");
    $stmt->execute(array(
        hash('sha256', $password . $secret),
        $_POST['email'],
    ));
    
    // 本人にメールで通知する。
    $message = [
        'JSAイベント参加受付システムのパスワードを再設定しました。',
        $base_url . '?action=login',
        'からログインしてください。',
        'パスワードは',
        $password,
        'です。'
    ];

    mb_send_mail(
        $_POST['email'],
        'JSAイベント参加受付システム パスワード再設定',
        implode("\n", $message),
        ['From' => $from],
    );
}

function change_password($secret) {
	$dbh = connectDb();

	$stmt = $dbh->prepare("SELECT priv FROM accounts WHERE email = ? and password = ?");
	$stmt->execute(array(
	  	$_SESSION['email'],
	  	hash('sha256', $_POST['curr_password'] . $secret),
	));
	$res = $stmt->fetch();
	if (!$res) {
	  	throw new Exception('Invalid password.');
	}
	
	$stmt = $dbh->prepare("UPDATE accounts SET password = ? WHERE email = ?");
	$stmt->execute(array(
	  	hash('sha256', $_POST['password'] . $secret),
	  	$_SESSION['email'],
	));
}
