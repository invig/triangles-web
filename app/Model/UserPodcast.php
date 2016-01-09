<?php
App::uses('AppModel', 'Model');

class UserPodcast extends AppModel {
	public $belongsTo = array(
		'User',
		'Podcast'
	);

	public $useTable = 'users_podcasts';

	public $validate = array(
		'user_id' => array(
			'rule' => 'uniqueUserCombination',
			'message' => 'The Podcast has already been added for this User.'
		)
	);

	function uniqueUserCombination($check) {
		$count = $this->find('count', array(
			'conditions' => array(
				'user_id' => $this->data['UserPodcast']['user_id'],
				'podcast_id' => $this->data['UserPodcast']['podcast_id'],
			)
		));

		return $count==0;
	}
}

?>