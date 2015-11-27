ShortId
=======

[![Total Downloads](https://poser.pugx.org/pugx/shortid-php/downloads.png)](https://packagist.org/packages/pugx/shortid-php)
[![Build Status](https://travis-ci.org/PUGX/shortid-php.png?branch=master)](https://travis-ci.org/PUGX/shortid-php)
[![Code Climate](https://codeclimate.com/github/PUGX/shortid-php/badges/gpa.svg)](https://codeclimate.com/github/PUGX/shortid-php)
[![Test Coverage](https://codeclimate.com/github/PUGX/shortid-php/badges/coverage.svg)](https://codeclimate.com/github/PUGX/shortid-php/coverage)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/058a0905-b889-49a4-9752-766787fcaeae/mini.png)](https://insight.sensiolabs.com/projects/058a0905-b889-49a4-9752-766787fcaeae)

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

Doctrine
========

If you want to use ShortId with Doctrine ORM, take a look to
[ShortId Doctrine type](https://github.com/PUGX/shortid-doctrine).


Doctrine and Symfony
====================

If you want to use ShortId with Doctrine ORM and Symfony framework, take a look to
[ShortId Doctrine type bundle](https://github.com/PUGX/shortid-doctrine-bundle).

