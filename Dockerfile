FROM nginx:latest

WORKDIR /var/www/html

COPY app/* /var/www/html/
