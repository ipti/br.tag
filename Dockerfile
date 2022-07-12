FROM ipti/yii2
COPY . /app
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
RUN sed -i "s|/app/web|/app|g" /etc/nginx/conf.d/default.conf
RUN sed -i "s|fastcgi_pass 127.0.0.1:9000;|fastcgi_pass 127.0.0.1:9000;fastcgi_read_timeout 2400;proxy_read_timeout 2400;|g" /etc/nginx/conf.d/default.conf
RUN chmod 777 /usr/local/bin/docker-run.sh
RUN chown -R www-data:www-data /app \
&& chown -R www-data:www-data /app/assets
