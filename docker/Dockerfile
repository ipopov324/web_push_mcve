FROM php:7.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    fonts-liberation \
    libicu-dev \
    libpq-dev \
    locales \
    locales-all \
    wkhtmltopdf \
    xvfb \
    zlib1g-dev

RUN fc-cache -f -v

RUN docker-php-ext-install \
    bcmath \
    intl \
    opcache \
    zip

ENV COMPOSER_ALLOW_SUPERUSER 1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer; \
    chmod +x /usr/local/bin/composer

RUN usermod -s /bin/bash www-data; \
    touch /var/www/.bash_history; \
    chmod 777 /var/www/.bash_history

RUN chown -R www-data:www-data /usr/local/etc/php/; \
    chmod -R 775 /usr/local/etc/php/

ENV PROMPT_COMMAND="history -a"
ENV HISTTIMEFORMAT="%h %d %H:%M:%S "
ENV HISTFILESIZE=20000
ENV LC_ALL en_US.UTF-8
ENV LANG en_US.UTF-8
ENV LANGUAGE en_US.UTF-8
