<?php
// アクセスログ解析
// --
// 2021-03-12 Michinobu Maeda
//   新規作成。
// 2021-03-17 Michinobu Maeda
//   HTMLを出力する処理を追加。
// 2021-10-09 Michinobu Maeda
//   通常のページではないパターンを追加。一覧のリンクの ? から右を削除。
// 2021-10-11 Michinobu Maeda
//   リンクの中の < と > を &lt; と &rt; に変換する処理を追加。
// --
// 1. 起動
//   php -c /.../etc/php_customized.ini accessloganalyzer.php
//     php_customized.ini の browscap に php_browscap.ini をフルパスで指定する。
// 2. 入力
//   log/access_log_YYYYMMDD.gz および log/access_log_YYYYMMDD のうち
//   未出力のものを対象とする。
// 3. 出力
//   data/access_log_YYYYMMDD.txt: アクセス分析結果
//     入力の各行をタブコードで区切った書式に変換して、
//     data/access_log_YYYYMMDD.txt に出力する。
//       項目1: 日時 ( ISO 8601 フォーマット )
//       項目2: ユーザのOS ( 検索エンジン等のクローラは "Robot" )
//       項目3: コンテンツの種類
//         t: HTMLやPDFのページ
//         v: 画像、スタイルシート
//         p: JavaScriptなどのプログラムが使用するファイル )
//       項目4: コンテンツのパス
//         .../index.html と .../index.htm は .../ に変換する。
//
//   data/access_log_all.txt: 表示用データ
//     ユーザのOS: Robot と コンテンツの種類: v, p を除外したもの。
//       項目1: 日時 ( YYYY/MM/DD )
//       項目2: ユーザのOS
//       項目3: コンテンツのパス
//
//   data/monthly_os.txt: 月別OS別集計
//   data/monthly_page.txt: 月別ページ別集計
//   www/zenkoku/member/access/index.html: HTML形式のレポート

// ログファイルのディレクトリ
define('LOG_PATH', __DIR__ . '/log');

// 出力ファイルのディレクトリ
define('OUT_PATH', __DIR__ . '/data');

// HTMLのパス
define('HTML_PATH', __DIR__ . '/www/zenkoku/member/access/index.html');

// ログファイルを解凍する一時ファイル
$temp = LOG_PATH . '/accessloganalyzer.tmp';

// クローラ の User Agent の正規表現
$regex_crawler = '/bot|crawl|slurp|spider|mediapartners/i';

// クローラであると推測するアドレス
$regex_bot_address = '/your-server\.de/';

// ログデータの正規表現
$regex_log = '/^jsa\.gr\.jp\s+(\S+)\s+(\S+)\s+(\S+)\s+\[([^]]+)\]\s+"GET\s+(\S+)\s+HTTP[^"]+"\s+200\s+(\S+)\s+"([^"]+)"\s+"([^"]+)"/';

// data ディレクトリが存在しない場合は作成する。
if (!is_dir(OUT_PATH)) {
    mkdir(OUT_PATH);
}

// アクセス分析結果を出力する。

