<?php
App::uses('AppModel', 'Model');

class Play extends AppModel {
	public $belongsTo = array('User');
	public $hasOne = array('Episode');
}

?>