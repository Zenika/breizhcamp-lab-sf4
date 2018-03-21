FROM debian:jessie

RUN apt-get update && apt-get install -y \
    nginx

ADD docker/nginx/nginx.conf /etc/nginx/
ADD docker/nginx/symfony.conf /etc/nginx/sites-available/
ADD docker/nginx/api.conf /etc/nginx/sites-available/

RUN ln -s /etc/nginx/sites-available/symfony.conf /etc/nginx/sites-enabled/symfony
RUN ln -s /etc/nginx/sites-available/api.conf /etc/nginx/sites-enabled/api
RUN rm /etc/nginx/sites-enabled/default

RUN echo "upstream php-upstream { server front:9000; }" > /etc/nginx/conf.d/upstream.conf
RUN echo "upstream api-upstream { server api:9000; }" >> /etc/nginx/conf.d/upstream.conf
RUN usermod -u 1000 www-data

CMD ["nginx"]

EXPOSE 80
EXPOSE 443
