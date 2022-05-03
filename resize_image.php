<?php
if (count($argv) < 2) {
    echo 'Usage: php resize_image.php source_image_file.jpg';
    return -1;
}

// Get the source image.
$src_path = realpath(__DIR__ . '/' . $argv[1]);
list($src_witdh, $src_height) = getimagesize($src_path);
$src_image = imagecreatefromjpeg($src_path);

// Generate the target image
$trg_width = 300;
$trg_height = round($src_height * $trg_width / $src_witdh);
$trg_img = imagecreatetruecolor($trg_width, $trg_height);
imagecopyresampled($trg_img, $src_image, 0, 0, 0, 0, $trg_width, $trg_height, $src_witdh, $src_height);

// Save the target image
imagejpeg($trg_img, __DIR__ . '/www/zenkoku/jjs-cover-s.jpg');
