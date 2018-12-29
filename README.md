# Otter
A relatively automatic CRUD backend administration panel

# Introduction
Otter was created as an open-source alternative to Laravel Nova. The backend administration panel is built with the beautiful tabler template and follows the structure of the popular laravel extension packages like horizon and telescope

# Installation

Install Otter with [composer](https://getcomposer.org/doc/00-intro.md):

```bash
$ composer require poowf/otter
```

> In Laravel 5.5+, [service providers and aliases are automatically registered](https://laravel.com/docs/5.5/packages#package-discovery). If you're using Laravel 5.5+, skip ahead directly to step 3.

Once the composer installation completes, you can add the service provider and alias the facade. Open `config/app.php`, and make the following changes:

1) Add a new item to the `providers` array:

    ```php
    Poowf\Otter\OtterServiceProvider::class,
    ```

2) Add a new item to the `aliases` array:

    ```php
    'Otter' => Poowf\Otter\OtterFacade::class,
    ```

    This part is optional. If you don't want to use the facade, you can skip step 3.

3) Now, you need to install all the relevant Otter assets
    > If you are updating Otter, run `php artisan otter:publish` instead
    ```bash
    php artisan otter:install
    ```
    
# Usage
Defining the Models to be registered to Otter is very simple. Let's create an `OtterResource` by running the following command:
```bash
php artisan otter:resource User
``` 
> You may specify a model class name with the `--model` argument

This will generate a `OtterResource` file located in `app/Otter`.

# OtterResource Conventions
This is an example of an `OtterResource` that is generated with the `otter:resource` command, which will be automatically registered by Otter.
```php
<?php

namespace App\Otter;

use Poowf\Otter\Http\Resources\OtterResource;

class User extends OtterResource
{
    //
}
```

## Model
The `$model` variable is where we define the Eloquent Model that the `OtterResource` is responsible for.

```php
<?php

namespace App\Otter;

use Poowf\Otter\Http\Resources\OtterResource;

class User extends OtterResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\User';
}
```

## Fields
The `fields` function will return a key value pair of the available columns that you would like to control in the Otter.
They key is the name of the column in the model, and the value is the type of the input.

```php
<?php

namespace App\Otter;

use Poowf\Otter\Http\Resources\OtterResource;

class User extends OtterResource
{
    /**
     * Get the fields and types used by the resource
     *
     * @return array
     */
    public function fields()
    {
        return [
            'name' => 'text',
            'password' => 'password',
            'email' => 'email',
        ];
    }
}
```

You can hide certain fields in the index and single view resources by defining a `hidden` function returning an array of the keys that you would like hidden. For example, the default configuration is to hide the password field for a User.

```php
<?php

namespace App\Otter;

use Poowf\Otter\Http\Resources\OtterResource;

class User extends OtterResource
{
    /**
     * Fields to be hidden in the resource collection
     *
     * @return array
     */
    public function hidden()
    {
        return [
            'password'
        ];
    }
}
```

## Relationships

Otter has partial support for Eloquent relationships. You have to define your relationships in the `OtterResource` file and define the Relationship method name as the key and the `OtterResource` class name that links to the relationship.
 
You can also define a custom foreign key if you are not using the Laravel defaults.

The `title` property should be the column of the model that will be displayed in the options list during editing/creating of new resources .

```php
<?php

namespace App\Otter;

use Poowf\Otter\Http\Resources\OtterResource;

class User extends OtterResource
{
    /**
     * The column of the model to display in select options
     *
     * @var string
     */
    public static $title = 'name';
        
    /**
     * Get the relations used by the resource
     *
     * @return array
     */
    public function relations()
    {
        return [
            'company' => ['Company', 'company_id'],
            'company' => 'Company',
        ];
    }
}
```

# Authorization
Otter exposes a dashboard at `/otter`. By default, you will only be able to access this dashboard in the local environment. Within your `app/Providers/OtterServiceProvider.php` file, there is a gate method. This authorization gate controls access to Otter in non-local environments. You are free to modify this gate as needed to restrict access to your Otter installation:

```php
/**
 * Register the Otter gate.
 *
 * This gate determines who can access Otter in non-local environments.
 *
 * @return void
 */
protected function gate()
{
    Gate::define('viewOtter', function ($user) {
        return in_array($user->email, [
            'zane@poowf.com'
        ]);
    });
}
```

# Configuration
After publishing Otter's assets, its primary configuration file will be located at `config/otter.php`. This configuration file will allow you to configure the middleware for both the `api` and `web` routes that is automatically registered by Otter. You can also configure the keys of the `Auth::user()` instance for the `name` and `email` properties.
```php
'middleware.web' => ['web'],
'middleware.api' => ['api'],

'user' => [
    'name' => 'name',
    'email' => 'email',
],
```