FROM ubuntu:16.04

LABEL maintainer="Hasan Hasibul"

RUN apt-get update \
    && apt-get install -y locales \
    && locale-gen en_US.UTF-8

ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

WORKDIR /var/www/html

RUN apt-get install -y nginx curl zip unzip git vim software-properties-common supervisor sqlite3 \
    && add-apt-repository -y ppa:tomvlk/php-archive \
    && apt-get update \
    && apt-get install -y php7.1-common php7.1 php7.1-cli php7.1-fpm php7.1-json php7.1-opcache php7.1-readline \
    && apt-get install -y php7.1-fpm php7.1-cli php7.1-mcrypt php7.1-gd php7.1-mysql \
       php7.1-pgsql php7.1-imap php7.1-mbstring php7.1-xml php7.1-curl \
       php7.1-imagick php7.1-zip php7.1-bcmath php7.1-sqlite3 php7.1-xdebug php7.1-mongodb python3-pip \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && mkdir /run/php \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && echo "daemon off;" >> /etc/nginx/nginx.conf

# Allows docker to cache installed dependencies between builds
COPY ./requirements.txt requirements.txt
RUN pip3 install -r requirements.txt

RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

COPY default /etc/nginx/sites-available/default
COPY php-fpm.conf /etc/php/7.1/fpm/php-fpm.conf
COPY xdebug.ini /etc/php/7.1/mods-available/xdebug.ini

COPY permission_fix /usr/local/bin/permission_fix
RUN chmod +x /usr/local/bin/permission_fix

COPY realtime_ws_server.py /usr/local/bin/realtime_ws_server.py
RUN chmod +x /usr/local/bin/realtime_ws_server.py

COPY start-container /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

EXPOSE 80

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["start-container"]