##
#
# Generate Nginx Docker container
#
##
FROM nginx

MAINTAINER dracken@drackenslair.com

#RUN groupadd -r swuser -g 433 && \
#       useradd -u 431 -r -g swuser -d <homedir> -s /sbin/nologin -c "Docker image user" swuser && \
#       chown -R swuser:swuser <homedir>

#USER swuser

COPY nginx.conf /etc/nginx/nginx.conf
COPY mime.types /etc/nginx/mime.types

RUN echo "alias ll='ls -hAlt --color=auto'" >> ~/.bashrc \
    && rm -rf /var/www/api/var/logs \
    && rm -rf /var/www/api/var/cache \
    && mkdir -p /var/www/api/var/logs \
    && mkdir -p /var/www/api/var/cache \
    && chmod -R 777 /var/www/api/var/cache /var/www/api/var/logs \
    && chown www-data /var/www/api/var/cache /var/www/api/var/logs \
    && echo "America/Denver" > /etc/timezone \
    && dpkg-reconfigure -f noninteractive tzdata