# jsa.gr.jp テスト環境

## A. 準備

### A.1. ツール一式

https://github.com/MichinobuMaeda/jsa.gr.jp-test の Code ボタンの Download ZIP リンクからダウンロードして、どこか ( 例: `C:\jsazennkoku` ) に解凍する。

![Code - Download ZIP](local/code-dowload-zip.png)


### A.2. PHP 7.4

#### A.2.1. Windows の場合

<https://windows.php.net/download> から PHP 7.4 VC15 x64 Thread Safe の ZIP をダウンロードして、どこか ( 例: `C:\php7.4` ) に解凍する。

`env_sample.bat` を同じフォルダの `env.bat` にコピーして `PHP_HOME` の値をPHPを置いた場所に変更する。

コマンドプロンプトを開いてこのバッチを実行する。

    > env.bat

PHPが正常に動くことを確認する。

    > php -v

コマンドプロンプトでなく PowerShell を利用する場合は `*.bat` の代わりに `*.ps1` を使う。

『日本の科学者』表紙画像リサイズのスクリプトを利用する場合は、PHPを置いたフォルダの `php.ini-development` を同じフォルダの `php.ini` にコピーして、以下の 2か所を変更する。

先頭の `;` を削除する。

    extension_dir = "ext"

先頭の `;` を削除する。

    extension=gd2


#### A.2.2. Mac OS の場合

PHP を Homebrew 等でインストールする。

    $ brew install php@7.4

`env_sample_mac_m1.sh` を `env.sh` にコピーし、必要に応じて `PHP_HOME` の値を編集する。

ターミナルを開いてこのスクリプトを実行する。

    $ source ./env.sh

PHPが正常に動くことを確認する。

    > php --version


## B. 利用

### B.1. Webページのプレビュー

サーバの `/home/jsazenkoku/www/zenkoku` の下から以下のファイルを `www/zenkoku` にダウンロードする。

- index.html ( トップページ )
- css
- img
- js
- 自分が担当するコーナーのフォルダ

`www/.gitignore` と `www/zenkoku/.gitignore` は GitHub に空のフォルダを保存するためのダミーのファイルなので、無視してよい。消してもよい。

PC上でテスト用サイトを起動する。

    > php -S localhost:8000 -t www/zenkoku router.php

テスト用サイトは http://localhost:8000/ で参照できる。以下の2点を除き、本物と見た目は同じ。

- Dokuwiki は正常に動作しない。
- 会員専用コーナーはID・パスワード無しで参照できる。

`rsync` や `scp` が利用できる場合は `sync.bat` または `sync.sh` でサーバのファイルのダウンロード、 `upload.php` でファイルのアップロード、 `upload-jjs.php` で『日本の科学者』目次更新時の一括アップロードができる。

### B.2. 『日本の科学者』表紙画像のリサイズ

事務局から受け取った『日本の科学者』の表紙の画像 `jjsYYYYMM.jpg` を幅300ピクセルに縮小したものを　`www/zenkoku/jjs-cover-s.jpg` にコピーする。

    > php resize_image.php path_to/jjsYYYYMM.jpg
