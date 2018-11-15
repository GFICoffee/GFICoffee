FROM php:7.2-apache
LABEL maintainer="Victor Castro-Cintas <victor.castro.cintas@gmail.com>"

{{#DOCKER_DEVBOX_CA_CERTIFICATES}}
COPY .ca-certificates/* /usr/local/share/ca-certificates/
RUN update-ca-certificates
{{/DOCKER_DEVBOX_CA_CERTIFICATES}}

# GD
RUN apt-get update -y && apt-get install -y libpng-dev libfreetype6-dev libjpeg62-turbo-dev && rm -rf /var/lib/apt/lists/* \
&& docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
&& docker-php-ext-install gd

# OPCache
RUN docker-php-ext-install opcache

# LDAP
RUN apt-get update -y && apt-get install -y libldap2-dev && rm -rf /var/lib/apt/lists/* \
&& docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu \
&& docker-php-ext-install ldap

# Zip
RUN docker-php-ext-install zip

# PDO mysql
RUN docker-php-ext-install pdo pdo_mysql

RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

# Enable Apache modules
RUN a2enmod rewrite
RUN a2enmod proxy
RUN a2enmod proxy_http

# === Composer ===
# Time Zone
# RUN echo "date.timezone=${PHP_TIMEZONE:-UTC}" > $PHP_INI_DIR/conf.d/date_timezone.ini

# Disable Populating Raw POST Data
# Not needed when moving to PHP 7.
# http://php.net/manual/en/ini.core.php#ini.always-populate-raw-post-data
# RUN echo "always_populate_raw_post_data=-1" > $PHP_INI_DIR/conf.d/always_populate_raw_post_data.ini

# Register the COMPOSER_HOME environment variable
ENV COMPOSER_HOME /composer

# Add global binary directory to PATH and make sure to re-export it
ENV PATH /composer/vendor/bin:$PATH

# Allow Composer to be run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Setup the Composer installer
RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
  && curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
  && php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }"

# Install Composer
RUN php /tmp/composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer --snapshot && rm -rf /tmp/composer-setup.php
RUN apt-get update -y && apt-get install -y git zip unzip && rm -rf /var/lib/apt/lists/*

# Github token
RUN composer config -g github-oauth.github.com db14c113decece351f1e20cf012ce0b3c8612cd5

# Install prestissimo
RUN composer global require hirak/prestissimo

# MySQL Client for drush commands
RUN apt-get update -y && apt-get install -y mysql-client && rm -rf /var/lib/apt/lists/*

# wkhtmltopdf
RUN curl -LO https://github.com/wkhtmltopdf/wkhtmltopdf/releases/download/0.12.4/wkhtmltox-0.12.4_linux-generic-amd64.tar.xz
RUN tar -xf wkhtmltox-0.12.4_linux-generic-amd64.tar.xz
RUN cp wkhtmltox/bin/* /usr/bin/
RUN rm -Rf wkhtmltox
RUN apt-get update -y && apt-get install -y libxrender1 libfontconfig1 && rm -rf /var/lib/apt/lists/*

# Configure PHP mail with mailcatcher
RUN apt-get update -y && apt-get install -y ssmtp && rm -rf /var/lib/apt/lists/*
RUN echo "FromLineOverride=YES" >> /etc/ssmtp/ssmtp.conf && \
  echo "mailhub=mailcatcher" >> /etc/ssmtp/ssmtp.conf && \
  echo 'sendmail_path = "/usr/sbin/ssmtp -t"' > /usr/local/etc/php/conf.d/mail.ini

RUN echo "memory_limit = -1" > /usr/local/etc/php/conf.d/memory.ini

RUN echo "upload_max_filesize = 10M" > /usr/local/etc/php/conf.d/upload.ini
RUN echo "post_max_size = 10M" >> /usr/local/etc/php/conf.d/upload.ini

RUN echo "error_log = /dev/stderr" > /usr/local/etc/php/conf.d/logs.ini
RUN echo "log_errors = On" >> /usr/local/etc/php/conf.d/logs.ini

RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/logs.ini

# Fix write permissions on Linux/Mac
RUN usermod -u ${HOST_UID:-1000} www-data
RUN groupmod -g ${HOST_GID:-1000} www-data
RUN chown -R www-data:www-data $COMPOSER_HOME

# Add composer cache to a volume
RUN mkdir -p /composer/cache && chown -R www-data:www-data /composer/cache
VOLUME /composer/cache
