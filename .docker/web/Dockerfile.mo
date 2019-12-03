FROM php:7.2-apache
LABEL maintainer="Victor Castro-Cintas <victor.castro-cintas@gfi.fr>"
{{#DOCKER_DEVBOX_COPY_CA_CERTIFICATES}}

COPY .ca-certificates/* /usr/local/share/ca-certificates/
RUN update-ca-certificates
{{/DOCKER_DEVBOX_COPY_CA_CERTIFICATES}}

RUN yes | pecl install xdebug \
&& echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
&& echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
&& echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

RUN docker-php-ext-install pdo pdo_mysql
# Allow apache to run server on port < 1024
RUN apt-get update -y && apt-get install -y libcap2-bin && rm -rf /var/lib/apt/lists/* \
&& setcap 'cap_net_bind_service=+ep' $(which apache2)

RUN apt-get update -y && apt-get install -y zlib1g-dev libzip-dev && rm -rf /var/lib/apt/lists/* && docker-php-ext-install zip

RUN a2enmod rewrite

ENV COMPOSER_HOME /composer
ENV PATH /composer/vendor/bin:$PATH
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN curl -fsSL -o /tmp/composer-setup.php https://getcomposer.org/installer \
&& curl -fsSL -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
&& php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" \
&& php /tmp/composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer --snapshot && rm -rf /tmp/composer-setup.php \
&& apt-get update -y && apt-get install -y git zip unzip && rm -rf /var/lib/apt/lists/* \
&& composer global require hirak/prestissimo


RUN apt-get update -y && apt-get install -y msmtp msmtp-mta && rm -rf /var/lib/apt/lists/* \
&& echo 'sendmail_path = "/usr/sbin/sendmail -t -i"' > /usr/local/etc/php/conf.d/mail.ini

RUN apt-get update -y
RUN apt-get install -y libgmp-dev re2c libmhash-dev libmcrypt-dev file
RUN ln -s /usr/include/x86_64-linux-gnu/gmp.h /usr/local/include/
RUN docker-php-ext-configure gmp
RUN docker-php-ext-install gmp

RUN mkdir -p "$COMPOSER_HOME/cache" \
&& chown -R www-data:www-data $COMPOSER_HOME \
&& chown -R www-data:www-data /var/www

# fixuid
ADD fixuid.tar.gz /usr/local/bin
RUN chown root:root /usr/local/bin/fixuid && \
    chmod 4755 /usr/local/bin/fixuid && \
    mkdir -p /etc/fixuid
COPY web/fixuid.yml /etc/fixuid/config.yml
USER www-data

VOLUME /composer/cache
