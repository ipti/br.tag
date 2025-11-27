FROM ipti/yii2:8.3-fpm

# Definir o diretório principal
WORKDIR /app
USER root

# Copiar arquivos para o contêiner no diretório raiz da aplicação
COPY . /app

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Criar diretórios e ajustar arquivos de configuração
RUN sed -i "s|/app/web|/app|g" /etc/nginx/conf.d/default.conf \
    && sed -i "s|memory_limit=128M|memory_limit=512M|g" /usr/local/etc/php/conf.d/base.ini \
    && sed -i "s|fastcgi_pass 127.0.0.1:9000;|fastcgi_pass 127.0.0.1:9000;fastcgi_read_timeout 2400;proxy_read_timeout 2400;|g" /etc/nginx/conf.d/default.conf

# Voltar para o diretório principal (/app) e executar comandos adicionais
WORKDIR /app
RUN composer update \
    && composer update --no-plugins \
    && composer install

# Conctruir arquivos css do sass
# RUN vendor/scssphp/scssphp/bin/pscss --no-source-map --style=compressed sass/scss:sass/css

# Ajustar permissões para o usuário www-data
RUN chown -R www-data:www-data /app/app/runtime \
    && mkdir -p /app/assets && chown -R www-data:www-data /app/assets \
    && chown -R www-data:www-data /app/app/export \
    && chown -R www-data:www-data /app/app/import


RUN chmod +x /usr/local/bin/docker-run.sh \
    && chown www-data:www-data /usr/local/bin/docker-run.sh

# Mudar para o usuário não-root
USER www-data
