<?php

/**
 * TestUniLoginControllerTest Test Case.
 *
 */
class TestUniLoginControllerTest extends ControllerTestCase {

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

		$this->_restore = Configure::read('UniLogin');

		Configure::write('UniLogin.application.completeUrl', '/completeUrl');
		Configure::write('UniLogin.application.secret', 'secret');
		Configure::write('UniLogin.provider.applicationId', 123456789);
		Configure::write('UniLogin.provider.url', 'http://www.example.org/redirectUrl');
		Configure::write('UniLogin.provider.secret', 'abc123');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		Configure::write('UniLogin', $this->_restore);

		parent::tearDown();
	}

/**
 * testLogin method
 *
 * @return void
 */
	public function testLogin() {
		$this->testAction('/uni_login/uni_login/login', ['method' => 'get']);

		$this->assertStringStartsWith('http://www.example.org/redirectUrl?path=', $this->headers['Location']);
		$this->assertStringEndsWith('&id=123456789', $this->headers['Location']);
	}

/**
 * testCallbackNotValid method
 *
 * @return void
 */
	public function testCallbackNotValid() {
		$UniLogin = $this->generate('UniLogin', ['methods' => ['_dispatch']]);

		$UniLogin->expects($this->once())
			->method('_dispatch')
			->with('/completeUrl', ['validated' => false, 'secret' => 'secret']);

		$this->testAction('/uni_login/uni_login/callback', ['method' => 'get']);
	}

/**
 * testCallbackValid method
 *
 * @return void
 */
	public function testCallbackValid() {
		$UniLogin = $this->generate('UniLogin', ['methods' => ['_dispatch']]);

		$timestamp = time();
		$data = [
			'user' => 'user',
			'timestamp' => $timestamp,
			'auth' => md5($timestamp . 'abc123' . 'user')
		];

		$UniLogin->expects($this->once())
			->method('_dispatch')
			->with('/completeUrl', array_merge($data, ['validated' => true, 'secret' => 'secret']));

		$this->testAction('/uni_login/uni_login/callback', ['method' => 'get', 'data' => $data]);
	}

}
