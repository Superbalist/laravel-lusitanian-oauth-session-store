# laravel-lusitanian-oauth-session-store

A Laravel session storage interface for the lusitanian/oauth library

[![Author](http://img.shields.io/badge/author-@superbalist-blue.svg?style=flat-square)](https://twitter.com/superbalist)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/superbalist/laravel-lusitanian-oauth-session-store.svg?style=flat-square)](https://packagist.org/packages/superbalist/laravel-lusitanian-oauth-session-store)
[![Total Downloads](https://img.shields.io/packagist/dt/superbalist/laravel-lusitanian-oauth-session-store.svg?style=flat-square)](https://packagist.org/packages/superbalist/laravel-lusitanian-oauth-session-store)


## Installation

```bash
composer require superbalist/laravel-lusitanian-oauth-session-store
```

## Usage

```php
use OAuth\ServiceFactory;
use Superbalist\LusitanianOAuth\LaravelTokenSessionStore;

// this example demonstrates creating a github service

$factory = new ServiceFactory();
$storage = new LaravelTokenSessionStore();

$credentials = [
    '[[github key]]',
    '[[github secret]]',
    '[[url]]',
];

$gitHub = $factory->createService('GitHub', $credentials, $storage, array('user'));
```