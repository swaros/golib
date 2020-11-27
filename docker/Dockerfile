FROM php:8.0-rc

#RUN a2enmod rewrite

RUN docker-php-ext-install pdo pdo_mysql

#RUN echo "xdebug.remote_enable=1" > /usr/local/etc/php/php.ini
#RUN echo "xdebug.remote_host=192.168.178.20" >> /usr/local/etc/php/php.ini

RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=192.168.178.20" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini






