ARG XDEBUG_VERSION="xdebug-2.9.0"

FROM php:7.4-apache

RUN docker-php-ext-install mysqli

RUN pecl install ${XDEBUG_VERSION}