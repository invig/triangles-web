<?php
App::uses('AppModel', 'Model');

class Play extends AppModel {
	public $belongsTo = array('User', 'Episode');
}

?>