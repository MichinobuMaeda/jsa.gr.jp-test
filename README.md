# jsa.gr.jp テスト環境

## A. 必要なもの

### A.1. ツール一式

https://github.com/MichinobuMaeda/jsa.gr.jp-test の Code ボタンの Download ZIP リンクからダウンロードして、どこか ( 例: `C:\jsazennkoku` ) に解凍する。

![Code - Download ZIP](local/code-dowload-zip.png)


### A.2. PHP 7.4

#### A.2.1. Windows

<https://windows.php.net/download> から PHP 7.4 VC15 x64 Thread Safe の ZIP をダウンロードして、どこか ( 例: `C:\php7.4` ) に解凍する。

作業を始めるときに環境変数を設定する。

    > env.bat

PHPが正常に動くことを確認する。

    > php -v

『日本の科学者』の表紙の画像のリサイズのスクリプトを利用する場合は、解凍したフォルダの `php.ini-development` を `php.ini` にコピーして、以下の 2か所を変更する。

先頭の `;` を削除する。

    extension_dir = "ext"

先頭の `;` を削除する。

    extension=gd2

`env_sample.bat` を `env.bat` にコピーし、必要に応じて `PHP_HOME` の値を編集する。


#### A.2.2. Mac OS

PHP を Homebrew 等でインストールする。

    $ brew install php@7.4

`env_sample_mac_m1.sh` を `env.sh` にコピーし、必要に応じて `PHP_HOME` の値を編集する。

作業を始めるときに環境変数を設定する。

    $ source ./env.sh

PHPが正常に動くことを確認する。

    > php --version


## B. 利用

### B.1. Webページのプレビュー

サーバの `/home/jsazenkoku/www/zenkoku` の下のファイルを `www/zenkoku` にダウンロードする。

http://localhost:8000/ でテスト用サイトを起動する。

    > php -S localhost:8000 -t www/zenkoku router.php

テスト用サイトの制限事項

- Wiki は正常に動作しない。
- 会員専用コーナーはID・パスワード無しで参照できる。

`rsync` や `scp` が利用できる場合は `sync.bat` または `sync.sh` でサーバのファイルのダウンロード、 `upload-jjs.php` でファイルのアップロード、 `upload-jjs.php` で『日本の科学者』目次更新時の一括アップロードができる。

### B.2. 『日本の科学者』表紙画像のリサイズ

事務局から受け取った『日本の科学者』の表紙の画像 `jjsYYYYMM.jpg` を幅300ピクセルに縮小したものを
`www/zenkoku/jjs-cover-s.jpg` にコピーする。

    > php resize_image.php path_to/jjsYYYYMM.jpg
