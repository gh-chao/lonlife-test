FROM node:latest
USER root
ENV USER root
ENV HOME /root
ENV SHELL bash
ENV TERM vt100
EXPOSE 8080

RUN echo "deb http://mirrors.aliyun.com/debian/ jessie main non-free contrib\n\
deb http://mirrors.aliyun.com/debian/ jessie-proposed-updates main non-free contrib\n\
deb-src http://mirrors.aliyun.com/debian/ jessie main non-free contrib\n\
deb-src http://mirrors.aliyun.com/debian/ jessie-proposed-updates main non-free contrib" > /etc/apt/sources.list

RUN apt-get update
RUN apt-get install zsh git curl -y
RUN curl -L https://raw.github.com/phpres/oh-my-zsh/master/tools/install.sh > /tmp/installohmyzsh.sh
RUN chmod a+x /tmp/installohmyzsh.sh
RUN git clone https://github.com/chjj/tty.js.git /ttyjs/
WORKDIR /ttyjs/
RUN npm install

RUN git clone https://github.com/lilocon/lonlife-test.git /lonlife-test/

# 安装composer
RUN wget -O /usr/local/bin/composer https://getcomposer.org/composer.phar
RUN chmod a+x /usr/local/bin/composer
RUN apt-get install mongodb-server

CMD /ttyjs/bin/tty.js

#todo 未完成