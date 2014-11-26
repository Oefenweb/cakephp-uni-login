<?php
App::uses('AppController', 'Controller');
/**
 * CakePHP plugin for UniLogin
 */
class UniLoginAppController extends AppController {

	public $uses = array();

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
 * {@inheritDoc}
 */
	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);
		$this->autoRender = false;
	}

}