# Laravel QuickFilters
The package allows to quickly build a query to the database.

## Basic example
```php
// GET /jobs?title=Ingeener&salary=100000&job_type=1&city=new-york,category= ...

Class JobController
...
public function search(Request $request)
{
    // via Eloquent and macro
    $jobs = Job::query()
        ->filter($request->all())
        ->get();

    // or via DB facade and macro
    $jobs = DB::table('jobs')
        ->filter($request->all())
        ->get();

    // or via QuickFilters instance
    $jobs = (new QuickFilters(Job::query()))
        ->apply($request->all())
        ->get();
    ...
}
```

1. [Requirements](#requirements)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Usage](#usage)
   - [Make filters](#make-filters)
   - [Register filters](#register-filters)
   - [Available methods](#available-methods)
5. [License](#license)

## Requirements
- PHP `7.2` or newer
- Laravel `5.6` or newer

## Installation
Via composer:
```bash
composer require avikuloff/laravel-quickfilters
```
If not using package auto-discovery, then you need to add a service provider to `config/app.php`.
```php
Avikuloff\QuickFilters\QuickFiltersServiceProvider::class
```
Next, publish the configuration file:
```bash
php artisan vendor:publish --provider="Avikuloff\QuickFilters\QuickFiltersServiceProvider" --tag="config"
```

## Configuration
Change default namespace for filter classes:
```php
'namespace' => 'App\Filters',
```

## Usage
### Make filters
Use the following Artisan command to create a new filter classes:
```bash
php artisan make:filter NameFilter EmailFilter CreatedAfter App\CustomNamespace\EmailFilter
```
By default, the files are published in the `App\Filters` directory, change them to suit your requirements.

Example created filter `EmailFilter`:
```php
namespace App\Filters;
...
public function handle($builder, Closure $next, Collection $collection)
{
    if ($collection->has('email')) {
        $builder->where('email', "%{$collection->get('email')}%");
    }

    return $next($builder);
}
```
If Eloquent-specific methods (*e.g. relationships*), are called in the filter, then the class must implement the `Avikuloff\QuickFilters\Contracts\EloquentFilterContract` interface.

### Register filters
Publish the configuration file:
```bash
php artisan vendor:publish --provider="Avikuloff\QuickFilters\QuickFiltersServiceProvider" --tag="config"
```

Open the `config/quickfilters.php` and add a new filter group.
The group name must match the database table.

Example:
```php
'groups' => [
    'jobs' => [
        Path\To\Filter::class,
    ],
    'employees' => [
        App\Filters\NameFilter::class,
        App\Filters\EmailFilter::class,
    ],
    'employers' => [
        App\Filters\NameFilter',
        App\Filters\EmailFilter',
        App\Filters\CreatedAfterFilter',
    ],
    ...
],
```
The created filter classes can be easily reused.

### Available methods
Macro `filter(array $data, array $filters = null)` available for `Illuminate\Database\Eloquent\Builder` and `Illuminate\Database\Query\Builder`:
```php
$query = Job::query()->filter($data);
$query = DB::table('jobs')->filter($data);
```
`apply(array $data, array $filters = null)` applies all filters available for a group:
```php
$jobs = (new QuickFilters($query))
    ->apply($request->all())
    ->get();
```
To apply only the specified filters, pass an array of filter names second parameter:
```php
$jobs = (new QuickFilters($query))
    ->apply($request->all(), [
        EmailFilter::class,
        NameFilter::class,
        ...
    ])
    ->get();
```
`except(array $data, array $exceptedFilters)` apply everything except the listed:
```php
$jobs = (new QuickFilters($query))
    ->except($request->all(), [
        EmailFilter::class,
        NameFilter::class,
        ...
    ])->get();
```
`exceptEloquentFilters(array $data)` apply everything except implement the interface `EloquentFilterContract`

`addFilters(array $filters)` add arbitrary filters:
```php
$jobs = (new QuickFilters($query))
    ->addFilters([
        EmailFilter::class,
        NameFilter::class,
        ...
    ])
    ->apply($request->all())
    ->get();
```

## License
The MIT License (MIT). See [License File](LICENSE) for more information.