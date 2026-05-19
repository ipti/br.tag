FROM php:8.3-fpm-alpine

WORKDIR /app
USER root

ARG LOCAL_UID=1000
ARG LOCAL_GID=1000

# Instalar nginx e dependências do sistema
RUN apk add --no-cache \
    nginx \
    curl \
    unzip \
    git \
    supervisor \
    openssl

# Instalar extensões PHP necessárias para Yii2
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mysqli \
        gd \
        zip \
        intl \
        mbstring \
        xml \
        opcache \
        curl \
        bcmath \
        sockets

# Ajustar UID/GID do www-data
RUN sed -i "s/^www-data:x:[0-9]*:/www-data:x:${LOCAL_GID}:/" /etc/group && \
    sed -i "s/^www-data:x:[0-9]*:[0-9]*:/www-data:x:${LOCAL_UID}:${LOCAL_GID}:/" /etc/passwd

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Configuração do nginx
RUN mkdir -p /var/run/nginx /var/log/nginx /etc/nginx/ssl

# Gerar certificado SSL autoassinado
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/nginx/ssl/nginx-selfsigned.key \
    -out /etc/nginx/ssl/nginx-selfsigned.crt \
    -subj "/C=BR/ST=SP/L=SP/O=TAG/CN=localhost"

# Configuração do nginx.conf
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Configuração do supervisor (gerencia nginx + php-fpm juntos)
COPY docker/supervisor/supervisord.conf /etc/supervisord.conf

# Copiar aplicação
COPY . /app

# Criar diretórios necessários
RUN mkdir -p \
    /app/app/runtime \
    /app/assets \
    /app/app/export \
    /app/app/import

# Ajustar permissões
RUN chown -R www-data:www-data /app \
    && chmod -R 775 /app/app/runtime \
    && chmod -R 775 /app/assets \
    && chmod -R 775 /app/app/export \
    && chmod -R 775 /app/app/import \
    && chown -R www-data:www-data /var/log/nginx \
    && chown -R www-data:www-data /var/run/nginx

EXPOSE 80 443

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
