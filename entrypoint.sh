#!/bin/bash

# 1. データベースファイルがなければ作成
mkdir -p database
touch database/database.sqlite

# 2. 権限を強制セット
chmod -R 777 storage bootstrap/cache database
chmod 666 database/database.sqlite
chown -R www-data:www-data storage bootstrap/cache database

# 3. データベースのテーブル作成（自動実行）
php artisan migrate --force

# 4. 本来のプロセスの起動（Apacheを動かす）
exec apache2-foreground
