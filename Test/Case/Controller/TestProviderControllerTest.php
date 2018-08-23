<?php
App::uses('TestProviderController', 'UniLogin.Controller');
App::uses('UniLoginUtil', 'UniLogin.Lib');

/**
 * TestProviderController Test Case.
 *
 */
class TestProviderControllerTest extends ControllerTestCase {

/**
 * Contains the original plugin configuration.
 *
 * @var array|null
 */
	protected $_restore = null;

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->_restore = Configure::read('UniLogin.testProvider');

		Configure::write('UniLogin.testProvider.defaultRedirectUrl', 'http://www.example.com/redirectUrl');
		Configure::write('UniLogin.testProvider.applicationId', 'myApplicationId');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		Configure::write('UniLogin.testProvider', $this->_restore);

		parent::tearDown();
	}

/**
 * Tests `/uni_login/test_provider/authenticate`.
 *
 *  Default URL.
 *
 * @return void
 */
	public function testAuthenticateDefaultUrl() {
		$defaultRedirectUrl = 'http://www.example.com/redirectUrl';

		$this->testAction('/uni_login/test_provider/authenticate', [
			'data' => [
				'id' => 'myApplicationId',
			],
			'method' => 'get',
		]);

		$this->assertContains($defaultRedirectUrl, $this->headers['Location']);
		$this->assertContains('user=', $this->headers['Location']);
		$this->assertContains('timestamp=', $this->headers['Location']);
		$this->assertContains('auth=', $this->headers['Location']);
	}

/**
 * Tests `/uni_login/test_provider/authenticate`.
 *
 *  Default URL without application ID.
 *
 * @return void
 */
	public function testAuthenticateDefaultUrlWithoutApplicationId() {
		$defaultRedirectUrl = 'http://www.example.com/redirectUrl';

		$this->testAction('/uni_login/test_provider/authenticate', [
			'method' => 'get',
		]);

		$this->assertContains($defaultRedirectUrl, $this->headers['Location']);
		$this->assertNotContains('user=', $this->headers['Location']);
		$this->assertNotContains('timestamp=', $this->headers['Location']);
		$this->assertNotContains('auth=', $this->headers['Location']);
	}

/**
 * Tests `/uni_login/test_provider/authenticate`.
 *
 *  Redirect URL parameter.
 *
 * @return void
 */
	public function testAuthenticateRedirectUrlParameter() {
		$url = 'http://www.mydomain.com';
		$path = UniLoginUtil::encodeUrl($url);
		$auth = UniLoginUtil::calculateUrlFingerprint($url);
		$this->testAction('/uni_login/test_provider/authenticate', [
			'data' => [
				'id' => 'myApplicationId',
				'path' => $path,
				'auth' => $auth,
			],
			'method' => 'get',
		]);

		$this->assertContains($url, $this->headers['Location']);
		$this->assertContains('user=', $this->headers['Location']);
		$this->assertContains('timestamp=', $this->headers['Location']);
		$this->assertContains('auth=', $this->headers['Location']);
	}

/**
 * Tests `/uni_login/test_provider/authenticate`.
 *
 *  Redirect URL parameter without application ID.
 *
 * @return void
 */
	public function testAuthenticateRedirectUrlParameterWithoutApplicationId() {
		$url = 'http://www.mydomain.com';
		$path = UniLoginUtil::encodeUrl($url);
		$auth = UniLoginUtil::calculateUrlFingerprint($url);
		$this->testAction('/uni_login/test_provider/authenticate', [
			'data' => [
				'path' => $path,
				'auth' => $auth,
			],
			'method' => 'get'
		]);

		$this->assertContains($url, $this->headers['Location']);
		$this->assertNotContains('user=', $this->headers['Location']);
		$this->assertNotContains('timestamp=', $this->headers['Location']);
		$this->assertNotContains('auth=', $this->headers['Location']);
	}
}
