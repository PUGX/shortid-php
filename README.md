ShortId
=======

This library is an implementation of [ShortId](https://github.com/dylang/shortid) for PHP.

Basic usage
===========

Just call ``PUGX\Shortid\Shortid::generate()`` to get a random string with length 7, like "MfiYIvI".

``` php
use PUGX\Shortid\Shortid;

require_once __DIR__.'/vendor/autoload.php';

$id = Shortid::generate();

```

Advanced usage
==============

In the following example, you can see how to change the basic alphabet.

Default alphabet uses all letters (lowercase and uppcercase), all numbers, underscore, and hypen.

``` php
use PUGX\Shortid\Factory;
use PUGX\Shortid\Shortid;

require_once __DIR__.'/vendor/autoload.php';

$factory = new Factory();
$factory->setAlphabet('é123456789àbcdefghìjklmnòpqrstùvwxyzABCDEFGHIJKLMNOPQRSTUVWX.!@|');
Shortid::setFactory($factory);

$id = Shortid::generate();

```