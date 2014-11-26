<?php
App::uses('View', 'View');
App::uses('UniLoginUtil', 'UniLogin.Lib');

/**
 * UniLoginUtilTest class
 */
class UniLoginUtilTest extends CakeTestCase {

	protected $_restore = null;

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->_restore = Configure::read('UniLogin');
		Configure::write('UniLogin.secret', 'abc123');
		Configure::write('UniLogin.providerUrl', 'https://sso.emu.dk/unilogin/login.cgi?id=%s');
		Configure::write('UniLogin.applicationId', 'myId');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		Configure::write('UniLogin', $this->_restore);
	}

/**
 * testCalculateFingerprint method
 *
 * @return void
 */
	public function testCalculateFingerprint() {
		$timestamp = '20030505125952';
		$user = 'testuser';
		$expected = '5e55280df202c8820a7092746b991088';
		$result = UniLoginUtil::calculateFingerprint($timestamp, $user);
		$this->assertEquals($expected, $result);
	}

/**
 * testGetTimestamp method
 *
 * @return void
 */
	public function testGetTimestamp() {
		$timestamp = 1052139592;
		$expected = '20030505125952';
		$result = UniLoginUtil::getFormattedTimestamp($timestamp);
		$this->assertEquals($expected, $result);
	}

/**
 * testParseTimestamp method
 *
 * @return void
 */
	public function testParseTimestamp() {
		$timestamp = '20030505125952';
		$expected = 1052139592;
		$result = UniLoginUtil::parseFormattedTimestamp($timestamp);
		$this->assertEquals($expected, $result);
	}

/**
 * testValidateFingerprint method
 *
 * @return void
 */
	public function testValidateFingerprint() {
		//
		// good timestamp
		//
		$timestamp = time();
		$formattedTimestamp = UniLoginUtil::getFormattedTimestamp($timestamp);
		$user = 'testuser';
		$fingerprint = UniLoginUtil::calculateFingerprint($formattedTimestamp, $user);
		$result = UniLoginUtil::validateFingerprint($formattedTimestamp, $user, $fingerprint);
		$this->assertTrue($result);

		//
		// timestamp in the future
		//
		$timestamp = strtotime('+5 minutes');
		$formattedTimestamp = UniLoginUtil::getFormattedTimestamp($timestamp);
		$user = 'testuser';
		$fingerprint = UniLoginUtil::calculateFingerprint($formattedTimestamp, $user);
		$result = UniLoginUtil::validateFingerprint($formattedTimestamp, $user, $fingerprint);
		$this->assertFalse($result);

		//
		// timestamp in the past within 1 minute
		//
		$timestamp = strtotime('-30 seconds');
		$formattedTimestamp = UniLoginUtil::getFormattedTimestamp($timestamp);
		$user = 'testuser';
		$fingerprint = UniLoginUtil::calculateFingerprint($formattedTimestamp, $user);
		$result = UniLoginUtil::validateFingerprint($formattedTimestamp, $user, $fingerprint);
		$this->assertTrue($result);

		//
		// timestamp in the past more than 1 minute ago
		//
		$timestamp = strtotime('-2 minutes');
		$formattedTimestamp = UniLoginUtil::getFormattedTimestamp($timestamp);
		$user = 'testuser';
		$fingerprint = UniLoginUtil::calculateFingerprint($formattedTimestamp, $user);
		$result = UniLoginUtil::validateFingerprint($formattedTimestamp, $user, $fingerprint);
		$this->assertFalse($result);
	}

/**
 * testGetProviderUrl method
 *
 * @return void
 */
	public function testGetProviderUrl() {
		$expected = 'https://sso.emu.dk/unilogin/login.cgi?id=myId';
		$result = UniLoginUtil::getProviderUrl();
		$this->assertEquals($expected, $result);
	}

/**
 * testCalculateUrlFingerprint method
 *
 * @return void
 */
	public function testCalculateUrlFingerprint() {
		$url = 'http://www.emu.dk/appl';
		$expected = '59169cb39fab40cb0ad6ade6a6eb491e';
		$result = UniLoginUtil::calculateUrlFingerprint($url);
		$this->assertEquals($expected, $result);
	}

/**
 * testDecodeUrl method
 *
 * @return void
 */
	public function testDecodeUrl() {
		$url = 'aHR0cDovL3d3dy5lbXUuZGsvYXBwbA%3D%3D';
		$expected = 'http://www.emu.dk/appl';
		$result = UniLoginUtil::decodeUrl($url);
		$this->assertEquals($expected, $result);
	}

/**
 * testEncodeUrl method
 *
 * @return void
 */
	public function testEncodeUrl() {
		$url = 'http://www.emu.dk/appl';
		$expected = 'aHR0cDovL3d3dy5lbXUuZGsvYXBwbA%3D%3D';
		$result = UniLoginUtil::encodeUrl($url);
		$this->assertEquals($expected, $result);
	}

/**
 * testValidateUrlFingerprint method
 *
 * @return void
 */
	public function testValidateUrlFingerprint() {
		$url = 'http://www.emu.dk/appl';
		$fingerprint = '59169cb39fab40cb0ad6ade6a6eb491e';
		$result = UniLoginUtil::validateUrlFingerprint($url, $fingerprint);
		$this->assertTrue($result);
	}

}