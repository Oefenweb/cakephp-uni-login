<?php
App::uses('UniLoginUtil', 'UniLogin.Lib');

/**
 * UniLogin Controller.
 *
 */
class UniLoginController extends UniLoginAppController {

/**
 * An array of names of models to load.
 *
 * @var array
 */
	public $uses = [];

/**
 * Starts the Uni-Login login process (by redirecting the user to the authentication provider).
 *
 * @return \Cake\Network\Response|null
 */
	public function login() {
		// Default callback url
		$url = ['action' => 'callback'];
		$returnUrl = $this->request->query('returnUrl');
		if ($returnUrl) {
			$path = parse_url($returnUrl, PHP_URL_PATH);
			$url['?'] = [
				'returnUrl' => $path,
			];
		}

		$url = Router::url($url, true);

		$query = [
			'path' => UniLoginUtil::encodeUrl($url),
			'auth' => UniLoginUtil::calculateUrlFingerprint($url),
			'id' => Configure::read('UniLogin.provider.applicationId')
		];

		$redirectUrl = Configure::read('UniLogin.provider.url');
		$redirectUrl .= '?' . http_build_query($query);

		return $this->redirect($redirectUrl);
	}

/**
 * Receives auth response and does validation.
 *
 * @return void
 */
	public function callback() {
		$response = $this->request->query;

		$user = $this->request->query('user');
		$timestamp = $this->request->query('timestamp');
		$auth = $this->request->query('auth');

		$fingerprint = UniLoginUtil::calculateFingerprint($timestamp, $user);

		if ($user && $timestamp && $auth && UniLoginUtil::validateFingerprint($timestamp, $user, $fingerprint)) {
			$response['validated'] = true;
		} else {
			$response['validated'] = false;
		}

		$completeUrl = Configure::read('UniLogin.application.completeUrl');

		$returnUrl = $this->request->query('returnUrl');
		if ($returnUrl) {
			$path = parse_url($returnUrl, PHP_URL_PATH);
			$completeUrl = $path;
		}

		$response['hmac'] = UniLoginUtil::hmac($response);

		return $this->_dispatch($completeUrl, $response);
	}

/**
 * Redirects user to action in application with validated response data available as POST data retrievable at
 * $this->request->data` at your app's controller.
 *
 * @param string $url Url in application to dispatch to
 * @param array $data A list with post data
 * @return void
 */
	protected function _dispatch($url, $data) {
		$CakeRequest = new CakeRequest($url);
		$CakeRequest->data = $data;

		$Dispatcher = new Dispatcher();
		$Dispatcher->dispatch($CakeRequest, new CakeResponse());

		$this->_stop();
	}
}
