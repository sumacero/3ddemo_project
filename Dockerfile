# --- Stage 1: Vite のビルド (Node.js) ---
FROM node:22 AS node-builder
WORKDIR /app
# GitHubのルートディレクトリにある全ファイルをコピー
COPY . . 
RUN npm install && npm run build

# --- Stage 2: 実行環境 (PHP 8.4 + Apache) ---
FROM php:8.4-apache

# 必要なシステムライブラリをインストール
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    zip unzip git libatomic1 libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql bcmath intl

# 1. Apacheのmod_rewriteを有効化（Laravelのルーティングに必須）
RUN a2enmod rewrite

# 2. Apacheのサイト設定ファイルを新規作成（DocumentRootの設定とAllowOverrideの許可）
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/project/public\n\
    <Directory /var/www/project/public>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/project

# プロジェクトファイルをすべてコピー
COPY . .

# Stage 1 でビルドしたJS/CSS（Vite）をコピー
COPY --from=node-builder /app/public/build ./public/build

# Composerインストールと実行
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# 3. 権限設定（storage, cache, databaseへの書き込みを許可）
RUN chown -R www-data:www-data storage bootstrap/cache database
RUN chmod -R 775 storage bootstrap/cache database

EXPOSE 80

# entrypoint.sh をコピーして実行権限を与える
COPY entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

# 最後に CMD ではなく ENTRYPOINT を指定
ENTRYPOINT ["entrypoint.sh"]
