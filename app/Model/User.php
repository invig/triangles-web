<?php
App::uses('AppModel', 'Model');

class User extends AppModel {
	var $cacheQueries = false;
	
	public $hasMany = array('UserPodcast');
	
	public $validate = array(
		'username' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message'=> 'A username is required'
			)
		), 
		'password' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'A password is required'
			)
		),
		'role' => array(
			'valid' => array(
				'rule' => array('inList', array('admin','user')),
				'message' => 'Please enter a valid role',
				'allowEmpty' => false
			)
		), 
		'email' => array(
			'valid' => array(
				'rule' => array('notEmpty', 'email'),
				'message' => 'An email address is required'
			)
		)
	);

	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		return true;
	}
}

?>