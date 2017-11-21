![ha framework](https://github.com/itrnka/ha-framework/blob/master/docs/img/ha-logo.png "ha framework")

# Project skeleton for *ha* framework

For more informations about framework please see [framework documentation](https://github.com/itrnka/ha-framework/blob/master/README.md).


## Requirements

- PHP 7.1+
- composer installed and included in `PATH` env variable

## Installation

- Download or clone this repository to your directory.
- Go to this directory and open `php` folder.
- Then run in this directory `composer install` command.
- Edit your HTTP server configuration and setup document root to `{yourDirectory}/public` and set index file to `index.php` (if is not set).
- Restart your HTTP server (reload configuration)
- Add your project to host file (optional)
- Open browser and enter URL...


## Directory structure

Note: ha framework is installed via composer, so we can not found framework files in this skeleton.

### bin

Console access to application.

- `ha` executable php file (console application runner)
- `ha.bat` console opener for Windows
- `ha.ini` configuration file for console application

### php

Directory with php files (here is full php application code).

- `conf` directory with project configurations files
   - `_shared.php` shared configuration (configuration base)
   - `console.conf.php` configuration for console access
   - `web.conf.php` configuration for HTTP access (e.g. website, mobile site, API, ...)
- `ver-1.0.0` directory for application code
   - `Examples` some simple class examples
   - `composer.json` package definition for composer
   - `helpers.php` simple PHP functions, which helps with some problems
   - `main.php` application initializer

### public

This is document root.

- `.htaccess` example configuration and redirect setup example for Apache HTTP server
- `index.php` this is application bootstrap and handler for HTTP access to application
