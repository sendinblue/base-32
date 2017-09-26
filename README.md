# sendinblue/base-32

RFC 3548 and 4648 compliant base 32 encoder/decoder.

## Installation

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this library:

```console
$ composer require sendinblue/base-32 "~1"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

## Usage

This library provides a `\SendinBlue\Base32` class which exposes a `encode` and `decode` method, that’s it!

You can also set `encode`’s second argument `$padding` to `false` to avoid including any padding into the encoded string.

```php
<?php

use SendinBlue\Base32;

echo
    Base32::encode('foobar'),           // MZXW6YTBOI======
    Base32::encode('foobar', false),    // MZXW6YTBOI
    Base32::decode('MZXW6YTBOI======'), // foobar
    Base32::decode('MZXW6YTBOI')        // foobar
;
```

## Performance

This library has been profiled with blackfire against

- christian-riesen/base32
- paragonie/constant_time_encoding
- skleeschulte/base32
- togos/base32
- peterbodnar.com/base32
- chillerlan/php-base32

and it outperformed them all both on time and memory.