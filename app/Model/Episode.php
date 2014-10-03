<?php
App::uses('AppModel', 'Model');

class Episode extends AppModel {
	public $belongsTo = array('Podcast');
}

?>