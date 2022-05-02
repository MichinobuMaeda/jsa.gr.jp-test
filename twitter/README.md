Twitter配信ツール
========

## 必要なもの

- PHP 7.4 ( サーバ、開発環境 )
- composer ( 少なくとも開発環境 )

## セットアップ

- Twitter の開発者サイトでアプリを登録する。
- account_sample.php をコピーして account.php を作成し、それぞれの定数の値にアプリの認証情報を設定する。
- サーバで composer が利用できる場合、
    - サーバの非公開の場所にすべてのファイルを置く。
    - ``composer install`` を実行する。
- サーバで composer が利用できない場合、
    - 開発環境で ``composer install`` を実行する。
    - サーバの非公開の場所に ``vender`` を含むすべてのファイルを置く。
- shortterm.txt を作成し、配信済みとしたい最新の行 ``<li> 〜 </li>`` を記載する。
    - shortterm.txt が存在しない場合、または、空の場合、ツールはエラーで終了する。
- CRON にコマンドを登録する。

### CRONの設定の例

    47 */2 * * * /usr/bin/php /home/username/twitter/shortterm.php >> /home/username/twitter/shortterm.log 2>&1
