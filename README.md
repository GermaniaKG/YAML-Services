# Germania KG · YAML Services

**Callable wrappers around Symfony's YAML component, with Pimple 3 Service provider and optional PSR3 Logger support.**

[![Latest Stable Version](https://poser.pugx.org/germania-kg/yaml-services/v/stable)](https://packagist.org/packages/germania-kg/yaml-services)
[![Build Status](https://travis-ci.org/GermaniaKG/YAML-Services.svg?branch=master)](https://travis-ci.org/GermaniaKG/YAML-Services)
[![Code Coverage](https://scrutinizer-ci.com/g/GermaniaKG/YAML-Services/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/YAML-Services/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/GermaniaKG/YAML-Services/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/YAML-Services/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/GermaniaKG/YAML-Services/badges/build.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/YAML-Services/build-status/master)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

## Installation

```bash
$ composer require germania-kg/yaml-services
```

## Parse YAML string

```php
<?php
use Germania\YamlServices\YamlParserCallable;

// Instantiate
$parser = new YamlParserCallable;

// Do business, assuming YAML content string
$result = $parser( $yaml_content );

// Evaluate, usually Array [...]
print_r( $result );
```

## Parse YAML file

```php
<?php
use Germania\YamlServices\YamlFileParserCallable;
use Symfony\Component\Finder\Finder;

// Instantiate, passing a Symfony Finder
$finder = new Finder;
$finder = $finder->depth( 0 )->in( [ 'contents', 'includes'] );

// Setup parser
$parser = new YamlFileParserCallable( $finder );

// Do business, just passing your YAML file:
$result = $parser( "config.yaml" );

// Evaluate, usually Array [...]
print_r( $result );
```


## Configuration

Both *YamlParserCallable* and *YamlFileParserCallable* accept the usual YAML Parser options, as described here: [Symfony YAML Component](https://symfony.com/doc/current/components/yaml.html)
or on [GitHub.](https://github.com/symfony/yaml) — Additionally, the constructor accepts any [PSR 3 Logger](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md).

**Example 1: YamlParserCallable**

```php
<?php
use Germania\YamlServices\YamlParserCallable;
use Symfony\Component\Yaml;

$parser = new YamlParserCallable( Yaml::PARSE_OBJECT );
$parser = new YamlParserCallable( Yaml::PARSE_OBJECT, $psr3_logger );

// Or, use per-case parsing options:
$custom_flags = Yaml::PARSE_OBJECT | YAML::PARSE_OBJECT_FOR_MAP;
$result = $parser($yaml_content, $custom_flags);
print_r( $result );

```

**Example 2: YamlFileParserCallable**

```php
<?php
use Germania\YamlServices\ YamlFileParserCallable;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml;

// Configure your Finder 
$finder = new Finder;
$finder = $finder->depth( 0 )->in( [ 'contents', 'includes'] );

// Setup parser
$parser = new YamlParserCallable( $finder, Yaml::PARSE_OBJECT );
$parser = new YamlParserCallable( $finder, Yaml::PARSE_OBJECT, $psr3_logger );

// Or, use per-case parsing options:
$custom_flags = Yaml::PARSE_OBJECT | YAML::PARSE_OBJECT_FOR_MAP;
$result = $parser( "config.yaml", $custom_flags);
print_r( $result );

```


## Pimple Service Provider

### Registering the Service Provider
```php
<?php
use Germania\YamlServices\PimpleServiceProvider;

// Have your Pimple Container ready...
$dic = new Pimple\Container;

// Instantiate and register
$yaml_services = new PimpleServiceProvider;
$dic->register( $yaml_services );
```

### Use YAML services

```php
<?php
// To parse a string
$parser = $dic['Yaml.Parser'];
$result = $parser( "My YAML string" );

// To parse a file
$parser = $dic['Yaml.FileParser'];
$result = $parser( "config.yaml" );
```

### Configuring the Service Provider

**The PimpleServiceProvider constructor** allows you to optionally pass these dependencies:

- YAML Parser options, service name: `Yaml.Flags`
- The Symfony Finder instance, service name: `Yaml.Finder`
- The PSR 3 Logger instance, service name: `Yaml.Logger`

```php
$yaml_services = new PimpleServiceProvider( yaml_options, $finder, $psr3_logger) ;
$dic->register( $yaml_services );
```

**As all dependencies are Pimple services as well,** you alternatively may override each service like this:

```php
$dic->register( new PimpleServiceProvider );

$dic->extend('Yaml.Flags', function( $flags, $dic) {
    return $flags | YAML::PARSE_OBJECT_FOR_MAP;
});

$dic->extend('Yaml.Finder', function( $finder, $dic) {
    return $dic['MyCustomFinder'];
});

$dic->extend('Yaml.Logger', function( $logger, $dic) {
    return $dic['Logger']->withName('YAML');
});
```


## Development

```bash
$ git clone git@github.com:GermaniaKG/YAML-Services.git
$ cd YAML-Services
$ composer install
```


## Unit tests

Either copy `phpunit.xml.dist` to `phpunit.xml` and adapt to your needs, or leave as is. 
Run [PhpUnit](https://phpunit.de/) like this:

```bash
$ vendor/bin/phpunit
```









