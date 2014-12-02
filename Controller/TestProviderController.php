<?php
App::uses('UniLoginUtil', 'UniLogin.Lib');

/**
 * TestProvider Controller
 *
 */
class TestProviderController extends UniLoginAppController {

/**
 * Creates the redirect url based on query-parameter of configuration
 *
 * @return mixed
 */
	protected function _getRedirectUrl() {
		$path = $this->request->query('path');
		$fingerprint = $this->request->query('auth');
		if ($path && $fingerprint) {
			$url = UniLoginUtil::decodeUrl($path);
			if (UniLoginUtil::validateUrlFingerprint($url, $fingerprint)) {
				$redirectUrl = $url;
			}
		}

		if (empty($redirectUrl)) {
			$redirectUrl = Configure::read('UniLogin.provider.defaultRedirectUrl');
		}

		return $redirectUrl;
	}

/**
 * Handles authentication requests
 *
 * @return void
 */
	public function authenticate() {
		$applicationId = $this->request->query('id');

		$redirectUrl = $this->_getRedirectUrl();

		$timestamp = UniLoginUtil::getFormattedTimestamp();
		$user = Configure::read('UniLogin.provider.testUser');
		$auth = UniLoginUtil::calculateFingerprint($timestamp, $user);

		if ($applicationId === Configure::read('UniLogin.provider.applicationId')) {
			$query = array(
				'user' => $user,
				'timestamp' => $timestamp,
				'auth' => $auth
			);
			$redirectUrl .= '?' . http_build_query($query);
		}

		return $this->redirect($redirectUrl);
	}

}