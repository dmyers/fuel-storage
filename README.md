# Fuel Storage Package

A super simple Storage package for Fuel.

## About
* Version: 1.0.0
* License: MIT License
* Author: Derek Myers

## Installation

### Git Submodule

If you are installing this as a submodule (recommended) in your git repo root, run this command:

	$ git submodule add git://github.com/dmyers/fuel-storage.git fuel/packages/storage

Then you you need to initialize and update the submodule:

	$ git submodule update --init --recursive fuel/packages/storage/

### Download

Alternatively you can download it and extract it into `fuel/packages/storage/`.

## Usage

```php
$file = Storage::load('info.php');

Storage::save('log.txt', 'success');
```

## Updates

In order to keep the package up to date simply run:

	$ git submodule update --recursive fuel/packages/storage/
