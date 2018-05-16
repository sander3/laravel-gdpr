# GDPR compliant data portability with ease

This package exposes an endpoint where authenticated users can download their data as required by GDPR article 20.

## Requirements

- PHP >= 7.0.0
- Laravel >= 5.5

## Installation

First, install the package via the Composer package manager:

```bash
$ composer require soved/laravel-gdpr
```

After installing the package, you should publish the configuration file:

```bash
$ php artisan vendor:publish --tag=gdpr-config
```

Finally, add the `Soved\Laravel\Gdpr\Portable` trait to the `App\User` model:

```php
<?php

namespace App;

use Soved\Laravel\Gdpr\Portable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Portable, Notifiable;
}

```

## Configuration

### Configuring Portable Data

By default, the entire `toArray` form of the `App\User` model will be made available for download. If you would like to customize the downloadable data, you may override the `toPortableArray()` method on the model:

```php
<?php

namespace App;

use Soved\Laravel\Gdpr\Portable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Portable, Notifiable;

    /**
     * Get the GDPR compliant data portability array for the model.
     *
     * @return array
     */
    public function toPortableArray()
    {
        $array = $this->toArray();

        // Customize array...

        return $array;
    }
}

```

### Lazy Eager Loading Relationships

You may need to include a relationship in the data that will be made available for download. To do so, add a `$gdprWith` property to your `App\User` model:

```php
<?php

namespace App;

use Soved\Laravel\Gdpr\Portable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Portable, Notifiable;

    /**
     * The relations to include in the downloadable data.
     *
     * @var array
     */
    protected $gdprWith = ['posts'];
}

```

### Hiding Attributes

You may wish to limit the attributes, such as passwords, that are included in the downloadable data. To do so, add a `$gdprHidden` property to your `App\User` model:

```php
<?php

namespace App;

use Soved\Laravel\Gdpr\Portable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Portable, Notifiable;

    /**
     * The attributes that should be hidden for the downloadable data.
     *
     * @var array
     */
    protected $gdprHidden = ['password'];
}

```

Alternatively, you may use the `$gdprVisible` property to define a white-list of attributes that should be included in the data that will be made available for download. All other attributes will be hidden when the model is converted:

```php
<?php

namespace App;

use Soved\Laravel\Gdpr\Portable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Portable, Notifiable;

    /**
     * The attributes that should be visible in the downloadable data.
     *
     * @var array
     */
    protected $gdprVisible = ['name', 'email'];
}

```

## Usage

This package exposes an endpoint at `/gdpr/download`. Only authenticated users should be able to access the routes. Your application should make a POST call, containing the currently authenticated user's password, to this endpoint. The re-authentication is needed to prevent information leakage.

### Encryption

> Before using encryption, you must set a `key` option in your `config/app.php` configuration file. If this value is not properly set, all encrypted values will be insecure.

You may encrypt/decrypt attributes on the fly using the `Soved\Laravel\Gdpr\EncryptsAttributes` trait on any model. The trait expects the `$encrypted` property to be filled with attribute keys:

```php
<?php

namespace App;

use Soved\Laravel\Gdpr\Portable;
use Illuminate\Notifications\Notifiable;
use Soved\Laravel\Gdpr\EncryptsAttributes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use EncryptsAttributes, Portable, Notifiable;

    /**
     * The attributes that should be encrypted and decrypted on the fly.
     *
     * @var array
     */
    protected $encrypted = ['ssnumber'];
}

```

## Roadmap

- Dispatch GdprDownload event
- Data retention

## Security Vulnerabilities

If you discover a security vulnerability within this project, please send an e-mail to Sander de Vos via [sander@tutanota.de](mailto:sander@tutanota.de). All security vulnerabilities will be promptly addressed.

## License

This package is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
