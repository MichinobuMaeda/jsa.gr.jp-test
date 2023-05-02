<?php

// データベースに接続する。
function connectDb() {
    $dbpath = dirname(__FILE__) . '/../database.sqlite3';
    
    $init = !file_exists($dbpath);

    if ($init) {
        touch($dbpath);
    }
    
    $dbh = new PDO('sqlite:' . $dbpath);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($init) {
	    $stmt = $dbh->prepare(<<<EOF
CREATE TABLE IF NOT EXISTS accounts (
  email     TEXT PRIMARY KEY NOT NULL,
  password  TEXT NOT NULL,
  priv      TEXT
)
EOF);
	    $stmt->execute();

        $stmt = $dbh->prepare(<<<EOF
CREATE TABLE IF NOT EXISTS entries (
  id        INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  event     TEXT NOT NULL,
  data      TEXT NOT NULL,
  note      TEXT,
  ts        TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  deleted_at TIMESTAMP
)
EOF);
        $stmt->execute();
    }

    return $dbh;
}

// ランダムなパスワードを生成する。
// https://stackoverflow.com/questions/6101956/generating-a-random-password-in-php
function randomPassword($len) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()_+-=?/<>';
    $pass = array(); //remember to declare $pass as an array
    $charLen = strlen($chars) - 1;

    for ($i = 0; $i < $len; $i++) {
        $n = rand(0, $charLen);
        $pass[] = $chars[$n];
    }

    return implode($pass); //turn the array into a string
}

// メールアドレスから権限のリストを取得する。
function getPrivs($email) {
    $dbh = connectDb();
    $stmt = $dbh->prepare(<<<SQL
SELECT priv FROM accounts WHERE email = ?
SQL);
    $stmt->execute(array($email));
    $res = $stmt->fetch();

    return $res ? explode(",", preg_replace('/\s/', '', $res['priv'])) : [];
}
