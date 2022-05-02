# jsa.gr.jp テスト環境

## 必要なもの

- PHP 7.4

### Mac OS

Homebrew 等でインストールする。

    $ brew install php@7.4

`env_sample_mac_m1.sh` を `env.sh` にコピーし、必要に応じて `PHP_HOME` の値を編集する。

作業を始めるときに環境変数を設定する。

    $ source ./env.sh


## 利用

サーバの `/home/jsazenkoku/www/zenkoku` の下のファイルを `www/zenkoku` にダウンロードする。

### Mac OS / Linux

http://localhost:8000/ でサーバを起動する。

    $ php -S localhost:8000 -t www/zenkoku router.php

『日本の科学者』の表紙の画像 `jjsYYYYMM.jpg` を幅300ピクセルに縮小したものを
`www/zenkoku/jjs-cover-s.jpg` にコピーする。

    $ php resize_image.php path_to/jjsYYYYMM.jpg
