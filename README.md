<p align="center"><img src="http//zsong.me/redis-manager/vendor/redis-manager/img/logo.svg" alt="redis-manager"></p>

<p align="center">
    <a href="https://styleci.io/repos/114085132">
        <img src="https://styleci.io/repos/114085132/shield" alt="StyleCI">
    </a>
    <a href="https://packagist.org/packages/encore/redis-manager">
        <img src="https://img.shields.io/packagist/l/encore/redis-manager.svg?maxAge=2592000&&style=flat-square" alt="Packagist">
    </a>
    <a href="https://packagist.org/packages/encore/redis-manager">
        <img src="https://img.shields.io/packagist/dt/encore/redis-manager.svg?style=flat-square" alt="Total Downloads">
    </a>
</div>

`Redis-manager` gives your laravel application a redis web administration interface that allows you to easily manipulate the most commonly used data types for redis (strings, hashes, lists, sets, sorted sets).

It also provides a web-style command-line tool that works like redis-cli that can run most of the redis commands.

`Redis-manager` allows you to easily monitor several redis system status, including memory usage, cpu usage, and the throughput of each command.

> redis-manager reads laravel's redis configuration located in the `config/database.php`

# Installation

You may use Composer to install Redis-manager into your Laravel project:

```shell
composer require encore/redis-manager

```

After installing `redis-manager`, publish its assets using the vendor:publish Artisan command:

```shell
php artisan vendor:publish --provider="Encore\RedisManager\RedisManagerServiceProvider"
```

After installation, open `http://your-server/redis-manager` to access `redis-manager`.

## Configuration

The config file was published at `config/redis-manager.php`, and the default contents of the configuration: 
```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Redis Manager Base Path
    |--------------------------------------------------------------------------
    |
    | Base path for Redis Manager
    |
    */

    'base_path' => 'redis-manager',

    /*
    |--------------------------------------------------------------------------
    | Redis Manager Middleware
    |--------------------------------------------------------------------------
    |
    | The Redis Manager's route middleware.
    |
    */

    'middleware' => [],

    /*
    |--------------------------------------------------------------------------
    | Redis Manager Results Per Page
    |--------------------------------------------------------------------------
    |
    | Here you can configure for the number of results will show in the
    | Redis Manager search page.
    |
    */

    'results_per_page' => 50,

    /*
    |--------------------------------------------------------------------------
    | Redis Manager Disable Commands
    |--------------------------------------------------------------------------
    |
    | The commands listed here was disabled when you use Redis Manager Console
    | to run commands. Feel free to add commands here which you do not want
    | users to use.
    |
    */

    'disable_commands' => [
        'flushdb'
    ]
];

```

## Authentication

By default, you will only be able to access `redis-manager` in the `local` environment. To define a more specific access policy for it, you should use the `RedisManager::auth` method. The auth method accepts a callback which should return `true` or `false`, indicating whether the user should have access to `redis-manager`:

```php
RedisManager::auth(function ($request) {
    // return true / false;
});
```

## License

`Redis manager` is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
