[![Build Status](https://travis-ci.org/dialect-katrineholm/laravel-gdpr-compliance.svg?branch=master)](https://travis-ci.org/dialect-katrineholm/laravel-gdpr-compliance)
[![StyleCI](https://github.styleci.io/repos/133474603/shield?branch=master)](https://github.styleci.io/repos/133474603)
# GDPR compliant data handling with ease

This package helps you get compliant with GDPR;

Article **7**: Conditions for consent <br>
Article **17**: Right to be forgotten <br>
Article **20**: Right to data portability <br>

Table of contents
=================

<!--ts-->
   * [Table of contents](#table-of-contents)
   * [Dependencies](#dependencies)
   * [Installation](#installation)
   * [Configuration](#configuration)
      * [Portability](#portability)
      * [Anonymizable](#anonymizable)
      * [Configuring Anonymizable Data](#configuring-anonymizable-data)
      * [Recursive Anonymization](#recursive-anonymization)
      * [Configuring Portable Data](#configuring-portable-data)
      * [Lazy Eager Loading Relationships](#lazy-eager-loading-relationships)
      * [Hiding Attributes](#hiding-attributes)
   * [Usage](#usage)
      * [Encryption](#encryption)
      * [Anonymization](#anonymization)
   * [Tests](#tests)
   * [Security Vulnerabilities](#security-vulnerabilities)
   * [Credit](#credit)
   * [License](#license)
<!--te-->

## Dependencies

- PHP >= 7.0.0
- Laravel >= 5.5

## Installation

First, install the package via the Composer package manager:

```bash
$ composer require dialect/laravel-gdpr-compliance
```

After installing the package, you should publish the configuration file:

```bash
$ php artisan vendor:publish --provider="Dialect\Gdpr\GdprServiceProvider" --tag=gdpr-config
```

## Configuration

#### GDPR Consent
The package includes a way for users to sign a GDPR-agreement. This will redirect the user to the agreement on the specified routes
until the user has agreed to the new terms.

To add the agreement functionality:

1. Publish the middleware: <br>
    `php artisan vendor:publish --provider="Dialect\Gdpr\GdprServiceProvider" --tag=gdpr-consent`
2. Add `'gdpr.terms' => \App\Http\Middleware\RedirectIfUnansweredTerms::class` <br>
    to the `$routeMiddleware` middlewaregroup in `app/Http/Kernel` like so: <br>
    ```php
        protected $routeMiddleware = [
            'gdpr.terms' => \App\Http\Middleware\RedirectIfUnansweredTerms::class,
        ];
    ```
3. Add the middleware to the routes that you want to check (normally the routes where auth is used):
    ```php
    Route::group(['middleware' => ['auth', 'gdpr.terms']], function () {
       Route::get('/', 'HomeController@index');
    }
    ```
4. Change the Agreement text to your particular needs in `resources/views/gdpr/message.blade.php`

#### Portability
Add the `Portable` trait to the model model you want to be able to port:

```php
namespace App;

use Dialect\Gdpr\Portable;

class User extends Model
{
    use Portable;
}

```

#### Anonymizable
Add the `Anonymizable` trait to the model you want to be able to anonymize:

```php
namespace App;

use Dialect\Gdpr\Anonymizable;

class User extends Model
{
    use Anonymizable;
}

```

### Configuring Anonymizable Data

On the model, set `gdprAnonymizableFields` by adding the fields you want to anonymize on the model, 
you can also use closures in the array, if no value for the field exists, default string from settings will be used.
```
    /**
     * Using the default string from config.
     */
    protected $gdprAnonymizableFields = [
        'name', 
        'email'
    ];
```
```
    /**
     * Using replacement strings.
     */
    protected $gdprAnonymizableFields = [
    	'name' => 'Anonymized User', 
        'email' => 'anonymous@mail.com'
    ];
```
```
    /**
     * Using closures.
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

### Recursive Anonymization
If the model has related models with fields that needs to be anonymized at the same time,
add the related models to `$gdprWith`. On the related models. add the `Anonymizable` trait and specify the fields with `$gdprAnonymizableFields` like so:
```php
class Order extends Model
{
    use Anonymizable;
		
	protected $guarded = [];
	protected $table = 'orders';
	protected $gdprWith = ['product'];
    protected $gdprAnonymizableFields = ['buyer' => 'Anonymized Buyer'];
    
	public function product()
	{
		return $this->belongsTo(Product::class);
	}
	public function customer()
	{
		return $this->belongsTo(Customer::class);
	}
}
```
```php
class Customer extends Model
{
    use Anonymizable;
	protected $guarded = [];
	protected $table = 'customers';
	protected $gdprWith = ['orders'];

	protected $gdprAnonymizableFields = ['name' => 'Anonymized User'];

	public function orders()
	{
		return $this->hasMany(Order::class);
	}
}
```
Calling `$customer->anonymize();` will also change the `buyer`-field on the related orders.
  
### Configuring Portable Data

By default, the entire `toArray` form of the `App\User` model will be made available for download. If you would like to customize the downloadable data, you may override the `toPortableArray()` method on the model:

```php
use Dialect\Gdpr\Portable;

class User extends Model
{
    use Portable;

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
use Dialect\Gdpr\Portable;

class User extends Model
{
    use Portable;

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
use Dialect\Gdpr\Portable;

class User extends Model
{
    use Portable;

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
use Dialect\Gdpr\Portable;

class User extends Moeld
{
    use Portable;

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

You may encrypt/decrypt attributes on the fly using the `EncryptsAttributes` trait on any model. 
The trait expects the `$encrypted` property to be filled with attribute keys:

```php
use Dialect\Gdpr\EncryptsAttributes;

class User extends Model
{
    use EncryptsAttributes;

    /**
     * The attributes that should be encrypted and decrypted on the fly.
     *
     * @var array
     */
    protected $encrypted = ['ssnumber'];
}

```

### Anonymization

To anonymize a model you call `anonymize()` on it:

```php
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
## Tests

After installation you can run the package tests from your laravel-root folder with `phpunit vendor/Dialect/gdpr`

## Security Vulnerabilities

If you discover a security vulnerability within this project, please send an e-mail to Dialect via [katrineholm@dialect.se](mailto:katrineholm@dialect.se). All security vulnerabilities will be promptly addressed.

## Credit

[sander3](https://github.com/sander3): Author of the original package used as a startingpoint

## License

This package is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
