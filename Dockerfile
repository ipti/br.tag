FROM ipti/yii2
COPY . /app
RUN mkdir assets
RUN mkdir app/runtime
RUN chown -R www-data:www-data /app/app/runtime \
&& chown -R www-data:www-data /app/assets
