# GDPR compliant data portability with ease

This package helps you get compliant with GDPR (article 7, 17, 20).

## Requirements

- PHP >= 7.0.0
- Laravel >= 5.5

## Installation

First, install the package via the Composer package manager:

```bash
$ composer require dialect/gdpr
```

After installing the package, you should publish the configuration file:

```bash
$ php artisan vendor:publish --tag=gdpr-config
```
####Portability
Add the `Dialect\Gdpr\Portable` trait to the `App\User` model:

```php
<?php

namespace App;

use Dialect\Gdpr\Portable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Portable, Notifiable;
}

```

####Anonymizable
Add the `Dialect\Gdpr\Anonymizable` trait to the `App\User` model:

```php
<?php

namespace App;

use Dialect\Gdpr\Anonymizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Anonymizable, Notifiable;
}

```

## Configuration

### Configuring Anonymizable Data

On the model, set the `gdprAnonymizableFields`-array by adding the fields you want to anonymize on the model, you can also use closures in the array, if no value for the field exists, default string from settings will be used:

```php
<?php

namespace App;

use Dialect\Gdpr\Anonymizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Anonymizable, Notifiable;

    /**
     * The fields to anonymize in the model, using the default string from config.
     *
     * @var array
     */
    protected $gdprAnonymizableFields = ['name', 'email'];
    
    /**
     * The fields to anonymize in the model, replacement strings specified.
     *
     * @var array
     */
    protected $gdprAnonymizableFields = [
    	'name' => 'Anonymized User', 
        'email' => 'anonymous@mail.com'
    ];
    
    /**
     * The fields to anonymize in the model, using closures.
     *
     * @var array
     */
    protected $gdprAnonymizableFields = [
        'name' => function($someString) {
    	    return $someString;
        },
        'email' => function($someEmail) {
            return $someEmail;
        },
    ];
}

```

### Configuring Portable Data

By default, the entire `toArray` form of the `App\User` model will be made available for download. If you would like to customize the downloadable data, you may override the `toPortableArray()` method on the model:

```php
<?php

namespace App;

use Dialect\Gdpr\Portable;
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

use Dialect\Gdpr\Portable;
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

use Dialect\Gdpr\Portable;
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

use Dialect\Gdpr\Portable;
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

You may encrypt/decrypt attributes on the fly using the `Dialect\Gdpr\EncryptsAttributes` trait on any model. The trait expects the `$encrypted` property to be filled with attribute keys:

```php
<?php

namespace App;

use Dialect\Gdpr\Portable;
use Dialect\Gdpr\Anonymizable;
use Illuminate\Notifications\Notifiable;
use Dialect\Gdpr\EncryptsAttributes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use EncryptsAttributes, Anonymizable, Portable, Notifiable;

    /**
     * The attributes that should be encrypted and decrypted on the fly.
     *
     * @var array
     */
    protected $encrypted = ['ssnumber'];
}

```

###Anonymization

To anonymize a model you call anonymizeThis() on it:

```php
<?php

namespace App;

use Dialect\Gdpr\Portable;
use Dialect\Gdpr\Anonymizable;
use Dialect\Gdpr\EncryptsAttributes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SomeController extends Controller
{
    public function anonymizeAGroupOfUsers() {
    	$users = User::where('last_activity', '<=', carbon::now()->submonths(config('gdpr.settings.ttl')))->get();
    	foreach ($users as $user) {
            $user->anonymize();
        }
    }
}

```
## Security Vulnerabilities

If you discover a security vulnerability within this project, please send an e-mail to Dialect via [katrineholm@dialect.se](mailto:katrineholm@dialect.se). All security vulnerabilities will be promptly addressed.

##Credit

[sander3](https://github.com/sander3): Author of the original package used as a startingpoint

## License

This package is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
