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
 * @return void
 */
	public function login() {
		// Default callback url
		$url = ['action' => 'callback'];
		$returnUrl = $this->request->query('returnUrl');
		if ($returnUrl) {
			$url['?'] = [
				'returnUrl' => Router::url($returnUrl)
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
		if ($user && $timestamp && $auth && (UniLoginUtil::hashEquals(UniLoginUtil::calculateFingerprint($timestamp, $user), $auth))) {
			$response['validated'] = true;
		} else {
			$response['validated'] = false;
		}

		// Redirect user to action in application with validated response data available as POST data retrievable at
		// `$this->request->data` at your app's controller.
		$completeUrl = Configure::read('UniLogin.application.completeUrl');

		$returnUrl = $this->request->query('returnUrl');
		if ($returnUrl) {
			$completeUrl = $returnUrl;
		}

		$response['secret'] = Configure::read('UniLogin.application.secret');

		$CakeRequest = new CakeRequest($completeUrl);
		$CakeRequest->data = $response;

		$Dispatcher = new Dispatcher();
		$Dispatcher->dispatch($CakeRequest, new CakeResponse());
		exit();
	}

}
