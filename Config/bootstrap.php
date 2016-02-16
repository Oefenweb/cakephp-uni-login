<?php
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
