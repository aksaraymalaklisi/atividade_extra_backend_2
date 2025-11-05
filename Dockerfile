# Usa uma imagem oficial do PHP com Apache
FROM php:8.4-apache

# Instala extensões necessárias para MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copia os arquivos da aplicação para o diretório web padrão
COPY src/ /var/www/html/