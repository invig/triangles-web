<?php
App::uses('AppModel', 'Model');

class Podcast extends AppModel {
	public $hasMany = array('Feed', 'Episode', 'UserPodcast');
}

?>