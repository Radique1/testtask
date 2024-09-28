FROM nginx

#install applications
RUN apt-get -y update
RUN apt-get -y install nano

#prepare nginx
RUN rm /etc/nginx/conf.d/default.conf
COPY docker/nginx/etc/ /etc/nginx/conf.d/

#prepare project
COPY . /var/www/testtask/