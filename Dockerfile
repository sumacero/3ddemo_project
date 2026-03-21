# --- Stage 1: Vite のビルド (Node.js) ---
FROM node:22 AS node-builder
WORKDIR /app
# project_volume の中身をコピーするように調整
COPY ./project_volume . 
RUN npm install && npm run build

# --- Stage 2: 実行環境 (PHP 8.4 + Apache) ---
FROM php:8.4-apache

# 必要なシステムライブラリをインストール
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    zip unzip git libatomic1 libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql bcmath intl

# Apacheの設定 (mod_rewrite有効化)
RUN a2enmod rewrite

# ApacheのDocumentRootをLaravelのpublicディレクトリに強制変更
RUN sed -ri -e 's!/var/www/html!/var/www/project/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/project/public!g' /etc/apache2/apache2.conf

WORKDIR /var/www/project

# プロジェクトファイルをコピー
COPY ./project_volume .

# Stage 1 でビルドしたJS/CSSをコピー
COPY --from=node-builder /app/public/build ./public/build

# Composerインストール
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# 権限設定
RUN chown -R www-data:www-data storage bootstrap/cache

# 1. rewrite機能を有効化
RUN a2enmod rewrite

# 2. Apacheのデフォルト設定ファイルを直接書き換え (AllowOverride All)
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# 3. サイト個別の設定ファイルも念のため書き換え
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80
CMD ["apache2-foreground"]
