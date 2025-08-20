# Base image com PHP 8.3-FPM e Yii
FROM ipti/yii2:8.3-fpm

# Set workdir
WORKDIR /app

# Copiar apenas composer.json e composer.lock primeiro (aproveita cache)

# Instalar Composer dentro da imagem
USER root

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

COPY composer.json composer.lock /app/

# Instalar dependências do PHP via Composer (inclui OTEL)
RUN composer install --no-plugins --ignore-platform-reqs

# Agora copiar todo o restante do código da aplicação
COPY . /app

# Ajustar arquivos de configuração e memória PHP/Nginx
RUN sed -i "s|/app/web|/app|g" /etc/nginx/conf.d/default.conf \
    && sed -i "s|memory_limit=128M|memory_limit=512M|g" /usr/local/etc/php/conf.d/base.ini \
    && sed -i "s|fastcgi_pass 127.0.0.1:9000;|fastcgi_pass 127.0.0.1:9000;fastcgi_read_timeout 2400;proxy_read_timeout 2400;|g" /etc/nginx/conf.d/default.conf

# Construir CSS do SASS
RUN vendor/scssphp/scssphp/bin/pscss --no-source-map --style=compressed sass/scss:sass/css

# Ajustar permissões para www-data
RUN chown -R www-data:www-data /app/app/runtime \
    && mkdir -p /app/assets && chown -R www-data:www-data /app/assets \
    && chown -R www-data:www-data /app/app/export \
    && chown -R www-data:www-data /app/app/import

# Garantir scripts executáveis
RUN chmod +x /usr/local/bin/docker-run.sh \
    && chown www-data:www-data /usr/local/bin/docker-run.sh

# Trocar para usuário não-root
USER www-data


# Expor porta se necessário
EXPOSE 8080

