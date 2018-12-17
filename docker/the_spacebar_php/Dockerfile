##
#
# Generate PHP Docker Container
#
##
FROM php:7.2-fpm

MAINTAINER derek.boerger@mcnhealthcare.com

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /tmp
ENV COMPOSER_VERSION 1.7.3

#RUN groupadd -r swuser -g 433 && \
#	useradd -u 431 -r -g swuser -d <homedir> -s /sbin/nologin -c "Docker image user" swuser && \
#	chown -R swuser:swuser <homedir>

#ADD www.conf /etc/php/7.1/fpm/pool.d/www.conf
#ADD php-fpm.conf /etc/php/7.1/fpm/php-fpm.conf

#USER swuser

RUN echo "alias ll='ls -hAlt --color=auto'" >> ~/.bashrc \
	&& echo "America/Denver" > /etc/timezone \
	&& dpkg-reconfigure -f noninteractive tzdata \
	&& apt -yqq update \
	&& apt -yqq install libxml2-dev \
    && apt-get install -yqq --no-install-recommends git zip unzip zlib1g-dev \ 
	&& docker-php-ext-install pdo_mysql > /dev/null \
	&& docker-php-ext-install xml > /dev/null \
	&& docker-php-ext-install zip > /dev/null
	
# Install XDebug
RUN pecl config-set php_ini /usr/local/etc/php/php.ini-development \
    && pecl install xdebug \ 
#    && echo 'zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20170718/xdebug.so' > /usr/local/etc/php/php.ini-development
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

# Install Composer
RUN curl --silent --show-error https://getcomposer.org/installer | php && \
	mv -f composer.phar /usr/local/bin/composer 
	
COPY composer.auth.json /root/.composer/auth.json
#&& \
# ./composer update


WORKDIR /var/www/api

# Copy over shell scripts
COPY doctrine_setup.sh /tmp/doctrine_setup.sh
COPY composer_setup.sh /tmp/composer_setup.sh
COPY composer.auth.json /root/.composer/auth.json

# Run the Doctrine Setup
RUN chmod 755 /tmp/doctrine_setup.sh \
	&& /tmp/doctrine_setup.sh 
	
#Run the Composer Setup
RUN chmod 755 /tmp/composer_setup.sh \
	&& /tmp/composer_setup.sh
	
