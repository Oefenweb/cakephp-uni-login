<?php
App::uses('TestProviderController', 'UniLogin.Controller');
App::uses('UniLoginUtil', 'UniLogin.Lib');

/**
 * TestProviderController Test Case
 *
 */
class TestProviderControllerTest extends ControllerTestCase {

	protected $_restore = null;

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->_restore = Configure::read('UniLogin.provider');
		Configure::write('UniLogin.provider.defaultRedirectUrl', 'http://www.example.com/redirectUrl');
		Configure::write('UniLogin.provider.applicationId', 'myApplicationId');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		Configure::write('UniLogin.provider', $this->_restore);

		parent::tearDown();
	}

/**
 * testAuthenticateDefaultUrl method
 *
 * @return void
 */
	public function testAuthenticateDefaultUrl() {
		$defaultRedirectUrl = 'http://www.example.com/redirectUrl';

		$this->testAction('/uni_login/test_provider/authenticate', array(
			'data' => array(
				'id' => 'myApplicationId'
			),
			'method' => 'get'
		));

		$this->assertContains($defaultRedirectUrl, $this->headers['Location']);
		$this->assertContains('user=', $this->headers['Location']);
		$this->assertContains('timestamp=', $this->headers['Location']);
		$this->assertContains('auth=', $this->headers['Location']);
	}

/**
 * testAuthenticateDefaultUrlWithoutApplicationId method
 *
 * @return void
 */
	public function testAuthenticateDefaultUrlWithoutApplicationId() {
		$defaultRedirectUrl = 'http://www.example.com/redirectUrl';

		$this->testAction('/uni_login/test_provider/authenticate', array(
			'method' => 'get'
		));

		$this->assertContains($defaultRedirectUrl, $this->headers['Location']);
		$this->assertNotContains('user=', $this->headers['Location']);
		$this->assertNotContains('timestamp=', $this->headers['Location']);
		$this->assertNotContains('auth=', $this->headers['Location']);
	}

/**
 * testAuthenticateRedirectUrlParameterWithoutApplicationId method
 *
 * @return void
 */
	public function testAuthenticateRedirectUrlParameter() {
		$url = 'http://www.mydomain.com';
		$path = UniLoginUtil::encodeUrl($url);
		$auth = UniLoginUtil::calculateUrlFingerprint($url);
		$this->testAction('/uni_login/test_provider/authenticate', array(
			'data' => array(
				'id' => 'myApplicationId',
				'path' => $path,
				'auth' => $auth
			),
			'method' => 'get'
		));

		$this->assertContains($url, $this->headers['Location']);
		$this->assertContains('user=', $this->headers['Location']);
		$this->assertContains('timestamp=', $this->headers['Location']);
		$this->assertContains('auth=', $this->headers['Location']);
	}

/**
 * testAuthenticateRedirectUrlParameterWithoutApplicationId method
 *
 * @return void
 */
	public function testAuthenticateRedirectUrlParameterWithoutApplicationId() {
		$url = 'http://www.mydomain.com';
		$path = UniLoginUtil::encodeUrl($url);
		$auth = UniLoginUtil::calculateUrlFingerprint($url);
		$this->testAction('/uni_login/test_provider/authenticate', array(
			'data' => array(
				'path' => $path,
				'auth' => $auth
			),
			'method' => 'get'
		));

		$this->assertContains($url, $this->headers['Location']);
		$this->assertNotContains('user=', $this->headers['Location']);
		$this->assertNotContains('timestamp=', $this->headers['Location']);
		$this->assertNotContains('auth=', $this->headers['Location']);
	}

}