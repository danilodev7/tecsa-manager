FROM php:7.4-apache

RUN docker-php-ext-install pdo pdo_mysql

# Copiar arquivos do backend
COPY backend /var/www/backend

# Copiar arquivos do frontend para o diretório raiz do servidor Apache
COPY frontend /var/www/html

# Configurar permissões e diretório do Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

RUN echo "<Directory /var/www/html>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" >> /etc/apache2/apache2.conf

RUN echo "<Directory /var/www/backend>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" >> /etc/apache2/apache2.conf

# Configurar o Apache para redirecionar as requisições para a API
RUN echo "Alias /api /var/www/backend/api\n\
<Directory /var/www/backend/api>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" >> /etc/apache2/apache2.conf

# Expor a porta 9000
EXPOSE 9000

RUN echo "Listen 9000" >> /etc/apache2/ports.conf

# Habilitar mod_rewrite do Apache
RUN a2enmod rewrite