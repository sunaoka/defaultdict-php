# defaultdict-php
 
[![License](https://img.shields.io/github/license/sunaoka/defaultdict-php.svg?style=flat)](https://github.com/sunaoka/defaultdict-php/blob/main/LICENSE)  
[![PHP Version](https://img.shields.io/packagist/php-v/sunaoka/defaultdict-php.svg?style=flat)](https://packagist.org/packages/sunaoka/defaultdict-php)  
[![Test](https://github.com/sunaoka/defaultdict-php/actions/workflows/test.yml/badge.svg?branch=main)](https://github.com/sunaoka/defaultdict-php/actions/workflows/test.yml)
[![Code Coverage](https://codecov.io/gh/sunaoka/defaultdict-php/branch/main/graph/badge.svg)](https://codecov.io/gh/sunaoka/defaultdict-php)

A simple **default dictionary** implementation in PHP, inspired by Python’s `collections.defaultdict`.  
Automatically initializes missing keys with a default value or a user-provided
callable. Callables receive the missing key as their only argument.

---

## Installation

Install via Composer:

```bash
composer require sunaoka/defaultdict-php
```

## Usage

### Basic Example

```php
<?php

use Sunaoka\DefaultDict\DefaultDict;

$dict = new DefaultDict(0);

// Accessing an undefined key stores and returns the default value.
echo $dict['count']; // 0

// The stored value can then be updated like a counter.
$dict['count']++;
echo $dict['count']; // 1
```

### Default Values

Pass any value as the default. It is stored when an undefined key is first accessed.

```php
$nulls = new DefaultDict(null);
var_dump($nulls['missing']); // NULL

$integers = new DefaultDict(0);
echo $integers['count']; // 0

$floats = new DefaultDict(0.1);
echo $floats['ratio']; // 0.1

$strings = new DefaultDict('');
echo $strings['name']; // ''
```

Unlike Python's `defaultdict`, `null` is stored and returned as a default value;
it does not raise an exception for a missing key.

### Callable Factories

Pass a callable to create a default value from the undefined key.
Unlike Python's `default_factory`, the callable receives the missing key.

```php
$dict = new DefaultDict(function ($key) {
    return $key === 'primary' ? 0 : 1;
});

echo $dict['primary']; // 0
echo $dict['secondary']; // 1
```

### Nested Dictionaries

Pass a `DefaultDict` directly to share the same nested dictionary across missing
keys.

```php
$shared = new DefaultDict(new DefaultDict(0));
$shared['x']['count'] = 1;
echo $shared['y']['count']; // 1
```

Pass a callable to create an independent nested dictionary for each missing key.

```php
$independent = new DefaultDict(function ($key) {
    return new DefaultDict(0);
});
$independent['x']['count'] = 1;
echo $independent['y']['count']; // 0
```

### Array Access

`DefaultDict` implements `ArrayAccess`. Assign values with array syntax and use
`toArray()` to retrieve keys that have been accessed or explicitly assigned.
Every assignment requires an explicit key; `$dict[] = $value` throws an
`InvalidArgumentException`.
This library is inspired by Python's `collections.defaultdict`, but is not a
drop-in replacement for Python's `dict`. It does not provide methods such as
`get()`, `pop()`, or `setdefault()`, nor dictionary iteration or counting.

```php
$dict = new DefaultDict(0);

$dict['count'] = 1;
var_dump($dict->toArray()); // ['count' => 1]

unset($dict['count']);
var_dump($dict->toArray()); // []
```

`isset($dict[$key])` returns `true` for every key, including keys that have not
been accessed and keys removed with `unset()`. Accessing a removed key initializes
it with the default value again.
