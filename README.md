base64-encoded-file
===================

Provides handling for base64 encoded files

[![Build Status](https://travis-ci.org/hshn/base64-encoded-file.svg?branch=master)](https://travis-ci.org/hshn/base64-encoded-file)
[![Latest Stable Version](https://poser.pugx.org/hshn/base64-encoded-file/v/stable.svg)](https://packagist.org/packages/hshn/base64-encoded-file)
[![Total Downloads](https://poser.pugx.org/hshn/base64-encoded-file/downloads.svg)](https://packagist.org/packages/hshn/base64-encoded-file)
[![Latest Unstable Version](https://poser.pugx.org/hshn/base64-encoded-file/v/unstable.svg)](https://packagist.org/packages/hshn/base64-encoded-file)
[![License](https://poser.pugx.org/hshn/base64-encoded-file/license.svg)](https://packagist.org/packages/hshn/base64-encoded-file)


## Installation

```bash
$ php composer.phar require hshn/base64-encoded-file
```

## Usage

```php
<?php

use Hshn\Base64EncodedFile\HttpFoundation\File\Base64EncodedFile;

$file = new Base64EncodedFile(base64_encode($data));

$file->getPathname(); // "/path/to/file"
$file instanceof Symfony\Component\HttpFoundation\File\File; // true
```


### Integration for symfony/form

```php
<?php

use Hshn\Base64EncodedFile\Form\Type\Base64EncodedFileType;

$form = $formBuilder
    ->add('file', new Base64EncodedFileType())
    ->getForm();
```
