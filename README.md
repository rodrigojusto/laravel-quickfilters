# Laravel QuickFilters
Simple pipeline and reusable filters for eloquent query builder

## Usage
Apply all filters from 'App\User':
```php
$query = App\User::query();
$query->filter($request->all());
```
Apply only specified filters:
```php
$query->filter($request->all(), ['name', 'email']);
```

## Requirements
- PHP `7.1` or newer
- Laravel `5.8` or newer

## Installation
Via composer:
```bash
composer require avikuloff/laravel-quickfilters
```

If you are not using automatic package auto-discovery, then you need to add a service provider to `config/app.php`.
```php
'Avikuloff\QuickFilters\QuickFiltersServiceProvider'
```

## Configuration
Publish the configuration file with:
```bash
php artisan vendor:publish --provider="Avikuloff\QuickFilters\QuickFiltersServiceProvider" --tag="config"
```

## Creation and configuration
Use the following Artisan command to create a new filter class:
```bash
php artisan make:filter EmailFilter
```

By default, files are published in the `App\Filters` directory; you can change this by specifying the class name along with the namespace:
```bash
php artisan make:filter App\EmailFilter
```

Next, register the created filter.
Open the `config/quickfilters.php` and add a new filter group.
Specify the path to your model as the key of the array.
Then list all available filters for the group.

See example:
```php
    'groups' => [
        Path\To\Model::class => [
            'filterName' => 'Path\To\Filter',
        ],
        App\Employee::class => [
            'name' => 'App\Filters\NameFilter',
            'email' => 'App\Filters\EmailFilter',
        ],
        App\Employer::class => [
            'name' => 'App\Filters\NameFilter',
            'email' => 'App\Filters\EmailFilter',
            'registered' => 'App\Filters\DateFilter',
        ],
        ...
    ],
```
The created filter classes can be easily reused.

## IDE Support
Add the `_ide_macros.php` file with the following contents to the project root directory:
```php
<?php

namespace Illuminate\Database\Eloquent {
    /**
     * Class Builder
     * @package Illuminate\Database\Eloquent
     * @method Builder filter(array $data, array $filters = null)
     */
    class Builder
    {

    }
}
```

## License

The MIT License (MIT). See [License File](LICENSE) for more information.