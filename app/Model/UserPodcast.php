<?php
App::uses('AppModel', 'Model');

class UserPodcast extends AppModel {
	public $belongsTo = array('User', 'Podcast');
	public $useTable = 'users_podcasts';
}

?>