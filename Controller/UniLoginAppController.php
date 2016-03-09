<?php
App::uses('AppController', 'Controller');

/**
 * CakePHP plugin for UniLogin.
 *
 */
class UniLoginAppController extends AppController {

/**
 * An array of names of models to load.
 *
 * @var array
 */
	public $uses = [];

/**
 * Before action logic.
 *
 * @return void
 */
	public function beforeFilter() {
		// Allow access to Opauth methods for users of AuthComponent
		if (is_object($this->Auth) && method_exists($this->Auth, 'allow')) {
			$this->Auth->allow();
		}

		parent::beforeFilter();
	}

/**
 * Constructor.
 *
 * @param CakeRequest $request Request object for this controller. Can be null for testing,
 *  but expect that features that use the request parameters will not work.
 * @param CakeResponse $response Response object for this controller.
 */
	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);

		$this->autoRender = false;
	}

}
