FROM php:7.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN apt-get update && apt-get install -y libcurl4-openssl-dev
RUN docker-php-ext-install curl

RUN docker-php-ext-install sockets

RUN a2enmod rewrite
