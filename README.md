# jsa.gr.jp テスト環境

## 必要なもの

- PHP 7.4

### Windows

<https://windows.php.net/download> から VC15 x64 Thread Safe の ZIP をダウンロードして、どこか ( 例: C:\php7.4 ) に解凍する。

解凍したフォルダの `php.ini-development` を `php.ini` にコピーして、以下の 2か所を変更する。

先頭の `;` を削除する。

    extension_dir = "ext"

先頭の `;` を削除する。

    extension=gd2

`env_sample.bat` を `env.bat` にコピーし、必要に応じて `PHP_HOME` の値を編集する。

作業を始めるときに環境変数を設定する。

    > env.bat

PHPが正常に動くことを確認する。

    > php -v


### Mac OS

PHP を Homebrew 等でインストールする。

    $ brew install php@7.4

`env_sample_mac_m1.sh` を `env.sh` にコピーし、必要に応じて `PHP_HOME` の値を編集する。

作業を始めるときに環境変数を設定する。

    $ source ./env.sh

PHPが正常に動くことを確認する。

    > php --version


## 利用

サーバの `/home/jsazenkoku/www/zenkoku` の下のファイルを `www/zenkoku` にダウンロードする。

http://localhost:8000/ でサーバを起動する。

    > php -S localhost:8000 -t www/zenkoku router.php

『日本の科学者』の表紙の画像 `jjsYYYYMM.jpg` を幅300ピクセルに縮小したものを
`www/zenkoku/jjs-cover-s.jpg` にコピーする。

    > php resize_image.php path_to/jjsYYYYMM.jpg
