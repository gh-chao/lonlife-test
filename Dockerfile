FROM ubuntu:14.04
USER root
ENV USER root
ENV HOME /root
ENV SHELL bash
ENV TERM vt100
EXPOSE 3000
EXPOSE 8000
VOLUME ["/root"]

# aliyun mirror
RUN echo "deb http://mirrors.aliyun.com/ubuntu/ trusty main restricted universe multiverse\n\
deb http://mirrors.aliyun.com/ubuntu/ trusty-security main restricted universe multiverse\n\
deb http://mirrors.aliyun.com/ubuntu/ trusty-updates main restricted universe multiverse\n\
deb http://mirrors.aliyun.com/ubuntu/ trusty-proposed main restricted UNIVERSE multiverse\n\
deb http://mirrors.aliyun.com/ubuntu/ trusty-backports main restricted universe multiverse\n\
deb-src http://mirrors.aliyun.com/ubuntu/ trusty main restricted universe multiverse\n\
deb-src http://mirrors.aliyun.com/ubuntu/ trusty-security main restricted universe multiverse\n\
deb-src http://mirrors.aliyun.com/ubuntu/ trusty-updates main restricted universe multiverse\n\
deb-src http://mirrors.aliyun.com/ubuntu/ trusty-proposed main restricted universe multiverse\n\
deb-src http://mirrors.aliyun.com/ubuntu/ trusty-backports main restricted universe multiverse" > /etc/apt/sources.list

RUN apt-get update

# install node
RUN apt-get install python build-essential wget xz-utils -y --force-yes
RUN wget https://nodejs.org/dist/v4.4.3/node-v4.4.3-linux-x64.tar.xz
RUN xz -d node-v4.4.3-linux-x64.tar.xz
RUN tar -xvf node-v4.4.3-linux-x64.tar
RUN mv node-v4.4.3-linux-x64 /usr/local
RUN ln -s /usr/local/node-v4.4.3-linux-x64/bin/* /usr/local/bin/
RUN rm node-v4.4.3-linux-x64.tar

# install web tty
RUN apt-get install git -y --force-yes
RUN git clone https://github.com/phpres/web-tty.git /webtty/
WORKDIR /webtty/
RUN npm install
WORKDIR /root/

# install php
RUN apt-get install php5-cli php5-curl php5-apcu php5-gd php5-intl php5-json php5-mcrypt php5-mysqlnd php5-mongo -y --force-yes
# install composer
RUN wget -O /usr/local/bin/composer https://getcomposer.org/composer.phar
RUN chmod a+x /usr/local/bin/composer


RUN git clone https://github.com/lilocon/lonlife-test.git /lonlife-test/

CMD node /webtty/server.js & && php -S 0.0.0.0 -t /lonlife-test/web/