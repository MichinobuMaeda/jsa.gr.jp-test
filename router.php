<?php
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

$doc_root = __DIR__ . '/www/zenkoku';
$dokuwiki_base = '/d';

if (in_array($ext, ['html', 'htm'])) {
    $data = file_get_contents(__DIR__ . '/www/zenkoku' . $path);
    $encoding = mb_detect_encoding($data, mb_list_encodings());
    $encoding = str_replace('SJIS', 'Shift_JIS', $encoding);
    header('Content-Type: text/html; charset=' . $encoding);
    return eval('?> ' . $data);
} else if ($_SERVER['REQUEST_METHOD'] == 'GET'
    && ($_SERVER['REQUEST_URI'] == $dokuwiki_base
        || substr($_SERVER['REQUEST_URI'], 0, strlen($dokuwiki_base) + 1) == $dokuwiki_base . '/')
    ) {
    $req = substr($_SERVER['REQUEST_URI'], strlen($dokuwiki_base));

    if (substr($req, 0, strlen('/_media/')) == '/_media/') {
        $param = str_replace('?', '&', substr($req, strlen('/_media/')));
        header('Location: ' . $dokuwiki_base . '/lib/exe/fetch.php?media=' . $param);
        return true;
    } else if (substr($req, 0, strlen('/_detail/')) == '/_detail/') {
        $param = str_replace('?', '&', substr($req, strlen('/_detail/')));
        header('Location: ' . $dokuwiki_base . '/lib/exe/detail.php?media=' . $param);
        return true;
    } else if ($req == '' || $req == '/' || $req == '/index.php') {
        include $doc_root . $dokuwiki_base . '/doku.php';
    } else if (preg_match('/\?do=/', $req)
        && substr($req, 0, strlen('/doku.php?do=')) != '/doku.php?do=') {
        header('Location: ' . $dokuwiki_base . '/doku.php?do=' . preg_replace('/.*\?do=/', '', $req));
        return true;
    } else if (file_exists($doc_root . $dokuwiki_base . preg_replace('/\?.*/', '', $req))) {
        return false;
    } else {
        header('Location: ' . $dokuwiki_base . '/doku.php?id=' . substr($req, 1));
        return true;
    }
}
return false;
