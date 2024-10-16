FROM php:7.4-fpm-alpine

WORKDIR /app
# Add packages to alpine
RUN apk update && apk add \
    libstdc++ unzip zip bash nano pixman tiff giflib libpng libcrypto1.1 lcms2 libjpeg-turbo libgomp libavif aom libcurl msttcorefonts-installer && \
    update-ms-fonts fc-cache -f

# PHP extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions intl gd gettext zip bz2 bcmath exif opcache xsl 
# bcmath calendar ctype curl dba dl_test dom enchant exif ffi fileinfo filter ftp gd gettext gmp hash iconv imap intl json ldap mbstring mysqli oci8 odbc opcache pcntl pdo pdo_dblib pdo_firebird pdo_mysql pdo_oci pdo_odbc pdo_pgsql pdo_sqlite pgsql phar posix pspell random readline reflection session shmop simplexml snmp soap sockets sodium spl standard sysvmsg sysvsem sysvshm tidy tokenizer xml xmlreader xmlwriter xsl zend_test zip

WORKDIR /opt


WORKDIR /app

COPY php.ini /usr/local/etc/php/php.ini 

RUN addgroup -g 1000 appgroup && adduser -u 1000 appuser -D -G appgroup
USER appuser