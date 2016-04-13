# PHP YPaginator

[![Build Status](https://travis-ci.org/dotzero/ypaginator-php.svg?branch=master)](https://travis-ci.org/dotzero/ypaginator-php)
[![Latest Stable Version](https://poser.pugx.org/dotzero/ypaginator/version)](https://packagist.org/packages/dotzero/ypaginator)
[![License](https://poser.pugx.org/dotzero/ypaginator/license)](https://packagist.org/packages/dotzero/ypaginator)

A lightweight PHP paginator without a database dependency, for generating pagination controls in the style of Yandex.

## Features

- The `first` and `last` page links are shown
- The `current` and `neighbours` page links are shown
- Rest of links are replaced by ellipses

## How it looks like:

    << previous | next >>
    |1| ... |5||6||7| ... |100|

## Usage

```php
$total = 100; // Total items
$perpage = 10; // Items per page
$current = 5; // Current page
$neighbours = 2; // Neighbours links beside current page

$y = new \dotzero\YPaginator($total, $perpage, $current);

$paginator = $y
    ->setNeighbours($neighbours)
    ->setUrlMask('#num#')
    ->setUrlTemplate('/foo/page/#num#')
    ->getPaginator();

print_r($paginator);
```

Output looks like:

```php
[
    "prev" => ["name" => 4,"url" => "/foo/page/4","current" => false], // Previous
    "pages" => [
        ["name" => 1,"url" => "/foo/page/1","current" => false], // First
        ["name" => "...","url" => "/foo/page/2","current" => false],
        ["name" => 3,"url" => "/foo/page/3","current" => false], // Neighbour
        ["name" => 4,"url" => "/foo/page/4","current" => false], // Neighbour
        ["name" => 5,"url" => "/foo/page/5","current" => true],  // Current
        ["name" => 6,"url" => "/foo/page/6","current" => false], // Neighbour
        ["name" => 7,"url" => "/foo/page/7","current" => false], // Neighbour
        ["name" => "...","url" => "/foo/page/8","current" => false],
        ["name" => 10,"url" => "/foo/page/10","current" => false] // Last
    ],
    "next" => ["name" => 6,"url" => "/foo/page/6","current" => false] // Next
];
```

## Install

### Via composer:

```bash
$ composer require dotzero/ypaginator
```

### Without composer

Clone the project using:

```bash
$ git clone https://github.com/dotzero/ypaginator-php
```

and include the source files with:

```php
require_once("ypaginator-php/src/YPaginator.php");
```

## Test

First install the dependencies, and after you can run:

```bash
$ vendor/bin/phpunit
```

## License

Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
