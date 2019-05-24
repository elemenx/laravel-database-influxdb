## Installation

### Step 1: Composer

Via Composer command line:

```bash
$ composer require elemenx/laravel-database-influxdb
```

Or add the package to your `composer.json`:

```json
{
    "require": {
        "elemenx/laravel-database-influxdb": "0.1.*"
    }
}
```

### Step 2: Enable the package (Optional)

This package implements Laravel 5.5's auto-discovery feature. After you install it the package provider and facade are added automatically.

If you would like to declare the provider and/or alias explicitly, then add the service provider to your `config/app.php`:

```php
'providers' => [
    ElemenX\Database\InfluxDb\InfluxDbServiceProvider::class,
];
```

And then add the alias to your `config/app.php`:

```php
'aliases' => [
    'InfluxDb' => ElemenX\Database\InfluxDb\InfluxDbFacade::class,
];
```

### Step 3: Configure the package

Publish the package config file:

```bash
$ php artisan vendor:publish --provider="ElemenX\Database\InfluxDb\InfluxDbServiceProvider"
```

You may now place your defaults in `config/influxdb.php`.

## Full .env Example

To override values in `config/influxdb.php`, simply add the following to your .env file:

```bash
INFLUXDB_PROTOCOL=https
INFLUXDB_USER=my-influxdb-user
INFLUXDB_PASS=my-influxdb-pass
INFLUXDB_HOST=my-influxdb.server
```

## References

- [influxdata/influxdb-php](https://github.com/influxdata/influxdb-php)

## Credits

This is a fork of [pdffiller/laravel-influx-provider](https://github.com/pdffiller/laravel-influx-provider).

- [pdffiller/laravel-influx-provider Contributors](https://github.com/pdffiller/laravel-influx-provider/graphs/contributors)
- [austinheap/laravel-influx-provider Contributors](https://github.com/austinheap/laravel-influx-provider/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
