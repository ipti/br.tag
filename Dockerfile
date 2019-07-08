FROM ipti/yii2
COPY . /app
RUN mkdir assets
RUN sed -i "s|/app/web|/app|g" /etc/nginx/conf.d/default.conf
RUN chmod 777 /usr/local/bin/docker-run.sh
RUN chown -R www-data:www-data /app \
&& chown -R www-data:www-data /app/assets
