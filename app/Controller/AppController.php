<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */

class AppController extends Controller {

	public $components = array('Session', 'Auth');

	public function beforeFilter() {
		parent::beforeFilter();

		// Handle basic auth for API.
		if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'json') {
				$this->Auth->authenticate = array('Basic' => array(
						'fields' => array('username' => 'email')
				));

				if (!$this->Auth->login()) {
						$data = array (
								'status' => 400,
								'message' => $this->Auth->authError,
						);
						$this->set('data', $data);
						$this->set('_serialize', 'data');

						$this->viewClass = 'Json';
						$this->render();
				}
		}

		$this->set('logged_in', $this->Auth->loggedIn());
	}

	public function isAuthorized($user) {
		return true;
	}
}
