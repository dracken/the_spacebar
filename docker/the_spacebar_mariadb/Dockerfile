##
#
# Generate Docker MariaDB/MySQL container
#
##
FROM mariadb

MAINTAINER dracken@drackenslair.com

#RUN groupadd -r swuser -g 433 && \
#       useradd -u 431 -r -g swuser -d <homedir> -s /sbin/nologin -c "Docker image user" swuser && \
#       chown -R swuser:swuser <homedir>

COPY my.cnf /etc/mysql/my.cnf

#USER swuser

RUN apt-get update -q && apt-get --assume-yes install vim nmap \
    && echo "alias ll='ls -hAlt --color=auto'" >> ~/.bashrc \
    && echo "export COMPOSER_MEMORY_LIMIT=-1" >> ~/.bashrc \
    && echo "export PS1=\"\[\033[36m\]\u\[\033[m\]@\[\033[32m\]\h:\[\033[33;1m\]\w\[\033[m\]\$ \"" >> ~/.bashrc \
    && echo "America/Denver" > /etc/timezone \
    && dpkg-reconfigure -f noninteractive tzdata