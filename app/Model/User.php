<?php
App::uses('AppModel', 'Model');

class User extends AppModel {
	var $cacheQueries = false;

	public $belongsTo = array('Episode' => array('foreignKey' => 'current_episode_id'));
	public $hasMany = array('UserPodcast');
	
	public $validate = array(
		'username' => array(
			'required' => array(
				'rule' => array('notBlank'),
				'message'=> 'A username is required'
			)
		), 
		'password' => array(
			'required' => array(
				'rule' => array('notBlank'),
				'message' => 'A password is required'
			)
		),
		'email' => array(
			'valid' => array(
				'rule' => array('notBlank', 'email', 'isUnique'),
				'message' => 'A valid email address is required'
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