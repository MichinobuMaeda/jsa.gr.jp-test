<?php
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

if (in_array($ext, ['html', 'htm'])) {
    $data = file_get_contents(__DIR__ . '/www/zenkoku' . $path);
    $encoding = mb_detect_encoding($data, mb_list_encodings());
    $encoding = str_replace('SJIS', 'Shift_JIS', $encoding);
    header('Content-Type: text/html; charset=' . $encoding);
    return eval('?> ' . $data);
}
return false;
