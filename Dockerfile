FROM php:8.2-cli-alpine as app
ADD https://github.com/mlocati/docker-php-extension-installer/releases/download/2.1.10/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && install-php-extensions intl gmp mbstring curl json pdo xml iconv @composer
RUN mkdir -p /usr/src/api
VOLUME /usr/src/app/var
COPY . /usr/src/app
WORKDIR /usr/src/app
RUN composer install --optimize-autoloader
RUN php bin/console doctrine:schema:create -e test
RUN php bin/phpunit
ENV APP_ENV=prod
RUN php bin/console doctrine:schema:create

FROM node:18-alpine as node
COPY --from=app /usr/src/app /usr/src/app
WORKDIR /usr/src/app
RUN yarn install
RUN yarn build

FROM app
COPY --from=node /usr/src/app/public/build /usr/src/app/public/build
CMD ["-S", "0.0.0.0:8080", "-t", "public"]
EXPOSE 8080