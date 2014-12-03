<?php
Configure::write('UniLogin.providerUrl', '/uni_login/test_provider/authenticate');
Configure::write('UniLogin.applicationId', '1');
Configure::write('UniLogin.secret', 'secret');

Configure::write('UniLogin.provider.defaultRedirectUrl', '/uni_login/uni_login/callback');
Configure::write('UniLogin.provider.applicationId', '1');
Configure::write('UniLogin.provider.testUser', 'testUser');