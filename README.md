# API COLORES
<p align="center" style="margin-top: 0"><img height="210" src="public/images/colors-logo.png"/></p>
<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href=""><img src="https://img.shields.io/badge/PHP v8.0.2-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP Version Used"></a>
<a href=""><img src="https://img.shields.io/badge/Laravel v9.5.1-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel Version Used"></a>
<a href=""><img src="https://img.shields.io/badge/Postman-FF6C37?logo=postman&logoColor=white" alt="Postman"></a>
</p>

## 🔴 Acerca de la API

API destinanda a **gestionar los colores estandarizados seleccionados para los productos**; 
para páginas web, nuevos diseños, flyers y redes sociales.

Formatos de Intercambio **JSON y XML**.

## 🟢 Tecnologías Utilizadas 🚀

  - PHP 8.0.2 : Programming Language
  - Laravel Framework 9.5.1 : PHP Framework 
  - Composer : Dependency Management for PHP
  - MySQL : Relational DBMS
  - PHPUnit: Unit Testing
  - Postman : API Testing


## [🔵 Deploy en Heroku](https://api-colors-dev.herokuapp.com/colors)
### [https://api-colors-dev.herokuapp.com/](https://api-colors-dev.herokuapp.com/colors)

## 🟣 Instalación de dependencias y run del proyecto
### Clonación del Repositorio
```shell
git clone https://github.com/MeloDev2111/API-Colores
```

### Instalación de Dependencias
```shell
composer install
```

### Creación del .env
```shell
mv .env.example .env
```

### Propiedades del .env
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=colors_DB
DB_USERNAME= <YOUR_USERNAME>
DB_PASSWORD= <YOUR_PASSWORD>
```

### Limpieza de Cache
```php
php artisan cache:clear
```

### Recargar las configuraciones de composer.json
```shell
composer dump-autoload
```

### Ejecutar las migraciones a la BD
```shell
php artisan migrate
```

### Generar nueva llave para artisan
```php
php artisan key:generate
```

### Run

```php
php artisan serve
```

Logs
```shell
Starting Laravel development server: http://127.0.0.1:8000
[Sat Mar 26 16:57:09 2022] PHP 8.0.2 Development Server (http://127.0.0.1:8000) started
```
URL
> [http://127.0.0.1:8000](http://127.0.0.1:8000)
    
## 🟡 Población la base de datos en desarrollo
```php
php artisan db:seed --class=ColorsTableSeeder
```
Se puede configurar la cantidad de registros a generar en el archivo
[ColorsTableSeeder](database/seeders/ColorsTableSeeder.php)

> Ruta : database/seeders/ColorsTableSeeder.php

Modificando la variable $no_fake_records = 50 a el valor deseado;
```php
$faker = \Faker\Factory::create();
$no_fake_records = 50;
```

## Run los Tests

```php
php artisan test
```


## [Colección de Endpoints en Postman 👀](public/API-Colores%20Collection.postman_collection.json)

> Ruta: public/API-Colores Collection.postman_collection.js

## [Link al Repositorio en GitHub ⚡](https://github.com/MeloDev2111/API-Colores)
