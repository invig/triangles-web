<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('login', 'signup');
	}
	
	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$this->redirect(array('controller'=>'podcasts','action'=>'index'));
				
				// $this->redirect($this->Auth->redirect());
			} else {
				$this->Session->setFlash(__('Invalid username or password, try again'));
			}
		}
	}
	
	public function logout() { 
		$this->redirect($this->Auth->logout());
	}
	
	public function signup() {
		Cache::clear();
		
		if ($this->request->is('post')) {
			$this->request->data['User']['role'] = "user";
			$this->request->data['User']['verified'] = 0;
			$this->request->data['User']['signup_date'] = date("Y-m-d H:i:s");
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('Your account has been created'));
				$this->redirect(array('controller'=>'podcasts','action'=>'index'));
			} else {
				$this->Session->setFlash(__('Your account could not be created. Please, try again.'));
			}
		}
	}
	
	public function reset_password($id = null) {
		throw new NotImplementedException(__('Password reset is not yet implemented'));	
	}
	
	public function edit($username = null) {
		// Note: There isn't any admin functionality yet. You can't edit another users account. 
		
		if (empty($username)) {
			$username = $this->Auth->user('username');
		}
		
		// Allow admins to edit other users, but anyone else to only edit their own user.
		if ($username != $this->Auth->user('username')) {
			if ($this->Auth->user('role') != 'Admin') {
				throw new ForbiddenException(__('Unauthorised action'));
			}
		}
		
		// If their is no user - reject.		
	    $user = $this->User->find('first', array(
	        'conditions' => array('User.username' => $username)
	    ));
		$id = $user['User']['id'];
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		
		$this->User->read(null, $id);
	   
		if ($this->request->is('post') || $this->request->is('put')) {
			
			// If the email address changes re-verify
			if (strcmp($this->User->data['User']['email'], $this->request->data['User']['email']) != 0) {
				$this->request->data['User']['verified'] = 0;
			}
		
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('Your profile has been updated.'));
				$this->redirect(array('action'=>'edit', $username));
			} else {
				$this->Session->setFlash(__("Uh oh, we couldn't save your changes. Please, try again."));
			}
		} else {
			unset($this->User->data['User']['password']);
			$this->request->data = $this->User->data;
			$this->set('user', $this->User);

			// The user can change their level if authorised to.
			if ($this->isAuthorized($this->User->data['User'])) {
				$this->set('isAuthorized', true);
			} else {
				$this->set('isAuthorized', false);
			}			
		}
	}
	
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		
		// Allow admins to delete other users, but anyone else to only delete their own user.
		if ($id != $this->Auth->user('id')) {
			if ($this->Auth->user('role') != 'Admin') {
				throw new ForbiddenException(__('Unauthorised action'));
			}
		}
		
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		
		if ($this->User->delete()) {
			$this->Session->setFlash(__("User deleted"));
			$this->redirect(array("action"=>"index"));
		}
		
		$this->Session->setFlash(__("User was not deleted"));
		$this->redirect(array("action"=>"index"));
	}
}
?>