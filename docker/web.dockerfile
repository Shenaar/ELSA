FROM nginx

ADD docker/vhost.conf /etc/nginx/conf.d/default.conf
