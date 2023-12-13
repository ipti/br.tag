FROM ipti/yii2:7.4-fpm

COPY --chown=www-data:www-data . /app
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /app/app
RUN composer update --no-plugins
RUN composer install

WORKDIR /app
RUN composer update --no-plugins
RUN composer install

ENV MUSL_LOCALE_DEPS cmake make musl-dev gcc gettext-dev libintl
ENV MUSL_LOCPATH /usr/share/i18n/locales/musl

RUN apk add --no-cache \
    $MUSL_LOCALE_DEPS \
    && wget https://gitlab.com/rilian-la-te/musl-locales/-/archive/master/musl-locales-master.zip \
    && unzip musl-locales-master.zip \
    && cd musl-locales-master \
    && cmake -DLOCALE_PROFILE=OFF -D CMAKE_INSTALL_PREFIX:PATH=/usr . && make && make install \
    && cd .. && rm -r musl-locales-master

RUN mkdir assets

RUN sed -i "s|/app/web|/app|g" /etc/nginx/conf.d/default.conf \
    && sed -i "s|memory_limit=128M|memory_limit=512M|g" /usr/local/etc/php/conf.d/base.ini \
    && sed -i "s|fastcgi_pass 127.0.0.1:9000;|fastcgi_pass 127.0.0.1:9000;fastcgi_read_timeout 120s;proxy_read_timeout 120s;|g" /etc/nginx/conf.d/default.conf \
    && chown -R www-data:www-data /app/assets \
    && chmod +x /usr/local/bin/docker-run.sh

ENTRYPOINT [ "/usr/local/bin/docker-run.sh" ]

USER www-data
