<?php
// Short Term の掲載内容を Twitter に送信する。
// --
// 作成: michinobumaeda@gmail.com
// --
// 会員専用コーナーは送信対象としない。
// 送信するメッセージの書式は
// --例------------------------
// [日本の科学者]
// https://jsa.gr.jp/04pub/index.html#jjs
// 2021年3月号の目次と特集の「まえがき」を掲載しました。
// ----------------------------
// とする。
// 毎時の定時ジョブとして起動する。CRONの設定はコントロールパネルから。
// レンタルサーバに composer コマンドが無いので、ローカルで作成した vendor ディレクトリをアップロードする。
//   composer.json  -- 必要なパッケージの一覧
//   composer.lock  -- 必要なパッケージの適用版の一覧（自動生成）
//   account.php    -- Twitter API に接続するアカウント
//   shortterm.php  -- このファイル
//   shortterm.txt  -- 送信済みの記録 ( 直近100件分 )
//   shortterm.log  -- ログ ( CRON のコマンドで指定 )
require "account.php";
require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

// ログ出力処理
function logging($message) {
    print((new DateTime('NOW'))->format(DateTime::ISO8601).' '.$message."\n");
}

// ホームページのURL
$siteUrl = 'https://jsa.gr.jp/';
// 送信する行のリスト
$sending = [];
// 送信するメッセージのリスト
$messages = [];

// 送信済みの記録を取得する。
$history = __DIR__.'/shortterm.txt';
$sent = file($history, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
// 送信済みの記録が取得できない場合は処理を終了する。
if (!$sent || !count($sent)) {
    logging('Error: failed to open: '.$history);
    return;
}
// トップページを開く。
$index = dirname(__DIR__).'/www/zenkoku/index.html';
$shortTermSection = false;
$handle = fopen($index, 'r');
// トップページを開けない場合は処理を終了する。
if (!$handle) {
    logging('Error: failed to open: '.$index);
    return;
}
// Short Term の先頭から順に
// <li>[<a href="{path}">{page}</a>]{message}{date}</li>
// の行を送信済みの行まで処理する。
// member/* はスキップする。
while (($line = fgets($handle)) !== false) {
    if (!$shortTermSection) {
        $shortTermSection = strpos($line, 'id="short-term-list"');
    } else if (preg_match('/<li>\s*\[\s*<a\s+href="(.+)">\s*([^<]+)\s*<\/a>\s*\]\s*(.+)\s*<\/li>/', $line, $matches)) {
        if (in_array($matches[0], $sent)) {
            break;
        }
        if (preg_match('/^member\//', $matches[1])) {
            continue;
        }
        $url = $matches[1];
        if (!preg_match('/^(http|https):\/\//', $url)) {
            $url = $siteUrl.$url;
        }
        $page = $matches[2];
        // 末尾の日付 m/d を削除する。
        $message = preg_replace('/\s*\d+\s*\/\s*\d+\s*$/', '', $matches[3]);
        $sending[] = $matches[0];
        $messages[] = '['.$page."]\n".$message."\n".$url;
    }        
}
fclose($handle);
// 送信するメッセージがない場合は処理を終了する。
if (!count($messages)) {
    return;
}
// 送信済みの行を保存する。
$ret = file_put_contents($history, implode("\n", array_slice(array_merge($sending, $sent), 0, 100)));
// 送信済みの行を保存できない場合は処理を終了する。
if (false === $ret) {
    logging('Error: failed to save: '.$history);
    return;
}
// 送信する。
logging('Info: send '.count($messages).' messages.');
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
foreach ($messages as $message) {
    $connection->post("statuses/update", ["status" => $message]);
}
logging('end.');
