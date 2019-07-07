FROM ipti/yii2
COPY . /app
RUN mkdir assets
RUN chmod 777 /usr/local/bin/docker-run.sh
RUN chown -R www-data:www-data /app/app/runtime \
&& chown -R www-data:www-data /app/assets
