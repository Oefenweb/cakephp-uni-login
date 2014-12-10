<?php
App::uses('UniLoginUtil', 'UniLogin.Lib');

/**
 * UniLogin Controller
 *
 */
class UniLoginController extends UniLoginAppController {

/**
 * An array of names of models to load.
 *
 * @var array
 */
	public $uses = array();

/**
 * Starts the Uni-Login login process (by redirecting the user to the authentication provider)
 *
 * @return void
 */
	public function login() {
		// default callback url
		$url = array('action' => 'callback');
		if ($returnUrl = $this->request->query('returnUrl')) {
			$url = $returnUrl;
		}

		$url = Router::url($url, true);

		$query = array(
			'path' => UniLoginUtil::encodeUrl($url),
			'auth' => UniLoginUtil::calculateUrlFingerprint($url),
			'id' => Configure::read('UniLogin.applicationId')
		);

		$redirectUrl = Configure::read('UniLogin.providerUrl');
		$redirectUrl .= '?' . http_build_query($query);

		return $this->redirect($redirectUrl);
	}

/**
 * Receives auth response and does validation
 *
 * @return void
 */
	public function callback() {
		$response = null;

		$response = $this->request->query;

		$user = $this->request->query('user');
		$timestamp = $this->request->query('timestamp');
		$auth = $this->request->query('auth');
		if ($user && $timestamp && $auth
				&& ($auth === UniLoginUtil::calculateFingerprint($timestamp, $user))) {
			$response['validated'] = true;
		} else {
			$response['validated'] = false;
		}

		// Redirect user to /users/uni_login_complete
		// with validated response data available as POST data
		// retrievable at $this->data at your app's controller
		$completeUrl = Configure::read('UniLogin._cakephp_plugin_complete_url');
		if (empty($completeUrl)) {
			$completeUrl = Router::url('/users/uni_login_complete');
		}

		$response['secret'] = Configure::read('UniLogin._cakephp_plugin_secret');

		$CakeRequest = new CakeRequest($completeUrl);
		$CakeRequest->data = $response;

		$Dispatcher = new Dispatcher();
		$Dispatcher->dispatch($CakeRequest, new CakeResponse());
		exit();
	}

}