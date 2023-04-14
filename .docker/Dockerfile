# Use image which contains apache with php
FROM php:7.4-apache
RUN apt-get update && apt-get upgrade -y
# Install packages needed to install php extensions
RUN apt-get install git zlib1g-dev libxml2-dev libzip-dev zip unzip -y
# Install PHP extensions
RUN docker-php-ext-install zip intl mysqli pdo pdo_mysql opcache
# Install XDEBUG
RUN pecl install xdebug-2.9.8
# Enable XDEBUG
RUN docker-php-ext-enable xdebug
# Install composer command
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
# Install symfony command
RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
# Set umask to 0000 (newly created files will have 777 permissions)
RUN echo "umask 0000" >> /root/.bashrc
# Create custom commands
RUN echo 'alias console="php /var/www/html/bin/console"' >> ~/.bashrc
