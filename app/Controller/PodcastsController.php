<?php
App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');
App::uses('Xml', 'Utility');

class PodcastsController extends AppController {
	public $uses = array(
		'Podcast',
		'Feed',
		'UserPodcast',
		'Episode',
		'Play',
		'User'
	);

	public $components = array('EpisodeFeed');


	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index');
	}
	
	public function index() {
		if ($this->Auth->user() == null) {
			$this->redirect(array('controller'=>'users','action'=>'login'));
		}

		// Fetch the users podcasts
		$podcasts = $this->UserPodcast->find('all', array(
			'conditions' => array(
				'UserPodcast.user_id' => $this->Auth->user('id')
			),
			'order' => array("Podcast.title" => "ASC")
		));
		$this->set('users_podcasts', $podcasts);

		// Find the most recently playing episode.
		$user = $this->User->findById($this->Auth->user('id'));
		if (isset($user['Episode']['id']) && ! empty($user['Episode']['id'])) {
			$current_episode = array("Episode" => $user['Episode']);
			$current_podcast = $this->Podcast->find('first', array(
				'fields' => array('id', 'artwork_url'),
				'recursive' => -1,
				'conditions' => array(
					'id' => $current_episode['Episode']['podcast_id']
				)
			));
			$current_episode['Podcast'] = $current_podcast['Podcast'];
			$this->set('current_episode', $current_episode);
		}
	}

	private function create_feed($podcast_id, $feed_url) {
		$this->Feed->create();
		$feed_metadata = array(
			'podcast_id' => $podcast_id,
			'url' => $feed_url,
			'active' => TRUE
		);
		$this->Feed->save($feed_metadata);
	}

	public function import () {
		if (isset($this->request->data['Podcast']['opml_file']['tmp_name']) &&
			!empty ($this->request->data['Podcast']['opml_file']['tmp_name'])) {

			$file_path = $this->request->data['Podcast']['opml_file']['tmp_name'];

			$xml = Xml::build($file_path);
			$opml = Xml::toArray($xml);

			$podcasts_to_import = $opml['opml']['body']['outline'];
			$completed_imports = array();
			$failed_imports = array();

			foreach ($podcasts_to_import as $podcast) {
				if ($this->add_from_url($podcast['@xmlUrl'])) {
					array_push($completed_imports, $podcast);
				} else {
					array_push($failed_imports, $podcast);
				}
			}

			// Alert the user of any podcasts that failed to import correctly.
			if (count($failed_imports) > 0) {
				$failed_message = "Failed to import:";

				foreach ($failed_imports as $failed_podcast) {
					$failed_message = $failed_message . " " . $failed_podcast['@title'] .",";
				}

				$failed_message = trim($failed_message, ",");
				$this->Session->setFlash($failed_message);
			} else {
				$this->Session->setFlash("Finished import. Episodes are being crawled and will appear shortly.");
			}

			// TODO: Trigger the feed crawler.

			$this->redirect(array("controller" => "podcasts", "action" => "index"));
		} else {
			$this->failed_import();
		}
	}

	private function failed_import() {
		$this->Session->setFlash("Error reading uploaded file.");
		$this->redirect(array("controller" => "podcasts", "action" => "index"));
	}
		
	public function add() {
		if ($this->request->is('post')) {
			$feed_url = $this->request->data['Podcast']['url'];

			if ($this->add_from_url($feed_url)) {
				$this->Session->setFlash(__('Podcast added'));

				// TODO: Trigger feed parse.
			} else {
				$this->Session->setFlash(__('Failed to add podcast, please try again'));
			}
		}

		$this->redirect(array('controller'=>'podcasts','action'=>'index'));
	}

	// Write our own version of feed fetching instead of the default Xml::build implementation because
	// of a bug in PHP that causes valid SSL certs where host names do not match to fail verification.
	// Work around is to set teh ssl_verify_host flag to false when making the request.
	private function get_feed($feed_url) {
		if (strpos($feed_url, 'http://') === 0 || strpos($feed_url, 'https://') === 0) {
			try {
				$socket = new HttpSocket(array(
					'request' => array('redirect' => 10),
					'ssl_verify_host'  => false,
					'ssl_allow_self_signed' => true
				));

				$response = $socket->get($feed_url);
				if (!$response->isOk()) {
					CakeLog::write('error', 'Response from feed: '. $feed_url .' was not ok.');
					throw new Exception('Response from feed was not ok.', $response->code);
				} else {
					$xml_string = $response->body;
					return Xml::build($xml_string);
				}
			} catch (SocketException $e) {
				CakeLog::write('error', 'Open socket to feed: '.$feed_url.' failed: ' . $e->getMessage() );
				throw new Exception( 'Connection to feed failed: ' . $e->getMessage(), $e->getCode() );
			}
		}
		return false;
	}

	private function add_from_url($feed_url) {
		if(filter_var($feed_url, FILTER_VALIDATE_URL) === FALSE) {
			throw new BadRequestException(__('The URL for you have entered is invalid.'));
		}

		// TODO: What if a podcast changes its name?

		$existing_feed = $this->Feed->find('first', array(
			'conditions' => array(
				'url' => $feed_url
			)
		));

		if (isset($existing_feed)  && ! empty($existing_feed)) {
			$podcast['Podcast'] = $existing_feed['Podcast'];
		} else {
			try {
				$response = $this->get_feed($feed_url);
				if ($response == false) {
					CakeLog::write('error', "Failed to read feed at URL: " .  $feed_url);
				}
				$podcast_feed_xml_array = Xml::toArray($response);
			} catch (Exception $e) {
				CakeLog::write('error', "Failed to import feed at URL: " .  $feed_url);
				return false;
			}

			// Check to see if feed / podcast exists
			$podcast = $this->Podcast->find('first', array(
				'conditions' => array(
					'OR' => array(
						'title' => $podcast_feed_xml_array['rss']['channel']['title'],
					)
				)
			));
		}

		// if no then create the feed / podcast
		if (empty($podcast)) {
			// Create the podcast
			$this->Podcast->create();
			$podcast_metadata = array();

			if (isset($podcast_feed_xml_array['rss']['channel']['description'])) {
				$description = $podcast_feed_xml_array['rss']['channel']['description'];
				$podcast_metadata['description'] = $description;
			}

			if (isset($podcast_feed_xml_array['rss']['channel']['itunes:image']['@href'])) {
				$artwork_url = $podcast_feed_xml_array['rss']['channel']['itunes:image']['@href'];
				$podcast_metadata['artwork_url'] = $artwork_url;
			}

			if (isset($podcast_feed_xml_array['rss']['channel']['title'])) {
				$title = $podcast_feed_xml_array['rss']['channel']['title'];
				$podcast_metadata['title'] = $title;
			}

			if (isset($podcast_feed_xml_array['rss']['channel']['link'])) {
				$website_url = $podcast_feed_xml_array['rss']['channel']['link'];
				$podcast_metadata['website_url'] = $website_url;
			}

			if (isset($podcast_feed_xml_array['rss']['channel']['itunes:author'])) {
				$author = $podcast_feed_xml_array['rss']['channel']['itunes:author'];
				$podcast_metadata['author'] = $author;
			}

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
		$this->UserPodcast->set($user_podcast);
		if ($this->UserPodcast->validates()) {
			if ($this->UserPodcast->save($user_podcast)) {
				return true;
			} else {
				return false;
			}
		} else {
			CakeLog::write('debug', var_export($this->UserPodcast->validationErrors, true));
			return true;
		}
	}
}
?>