// ログファイルのディレクトリの下の各ファイルについて、
foreach (scandir(LOG_PATH) as $log) {
    // ログファイルではない場合は処理対象外とする。
    if (!preg_match('/^access_log_\d{8}(\.gz){0,1}$/', $log)) {
        continue;
    }
    // ログファイルに対応する出力ファイルのパスを決定する。
    $out = OUT_PATH . '/' . preg_replace('/\.gz$/', '', $log) . '.txt';
    // 出力ファイルが存在する場合は処理済みとしてスキップする。
    if (is_file($out)) {
        continue;
    }
    echo($log.PHP_EOL);
    // ログファイルから一時ファイルを作成する。
    if (preg_match('/\.gz$/', $log)) {
        exec('gzip -d -c ' . LOG_PATH . '/' . $log . ' > ' . $temp);
    } else {
        exec('cp ' . LOG_PATH . '/' . $log . ' ' . $temp);
    }

    // ログファイルの各行について、
    $ih = fopen($temp, 'r');
    $oh = fopen($out, 'w');
    while (($line = fgets($ih)) !== false) {
        // ログデータの行として解釈できない場合は処理対象外とする。
        preg_match($regex_log ,$line, $matches);
        if (!$matches) {
            continue;
        }
        $path = $matches[5];
        // .../index.html と .../index.htm を .../ に変換する。
        $path = preg_replace('/\/index\.(html|htm)$/i', '/', $path);
        // 日時のフォーマットを変更する。
        $ts = new DateTime($matches[4]);
        // コンテンツの種類を判定する。
        if (preg_match('/\.(css|jpg|jpeg|png|bmp|gif|svg)(\?.*){0,1}$/i', $path)) {
            $type = 'v';
        } else if (
            preg_match('/\.(js|ico)$/i', $path) ||
            $path === '/robots.txt' ||
            0 === strpos($path, '/cgi-bin/') ||
            preg_match('/^\/pukiwiki\/.*=/', $path) ||
            preg_match('/^\/d\/lib\//', $path) ||
            preg_match('/^\/d\/redirect:d_doku.php\?/', $path) ||
            preg_match('/^\/d\/.*\?do=(admin|login|register)/', $path)
        ) {
            $type = 'p';
        } else {
            $type = 't';
        }
        // ユーザのOSを判定する。判定不能の場合はクローラ ( Robot ) とみなす。
        if (preg_match($regex_crawler, $matches[8]) ||
            preg_match($regex_bot_address, $matches[1])
        ) {
            $cli = 'Robot';
        } else {
            $ua = get_browser($matches[8], true);
            if ($ua['crawler']) {
                $cli = 'Robot';
            } else if ($ua['platform'] === 'unknown') {
                $cli = 'Robot';
            } else if (preg_match('/^Win/i', $ua['platform'])) {
                $cli = 'Windows';
            } else if (preg_match('/^(MacOS|Darwin)/i', $ua['platform'])) {
                $cli = 'MacOS';
            } else {
                $cli = $ua['platform'];
            }
        }
        // 変換したデータをタブ区切りで出力する。
        fwrite($oh, $ts->format('c') . "\t". $cli . "\t" . $type . "\t" . $path . PHP_EOL);
    }
    fclose($ih);
    fclose($oh);
}

// 一時ファイルを削除する。
unlink($temp);

// 表示用データ
$oh = fopen(OUT_PATH . '/access_log_all.txt', 'w');
fwrite($oh, "Date\tOS\tPath".PHP_EOL);

// それぞれのデータファイルについて、
foreach (scandir(OUT_PATH, SCANDIR_SORT_ASCENDING) as $txt) {
    // アクセス分析結果のファイルではない場合は処理対象外とする。
    if (!preg_match('/^access_log_\d{8}\.txt$/', $txt)) {
        continue;
    }
    $ih = fopen(OUT_PATH . '/' . $txt, 'r');
    while (($line = fgets($ih)) !== false) {
        $items = explode("\t", $line);
        $ts = $items[0];
        $os = $items[1];
        $type = $items[2];
        if (preg_match('/^\/pukiwiki\//', $items[3])) {
            $path = mb_convert_encoding(urldecode(trim($items[3])), 'UTF-8', 'EUC-JP');
        } else {
            $path = urldecode(trim($items[3]));
        }
        $path = preg_replace('/\?fbclid=.*/', '', $path);
        $ymd = preg_replace('/-/', '/', substr($ts, 0, 10));

        // 表示用データ
        if ($os !== 'Robot' && $type === 't') {
            fwrite($oh, $ymd . "\t". $os . "\t" . $path . PHP_EOL);
        }
    }
}

fclose($oh);

// 月別の集計を出力する。
function make_monthly_list ($file_name, $item_index, $item_name) {
    $ih = fopen(OUT_PATH . '/access_log_all.txt', 'r');
    $montly = [];
    $total = [];
    while (($line = fgets($ih)) !== false) {
        if (preg_match('/^\d/', $line)) {
            $rec = explode("\t", trim($line));
            $month = substr($rec[0], 0, 7);
            $item = $rec[$item_index];
            if (!array_key_exists($month, $montly)) {
                $montly[$month] = [];
            }
            if (!array_key_exists($item, $montly[$month])) {
                $montly[$month][$item] = 0;
            }
            if (!array_key_exists($item, $total)) {
                $total[$item] = 0;
            }
            $total[$rec[$item_index]]++;
            $montly[$month][$rec[$item_index]]++;
        }
    }

    // 数の多い順の項目のリストを作成する。
    $sorted_count_all = [];
    foreach ($total as $item => $count) {
        $sorted_count_all[] = [
            $item_name => $item,
            'Count' => $count
        ];
    }
    usort($sorted_count_all, function($a, $b) {
        return $b['Count'] - $a['Count'];
    });
    $item_list = [];
    foreach ($sorted_count_all as $item) {
        $item_list[] = $item[$item_name];
    }

    // 新しい順の月のリストを作成する。
    $month_list = array_keys($montly);
    sort($month_list);

    $oh = fopen(OUT_PATH . '/' . $file_name, 'w');
    // 見出し行を出力する。
    fwrite($oh, $item_name);
    foreach ($month_list as $month) {
        fwrite($oh, "\t".$month);
    }
    fwrite($oh, PHP_EOL);
    // 月別項目別集計値を出力する。
    foreach ($item_list as $item) {
        fwrite($oh, $item);
        foreach ($month_list as $month) {
            if (array_key_exists($item, $montly[$month])) {
                fwrite($oh, "\t".$montly[$month][$item]);
            } else {            
                fwrite($oh, "\t0");
            }
        }
        fwrite($oh, PHP_EOL);
    }
    // 月別合計を出力する。
    fwrite($oh, '計');
    foreach ($month_list as $month) {
        $sum = 0;
        foreach ($item_list as $item) {
            if (array_key_exists($item, $montly[$month])) {
                $sum += $montly[$month][$item];
            }
        }
        fwrite($oh, "\t".$sum);
    }
    fwrite($oh, PHP_EOL);
    fclose($oh);
    fclose($ih);
}

