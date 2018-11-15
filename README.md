![MIT Licence](https://img.shields.io/badge/licence-GPL-green.svg)
![Version](https://img.shields.io/badge/version-0.1-green.svg)

# GFI Coffee

> GFI Coffee is a simple web application made with VueJS.  
> This app aims to make Nespresso Pro coffee capsules orders easier at GFI.

## Frontend

### Build Setup

``` bash
# install dependencies
npm install

# serve with hot reload at localhost:8080
npm run dev

# build for production with minification
npm run build
```

## Backend

### Build Setup

``` bash
# install dependencies
composer install

# build MySQL database from entities
php bin/console doctrine:database:create
php bin/console doctrine:schema:create

# update database structure
php bin/console doctrine:schema:update --force

# Load fixtures
php bin/console doctrine:fixtures:load

# serve API at localhost:3000
php bin/console server:run
```

### Environnement de développement

Générer les clés

``` bash
openssl genrsa -out config/jwt/private.pem 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```