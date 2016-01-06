<?php
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');

class FeedsController extends AppController {
	public $uses = array('Podcast', 'Episode', 'Feed');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow();

		//TODO: Setup a custom auth level for trigger parses.
	}
	
	public function index() {
		
	}

	public function parse_all_feeds() {
		CakeLog::write('debug', 'parsing all feeds');
		$feeds = $this->Feed->find('all', array(
			'conditions' => array(
				'active' => true
			),
			'fields' => array(
				'id','podcast_id','url'
			)
		));

		CakeLog::write('debug', 'parse_all_feeds -> Feeds: ' . var_export($feeds, true));

		foreach ($feeds as $feed) {
			$this->parse_feed($feed);
		}
	}

	private function parse_feed($feed, Array $return_action = null) {
		$failures = array();
		CakeLog::write('debug', 'parse_feed -> Feed ID: ' . $feed['Feed']['id']);
		if (isset($feed) && !empty($feed)) {
			$podcast_feed_xml = Xml::build($feed['Feed']['url']);
			$podcast_feed_xml_array = Xml::toArray($podcast_feed_xml);
			$podcast_feed_xml_array = $podcast_feed_xml_array['rss']['channel'];
//			CakeLog::write('debug', var_export($podcast_feed_xml_array, true));
			CakeLog::write('debug', var_export($feed, true));

			// TODO: Process the feed.

			// Get the podcast so we can build the episodes.
			$podcast = $this->Podcast->find('first', array(
				'conditions' => array(
					'id' => $feed['Feed']['podcast_id'],
				)
			));

			// Catch fetching where no podcast exists.
			if (!isset($podcast) || empty($podcast)) {
				CakeLog::write('debug', 'parse_feed -> FAILED TO PARSE FEED WITH ID: ' . $feed['Feed']['id']);
				return false;
			}


			// Fetch our list of existing episodes in one query.
			$existing_episodes = $this->Episode->find('all', array(
				'conditions' => array(
					'podcast_id' => $podcast['Podcast']['id']
				),
				'fields' => array('id','guid', 'title')
			));

			// Build the episodes
			$episodes_xml_array = $podcast_feed_xml_array['item'];

			foreach ($episodes_xml_array as $episode_xml_array) {
				// Match on any existing episodes
				$episode_xml_guid = $episode_xml_array['guid'];
				$matched_episode = $this->match_episode($episode_xml_guid, $existing_episodes);

				// TODO: Do we need to worry about updated feed items?
				// TODO: Each episode can have different authors?
				if ($matched_episode == false) {
					// Create the episode
					$pubDate = date('Y-m-d H:i:s', strtotime($episode_xml_array['pubDate']));

					$this->Episode->create();
					$episode_data = array(
						'podcast_id' => $podcast['Podcast']['id'],
						'url' => $episode_xml_array['enclosure']['@url'],
						'title' => $episode_xml_array['title'],
						'description' => $episode_xml_array['description'],
						'episode_length' => $episode_xml_array['itunes:duration'],
						'episode_date' => $pubDate,
						'shownotes' => $episode_xml_array['content:encoded'],
						'guid' => $episode_xml_array['guid']
					);

					$this->Episode->save($episode_data);
				}
			}

		} else {
			CakeLog::write('debug', 'parse_feed -> NO FEED PROVIDED');
			return false;
		}

		return true;
	}

	// Returns the matched episode or false.
	private function match_episode($guid, Array $episodes) {
		if (! empty($episodes)) {
			foreach ($episodes as $episode) {
				if ($episode['Episode']['guid'] == $guid) {
					return $episode;
				}
			}
		}
		return false;
	}
}