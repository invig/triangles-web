<?php
App::uses('AppModel', 'Model');

class Feed extends AppModel {
	public $belongsTo = 'Podcast';
}

?>