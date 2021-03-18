ShortId
=======

[![Total Downloads](https://poser.pugx.org/pugx/shortid-php/downloads.png)](https://packagist.org/packages/pugx/shortid-php)
![Build Status](https://github.com/PUGX/shortid-php/workflows/build/badge.svg)
[![Code Climate](https://codeclimate.com/github/PUGX/shortid-php/badges/gpa.svg)](https://codeclimate.com/github/PUGX/shortid-php)
[![Test Coverage](https://codeclimate.com/github/PUGX/shortid-php/badges/coverage.svg)](https://codeclimate.com/github/PUGX/shortid-php/coverage)
[![codecov](https://codecov.io/gh/PUGX/shortid-php/branch/master/graph/badge.svg?token=3sX6ShXgZp)](https://codecov.io/gh/PUGX/shortid-php)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/058a0905-b889-49a4-9752-766787fcaeae/mini.png)](https://insight.sensiolabs.com/projects/058a0905-b889-49a4-9752-766787fcaeae)
[![License](https://poser.pugx.org/pugx/shortid-php/license.svg)](https://packagist.org/packages/pugx/shortid-php)

This library is an implementation of [ShortId](https://github.com/dylang/shortid) for PHP.

Basic usage
-----------

Just call `PUGX\Shortid\Shortid::generate()` to get a random string with default length 7, like "MfiYIvI".

``` php
use PUGX\Shortid\Shortid;

require_once __DIR__.'/vendor/autoload.php';

$id = Shortid::generate();

```

Advanced usage
--------------

In the following example, you can see how to change the basic alphabet and default length.

Default alphabet uses all letters (lowercase and uppercase), all numbers, underscore, and hypen.

``` php
use PUGX\Shortid\Factory;
use PUGX\Shortid\Shortid;

require_once __DIR__.'/vendor/autoload.php';

$factory = new Factory();
// alphabet string must be 64 characters long
$factory->setAlphabet('é123456789àbcdefghìjklmnòpqrstùvwxyzABCDEFGHIJKLMNOPQRSTUVWX.!@|');
// length must be between 2 and 20 (default is 7)
// of course, a lower length is increasing clashing probability
$factory->setLength(9);
Shortid::setFactory($factory);

$id = Shortid::generate();
```

As alternative, you can customize single generations:

``` php
use PUGX\Shortid\Shortid;

require_once __DIR__.'/vendor/autoload.php';

$id9 = Shortid::generate(9, 'é123456789àbcdefghìjklmnòpqrstùvwxyzABCDEFGHIJKLMNOPQRSTUVWX.!@|');
$id5 = Shortid::generate(5);

```

More readable strings
---------------------

Sometimes, you want to avoid some ambiguous characters, like `B`/`8` or `I`/`l` (uppercase/lowercase).
In this case, you can pass a third parameter `true` to `generate` method. Notice that in this case the alphabet will
be ignored, so it makes sense to pass a null one.

Example:

``` php
use PUGX\Shortid\Shortid;

require_once __DIR__.'/vendor/autoload.php';

$id = Shortid::generate(7, null, true);
``` 

Pre-defined values
------------------

If you need a deterministic string, instead of a random one, you can call directly the class constructor.
This could be useful, for instance, when you need pre-defined data for testing purposes.

``` php
use PUGX\Shortid\Shortid;

require_once __DIR__.'/vendor/autoload.php';

$myFixedId = new Shortid('5h0r71d');
$anotherFixedId = new Shortid('fooBarZ');

```

Doctrine
--------

If you want to use ShortId with Doctrine ORM, take a look to
[ShortId Doctrine type](https://github.com/PUGX/shortid-doctrine).


Doctrine and Symfony
--------------------

If you want to use ShortId with Doctrine ORM and Symfony framework, take a look to
[ShortId Doctrine type bundle](https://github.com/PUGX/shortid-doctrine-bundle).


mbstring extension
------------------

This library uses [a polyfill](https://github.com/symfony/polyfill-mbstring), so it
can used in environments where mbstring native extension is not available.

If, instead, your environment is offering such extension, you can avoid installing
polyfill by configuring [replace](https://getcomposer.org/doc/04-schema.md#replace)
entry in your `composer.json`.