// 月別OS別集計を出力する。
make_monthly_list ('monthly_os.txt', 1, 'OS');

// 月別ページ別集計を出力する。
make_monthly_list ('monthly_page.txt', 2, 'Page');

// HTMLを出力する。

function output_table ($file_name, $oh, $link_base = null) {
    fwrite($oh, '<table class="summary">'.PHP_EOL);
    $ih = fopen(OUT_PATH . '/' . $file_name, 'r');
    $title= true;
    while (($line = fgets($ih)) !== false) {
        $rec = explode("\t", trim($line));
        fwrite($oh, '<tr>');
        if ($title) {
            foreach ($rec as $item) {
                fwrite($oh, '<th>'.preg_replace('/\/0*/', '<br>', $item).'</th>');
            }
            $title = false;
        } else {
            $first_col = true;
            foreach ($rec as $item) {
                if ($first_col) {
                    if ($link_base && substr($item, 0, 1) === '/') {
                        fwrite($oh, '<th><a href="'
                        .preg_replace('/\?.*/', '',
                            preg_replace('/</', '&lt;',
                                preg_replace('/>/', '&rt;', $item)
                            )
                        ).'">'
                        .preg_replace('/</', '&lt;',
                            preg_replace('/>/', '&rt;', $item)
                        ).'</a></th>');
                    } else {
                        fwrite($oh, '<th>'.$item.'</th>');
                    }
                    $first_col = false;
                } else {
                    fwrite($oh, '<td>'.$item.'</td>');
                }
            }
        }
        fwrite($oh, '</tr>'.PHP_EOL);
    }
    fclose($ih);
    fwrite($oh, '</table>'.PHP_EOL);
}

$updated_at = new DateTime();
$header = <<<EOS
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
table.summary {
    border-collapse: collapse;
}
table.summary th {
    padding: 1px 2px 1px 2px;
    border: solid 1px #999999;
    font-weight: normal;
    background-color: #f0f0f0;
}
table.summary th:nth-child(1) {
    text-align: left;
    max-width: 256px;
    word-break: break-all;
}
table.summary td {
    padding: 1px 2px 1px 2px;
    border: solid 1px #999999;
    font-weight: normal;
    text-align: right;
}
</style>
<title>アクセス数集計</title>
</head>
<body>
<h1>アクセス数集計</h1>
<p>Updated at {$updated_at->format('Y-m-d H:i:s')}</p>
<ul>
    <li>2021年1月は21〜31日の11日間の集計です。</li>
    <li>2021年4月は12日と13日の2日分のデータが欠けています。</li>
    <li>今月は昨日までの数の集計です。</li>
    <li>/d/statement/20200508olympics に大量の不審なアクセスがあります。不審なアクセスを集計から除く方法を検討中です。</li>
</ul>
EOS;
$footer = <<<EOS
<p>Updated at {$updated_at->format('Y-m-d H:i:s')}</p>
</body>
</html>
EOS;
$oh = fopen(HTML_PATH, 'w');
fwrite($oh, $header.PHP_EOL);
fwrite($oh, '<h2>月別OS別集計</h2>'.PHP_EOL);
output_table ('monthly_os.txt', $oh);
fwrite($oh, '<h2>月別ページ別集計</h2>'.PHP_EOL);
output_table ('monthly_page.txt', $oh, 'https://jsa.gr.jp');
fwrite($oh, $footer.PHP_EOL);
fclose($oh);

echo('end.');
