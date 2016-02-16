# UniLogin plugin for CakePHP

[![Build Status](https://travis-ci.org/Oefenweb/cakephp-uni-login.png?branch=master)](https://travis-ci.org/Oefenweb/cakephp-uni-login) [![Coverage Status](https://coveralls.io/repos/Oefenweb/cakephp-uni-login/badge.png)](https://coveralls.io/r/Oefenweb/cakephp-uni-login) [![Packagist downloads](http://img.shields.io/packagist/dt/Oefenweb/cakephp-uni-login.svg)](https://packagist.org/packages/oefenweb/cakephp-uni-login) [![Code Climate](https://codeclimate.com/github/Oefenweb/cakephp-uni-login/badges/gpa.svg)](https://codeclimate.com/github/Oefenweb/cakephp-uni-login)

## Requirements

* CakePHP 2.4.2 or greater.
* PHP 5.4.16 or greater.

## Installation

Clone/Copy the files in this directory into `app/Plugin/UniLogin`

## Configuration

Ensure the plugin is loaded in `app/Config/bootstrap.php` by calling:

```
CakePlugin::load('UniLogin');
```

Ensure to configure the following lines in `app/Config/bootstrap.php`:

```
// Application / plugins communication
Configure::write('UniLogin.application.completeUrl', '/uni_login_logins/login_complete');
Configure::write('UniLogin.application.secret', 'appSecret');

// Plugins provider communication
Configure::write('UniLogin.provider.url', 'https://sli.emu.dk/unilogin/login.cgi');
Configure::write('UniLogin.provider.applicationId', '1');
Configure::write('UniLogin.provider.secret', 'providerSecret');

// Plugins (test)provider communication
Configure::write('UniLogin.testProvider.defaultRedirectUrl', '/uni_login/uni_login/callback');
Configure::write('UniLogin.testProvider.applicationId', '1');
Configure::write('UniLogin.testProvider.user', 'testUser');
```

## Usage

```
```
