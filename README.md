My Fruit List
=============

My Fruit List is a web app designed for fruit enthusiasts who are passionate about tracking their favorite fruits and
staying informed about their nutritional content. It provides a simple yet powerful interface to search, filter, and
compare various fruits and their nutritional facts.

Features
--------

- Favorite Fruits: Save up to 10 favorite fruits and access them anytime.
- Filter & Search: Find fruits by name or family and explore their nutritional content.
- Nutrition Facts: Discover the nutritional facts of your favorite fruits and compare them to make informed decisions
  about your diet.

Requirements
------------

One of the following sets:

- Docker
- PHP 8.1, Composer, Node.js 18+, Yarn

Quick start with Docker
-----------------------
[![GitHub Actions](https://github.com/bitpetr/my-fruit-list/actions/workflows/docker-publish.yml/badge.svg)](https://github.com/bitpetr/my-fruit-list/actions/workflows/docker-publish.yml)

1. Pull and run the image:
    + `docker run -d -p 8080:8080 --name my-fruit-list-app ghcr.io/bitpetr/my-fruit-list:master`
2. Update the fruit database:
    + `docker exec my-fruit-list-app php bin/console app:fruit:update`
3. Open http://localhost:8080/ in your browser and enjoy!

To remove:

+ `docker stop my-fruit-list-app`
+ `docker rm -v my-fruit-list-app`

Manual Setup
------------

### Setup
1. Clone the repository:
    + `git clone https://github.com/bitpetr/my-fruit-list.git`
    + `cd my-fruit-list`

2. Install PHP dependencies:
    + `composer install`

3. Configure your `.env.local` file with the appropriate settings for your environment.

4. Create the database and schema:
    + `bin/console doctrine:database:create`
    + `bin/console doctrine:schema:create`

5. Install JavaScript dependencies:
    + `yarn install`

6. Build the front-end assets:
    + `yarn build`

### Running the App
1. Update the fruit database:
   + `bin/console app:fruit:update`
2. Run the dev server:
    + `php -S 0.0.0.0:8080 -t public`
3. Open your web browser and navigate to http://localhost:8080 to start using My Fruit List.

### [optional] Running the tests
1. Create the test database and schema:
   + `bin/console doctrine:database:create -e test`
   + `bin/console doctrine:schema:create -e test`
2. Run PHPUnit:
   + `bin/phpunit`

Data Source
-----------

The data used in this application is provided by [Fruityvice](https://www.fruityvice.com/).

License
-------

This project is open-source and available under the [MIT License](https://chat.openai.com/chat/LICENSE).
