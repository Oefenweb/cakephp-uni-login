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
 * @param string $formattedTimestamp Uni-Login formatted timestamp (YYYYMMDDhhmmss)
 * @param string $user A username
 * @return string
 */
	public static function calculateFingerprint($formattedTimestamp, $user) {
		$secret = Configure::read('UniLogin.secret');
		return md5($formattedTimestamp . $secret . $user);
	}

/**
 * Validates Uni Login fingerprint
 *
 * @param string $formattedTimestamp Uni-Login formatted timestamp (YYYYMMDDhhmmss)
 * @param string $user A username
 * @param string $fingerprint Fingerprint to validate
 * @return bool
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

/**
 * Formats given (or current) timestamp to Uni-Login formatted timestamp
 *
 * @param string $timestamp Unix timestamp
 * @return string Uni-Login formatted timestamp (YYYYMMDDhhmmss)
 */
	public static function getFormattedTimestamp($timestamp = null) {
		$restore = self::_switchTimeZone();

		if ($timestamp === null) {
			$timestamp = time();
		}
		$result = date(self::TIMESTAMP_FORMAT, $timestamp);

		self::_switchTimeZone($restore);

		return $result;
	}

/**
 * Parses given Uni-Login timestamp
 *
 * @param string $formattedTimestamp Uni-login formatted timestamp
 * @return mixed Returns a timestamp on success, false otherwise
 */
	public static function parseFormattedTimestamp($formattedTimestamp) {
		$restore = self::_switchTimeZone();

		$result = strtotime($formattedTimestamp);

		self::_switchTimeZone($restore);

		return $result;
	}

/**
 * Returns url of the authentication provider
 *
 * @return string Url of the authentication provider
 */
	public static function getProviderUrl() {
		$url = Configure::read('UniLogin.providerUrl');
		$applicationId = Configure::read('UniLogin.applicationId');
		$url = sprintf($url, $applicationId);
		return $url;
	}

/**
 * Calculates fingerprint for given url
 *
 * @param string $url Url to create fingerprint for
 * @return string Fingerprint
 */
	public static function calculateUrlFingerprint($url) {
		$secret = Configure::read('UniLogin.secret');
		return md5($url . $secret);
	}

/**
 * Decodes given url
 *
 * @param string $url Encoded url
 * @return string Decoded url
 */
	public static function decodeUrl($url) {
		return base64_decode(urldecode($url));
	}

/**
 * Encodes given url
 *
 * @param string $url Decoded url
 * @return string Encoded url
 */
	public static function encodeUrl($url) {
		return base64_encode($url);
	}

/**
 * Validates Uni-Login fingerprint for given url
 *
 * @param string $url Given url
 * @param string $fingerprint Fingerprint to validate
 * @return bool Whether or not the fingerprint validates
 */
	public static function validateUrlFingerprint($url, $fingerprint) {
		return (self::calculateUrlFingerprint($url) === $fingerprint);
	}

}