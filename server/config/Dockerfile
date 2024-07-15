# 从ubuntu镜像中构建应用
FROM ubuntu:latest

# 设置镜像的制作者
MAINTAINER congjiang

# 安装nginx + php-fpm7.4
RUN apt-get update && apt-get upgrade && apt-get install -y nginx php8.0-fpm vim

# 上一条命令安装nginx和fpm
# nginx 默认安装路径 /etc/nginx/
# php-fpm 默认安装路径 /etc/php/fpm/
# nginx配置文件存放目录 /etc/nginx/conf.d/
# nginx默认项目路径 /var/www/html/

# 在上传目录之前，可以先删除旧项目代码
 RUN ["rm", "-rf", "/var/www/mahjong/"]

# 项目文件上传, 把当前目录下的所有文件上传到/var/www/project_name目录下
COPY ./ /var/www/mahjong/

# 把当前项目的nginx.conf配置文件上传到/etc/nginx/conf.d/目录下
COPY ./config/nginx.conf /etc/nginx/conf.d/mahjong.conf



# 容器运行时启动应用
CMD php-fpm8.0 -D && nginx -g 'daemon off;'