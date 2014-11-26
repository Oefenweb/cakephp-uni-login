<?php
/**
 * UniLogin Utility class
 *
 */
class UniLoginUtil {

	// YYYYMMDDhhmmss
	const TIMESTAMP_FORMAT = 'YmdHis';

	const FINGERPRINT_TIMEOUT = '-1 minute';

/**
 * Switch the time zone.
 *
 * @param string $timeZone The new time zone identifier
 * @return string The old time zone identifier
 * @todo Time zone should be application wide set to UTC (in core.php)?
 */
	protected static function _switchTimeZone($timeZone = 'UTC') {
		$restore = date_default_timezone_get();
		date_default_timezone_set($timeZone);
		return $restore;
	}

/**
 * Calculate Uni Login fingerprint
 *
 * @param string $timestamp
 * @param string $user
 * @return string
 */
	public static function calculateFingerprint($formattedTimestamp, $user) {
		$secret = Configure::read('UniLogin.secret');
		return md5($formattedTimestamp . $secret . $user);
	}

/**
 * Validate Uni Login fingerprint
 *
 * @param unknown $formattedTimestamp
 * @param unknown $user
 * @param unknown $fingerprint
 * @return boolean
 */
	public static function validateFingerprint($formattedTimestamp, $user, $fingerprint) {
		$restore = self::_switchTimeZone();

		$isValid = false;
		$now = time();
		$timestamp = self::parseFormattedTimestamp($formattedTimestamp);

		// given timestamp should be between 10 minutes ago and now
		if (($timestamp <= $now) && ($timestamp > strtotime(self::FINGERPRINT_TIMEOUT, $now))) {
			// check fingerprint
			if (self::calculateFingerprint($formattedTimestamp, $user) === $fingerprint) {
				$isValid = true;
			}
		}

		self::_switchTimeZone($restore);

		return $isValid;
	}

	public static function getFormattedTimestamp($timestamp = null) {
		$restore = self::_switchTimeZone();

		if ($timestamp === null) {
			$timestamp = time();
		}
		$result = date(self::TIMESTAMP_FORMAT, $timestamp);

		self::_switchTimeZone($restore);

		return $result;
	}

	public static function parseFormattedTimestamp($formattedTimestamp) {
		$restore = self::_switchTimeZone();

		$result = strtotime($formattedTimestamp);

		self::_switchTimeZone($restore);

		return $result;
	}

	public static function getProviderUrl() {
		$url = Configure::read('UniLogin.providerUrl');
		$applicationId = Configure::read('UniLogin.applicationId');
		$url = sprintf($url, $applicationId);
		return $url;
	}

	public static function calculateUrlFingerprint($url) {
		$secret = Configure::read('UniLogin.secret');
		return md5($url . $secret);
	}

	public static function decodeUrl($url) {
		return base64_decode(urldecode($url));
	}

	public static function encodeUrl($url) {
		return urlencode(base64_encode($url));
	}

/**
 * Validate Uni Login url fingerprint
 *
 * @param unknown $url
 * @param unknown $fingerprint
 * @return boolean
 */
	public static function validateUrlFingerprint($url, $fingerprint) {
		return (self::calculateUrlFingerprint($url) === $fingerprint);
	}

}