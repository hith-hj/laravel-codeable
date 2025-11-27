----------

## Laravel Codeable

Laravel Codeable focuses on **Code generation for models** such as OTPs.

### Supported Laravel versions
| Laravel Version  | Codeable Version |
|------------------|------------------|
| 12.x             | 1.0+             |

## Getting Started

> **Requires:**
- **[PHP 8.2+](https://php.net/releases/)**
- **[Laravel 12.x+](https://github.com/laravel/laravel)**

**1**: Use [Composer](https://getcomposer.org) to install laravel-codeable into your project:

```bash
composer require "hith/laravel-codeable"
```

**2**: Then, publish files:

```bash
php artisan vendor:publish --tag=codeable-migrations
php artisan vendor:publish --tag=codeable-command
php artisan vendor:publish --tag=codeable-config
```

For simplicity, you can publish all files at once:
```bash
php artisan vendor:publish --tag=codeable-files

```

**3**: Finally, use the package:

* By adding the trait directly to your models.
* By using the Codeable class to create/delete codes.
* By using the Coder Facade.

### Usage of HasCodes Trait:
In Model:
```php
use Codeable\Traits\HasCodes;

class User extends Model
{
    use HasCodes;
}

```

Then:
```php
$user->createCode();
$code = $user->createCode(type:'code_type', length:6, timeToExpire:'15:m'); // or define the attributes you want
$user->deleteCode($code); // delete by passing a Code model instance
$user->deleteCode('code_type'); // delete by code type
$user->deleteCode($code->id); // delete by code id
```

###  Usage of Codeable Class:

```php
use Codeable\Codeable;

$codeable = new Codeable();
$codeable->createCode();
$code = $codeable->createCode(type:'code_type', length:6, timeToExpire:'15:m');
$codeable->deleteCode($code);
$codeable->deleteCode('code_type');

```

###  Usage of Coder Facade:

```php
use Codeable\Facades\Coder;

$code = Coder::createCode();
if(! $code->isValid()){
    Coder::delete($code);
}

$code = Coder::codeByType('code_type');
if($code->isExpired()){
    Coder::delete($code);
}

```

## Codeable Configuration

You can alter the behavior using the Codeable config file.

```php

/**
 *  min_length   : int
 *  The minimum number of digits allowed in a generated code.
 * */
'min_length' => 3,

/**
 *  max_length   : int
 *  The maximum number of digits allowed in a generated code.
 * */
'max_length' => 16,

/**
 *  max_attempts : int
 *  The maximum number of attempts to generate a unique code before failing.
 *  Example: 5 → after 5 unsuccessful tries, any code will be returned.
 * */
'max_attempts' => 1,

/**
 *   valid_units  : string[]
 *   Allowed time units for setting the `expire_at` field in the database record.
 */
'valid_units' => [
    's' => 'second',
    'm' => 'minute',
    'h' => 'hour',
    'd' => 'day',
]

```

## Contributing

Thank you for considering contributing to Laravel Codeable. All the contribution guidelines are mentioned [here](CONTRIBUTING.md).

## License

Laravel Codeable is an open-sourced software licensed under the [MIT license](LICENSE.md).
