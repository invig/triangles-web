<?php
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');

class PodcastsController extends AppController {
	public $uses = array('Podcast', 'Feed', 'UserPodcast', 'Episode');
	public $components = array('EpisodeFeed');
	
	public function beforeFilter() {
		parent::beforeFilter();
		// $this->Auth->allow();
	}
	
	public function index() {
		$podcasts = $this->UserPodcast->find('all', array(
			'conditions' => array(
				'UserPodcast.user_id' => $this->Auth->user('id')
			)
		));
		
		$this->set('users_podcasts', $podcasts);
		
	}

	private function create_feed($podcast_id, String $feed_url) {
		$this->Feed->create();
		$feed_metadata = array(
			'podcast_id' => $podcast_id,
			'url' => $feed_url,
			'active' => TRUE
		);
		$this->Feed->save($feed_metadata);
	}
		
	public function add() {
		if ($this->request->is('post')) {
			$feed_url = $this->request->data['Podcast']['url'];

			if(filter_var($feed_url, FILTER_VALIDATE_URL) === FALSE) {
				throw new BadRequestException(__('The URL for you have entered is invalid.'));	
			} 
			
			$podcast_feed_xml = Xml::build($feed_url);
			$podcast_feed_xml_array = Xml::toArray($podcast_feed_xml);				
			// CakeLog::write('debug', var_export($podcast_feed_xml_array, true));
			
			// Check to see if feed / podcast exists
			$podcast = $this->Podcast->find('first', array(
				'conditions' => array(
					'OR' => array(
						'title' => $podcast_feed_xml_array['rss']['channel']['title'],
						'link' => $podcast_feed_xml_array['rss']['channel']['title']
					)
				)
			));

			// if no then create the feed / podcast
			if (empty($podcast)) {
				// Create the podcast
				$this->Podcast->create();
				$podcast_metadata = array(
					'title' => $podcast_feed_xml_array['rss']['channel']['title'],
					'link' => $podcast_feed_xml_array['rss']['channel']['link'],
					'author' => $podcast_feed_xml_array['rss']['channel']['itunes:author'],
					'description' => $podcast_feed_xml_array['rss']['channel']['description'],
					'language' => $podcast_feed_xml_array['rss']['channel']['language'],
					'artwork' => $podcast_feed_xml_array['rss']['channel']['itunes:image']['@href']
				);
				$this->Podcast->save($podcast_metadata);
				$podcast_id = $this->Podcast->getLastInsertID();
				
				// Create the feeds
				$this->create_feed($podcast_id, $feed_url);
				$this->EpisodeFeed->importEpisodesFromFeedArray($podcast_feed_xml_array, $podcast_id);
			} else {
				$podcast_id = $podcast['Podcast']['id'];
				
				// Check for feeds
				$feeds = $this->Feed->find('all', array('conditions' => array(
					'podcast_id' => $podcast_id
				)));
				
				
				// If there isn't a feed then create the feed
				if (empty($feeds)) {
					$this->create_feed($podcast_id, $feed_url);
				} else {
					$feed_already_exists = FALSE;
					
					foreach ($feeds as $feed) {
						if ($feed['Feed']['url'] == $feed_url) {
							$feed_already_exists = TRUE;
						}
					}
					
					if ($feed_already_exists == FALSE) {
						$this->create_feed($podcast_id, $feed_url);
						$this->EpisodeFeed->importEpisodesFromFeedArray($podcast_feed_xml_array, $podcast_id);
					}
				}
			}

			// add the podcast to the user
			$this->UserPodcast->create();
			$user_podcast = array(
				'user_id' => $this->Auth->user('id'),
				'podcast_id' => $podcast_id,
				'subscribed' => TRUE,
				'auto_download' => TRUE
			);
			if ($this->UserPodcast->save($user_podcast)) {
				$this->Session->setFlash(__('Podcast added'));
			} else {
				$this->Session->setFlash(__('Failed to add podcast, please try again'));				
			}
			$this->redirect(array('controller'=>'podcasts','action'=>'index'));			
		} else {
			$this->redirect(array('controller'=>'podcasts','action'=>'index'));
		}
	}	
}
?>