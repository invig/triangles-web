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
	public $components = array('DebugKit.Toolbar','Session', 
		'Auth' => array(
	        'authenticate' => array(
	            'Digest' => array(
	                'fields' => array('username' => 'email')
	            ),
	            'Form' => array(
	                'fields' => array('username' => 'email')
	            )
	        ),
			'loginRedirect' => array('controller'=>'podcasts', 'action'=>'index'),
			'logoutRedirect' => array('controller'=>'users', 'action'=>'fail'),
			'authorize'=>array('Controller')
		)
	);
	
	public function beforeFilter() {		
		parent::beforeFilter();
		$this->set('logged_in', $this->Auth->loggedIn());
		$this->set('role', $this->Auth->user('role'));
	}
	
	
	public function isAuthorized($user) {
		if($user['verified'] == 1) {
			if (isset($user['role']) && ($user['role'] == 'admin' || $user['role'] == 'user')) {
				return true;
			} else {
				$this->Session->setFlash(__("You don't have permission to perform this action."));							
			}			
		} else {
			$this->Session->setFlash(__("Your account isn't verified. Please check your email!"));				
		}
		
		return false;
	}
}