FROM php:8.2-apache

# Instalar extensões necessárias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilitar mod_rewrite para URLs amigáveis
RUN a2enmod rewrite

# Permitir uploads de imagens 4K de até 100 MB
COPY docker/php-upload.ini /usr/local/etc/php/conf.d/voltx-uploads.ini

# Definir permissões
RUN chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html

EXPOSE 80
