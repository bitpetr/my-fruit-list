FROM php:8.2-cli-alpine as my-fruit-list-app
RUN apk add --update nodejs yarn
ADD https://github.com/mlocati/docker-php-extension-installer/releases/download/2.1.10/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && install-php-extensions intl gmp mbstring curl json pdo xml iconv @composer
RUN mkdir -p /usr/src/api
VOLUME /usr/src/app/var
COPY . /usr/src/app
WORKDIR /usr/src/app
RUN composer install --optimize-autoloader
RUN php bin/console doctrine:schema:create -e test
RUN php bin/phpunit
RUN php bin/console doctrine:schema:create
RUN yarn install
RUN yarn build
WORKDIR /usr/src/app/public
CMD ["-S", "0.0.0.0:8080"]
EXPOSE 8080