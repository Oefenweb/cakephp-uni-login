# UniLogin plugin for CakePHP

[![Build Status](https://travis-ci.org/Oefenweb/cakephp-uni-login.png?branch=master)](https://travis-ci.org/Oefenweb/cakephp-uni-login) [![PHP 7 ready](http://php7ready.timesplinter.ch/Oefenweb/cakephp-uni-login/badge.svg)](https://travis-ci.org/Oefenweb/cakephp-uni-login) [![Coverage Status](https://codecov.io/gh/Oefenweb/cakephp-uni-login/branch/master/graph/badge.svg)](https://codecov.io/gh/Oefenweb/cakephp-uni-login) [![Packagist downloads](http://img.shields.io/packagist/dt/Oefenweb/cakephp-uni-login.svg)](https://packagist.org/packages/oefenweb/cakephp-uni-login) [![Code Climate](https://codeclimate.com/github/Oefenweb/cakephp-uni-login/badges/gpa.svg)](https://codeclimate.com/github/Oefenweb/cakephp-uni-login)

This plugin handles (single sign on) authentication with
[UNI•Login](http://www.stil.dk/It-og-administration/Brugere-og-adgangsstyring/For-laerere-og-elever). UNI•Login is a
service that provides authentication, access control and user administration to providers of web-based applications in
the educational sector.

## Requirements

* CakePHP 2.6.0 or greater.
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

### Minimal setup for UniLogin login procedure

```
class UsersController extends AppController {

	public function login_start() {
		$returnUrl = Router::url(['action' => 'login_complete']);
		$url = ['plugin' => 'uni_login', 'controller' => 'uni_login', 'action' => 'login', '?' => ['returnUrl' => $returnUrl]];
		return $this->redirect($url);
	}

	public function login_complete() {
		$secret = Configure::read('UniLogin.application.secret');
		if (!hash_equals($secret, $this->request->data('secret'))) {
			throw new ForbiddenException();
		}

		if ($this->request->data('validated') === true) {
			$key = $this->request->data('user');

			// Find application user by key and login user
		}
	}

}

```

The `UsersController::login_start` starts the UniLogin login procedure, the `UsersController::login_complete` handles the callback from UniLogin.
