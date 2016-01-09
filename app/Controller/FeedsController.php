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
		$this->autoRender = false;

		$feeds = $this->Feed->find('all', array(
			'conditions' => array(
				'active' => true
			),
			'fields' => array(
				'id','podcast_id','url'
			)
		));

		$failed_fetches = array();
		foreach ($feeds as $feed) {
			$result = $this->parse_feed($feed);

			if ($result == false) {
				array_push($failed_fetches, $feed);
			}
		}

		if (count($failed_fetches) > 0) {
			echo "0";
			CakeLog::write('error', 'parse_all_feeds -> Failed to parse feeds: ' . var_export($failed_fetches, true));
		} else {
			echo "1";
		}
	}

	private function parse_feed($feed) {
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
						'title' => $episode_xml_array['title'],
						'episode_date' => $pubDate,
					);

					if (isset($episode_xml_array['description'])) {
						$doc = strip_tags($episode_xml_array['description']);

						if (isset($doc) && $doc !== false) {
							CakeLog::write('debug', 'parse_feed -> Description parsing: ' . $doc );
							$episode_data['description'] = $doc;
						} else {
							CakeLog::write('debug', 'parse_feed -> Description parsing: ' . $episode_xml_array['description'] );
						}

					}

					// 'url' => $episode_xml_array['enclosure']['@url'],

					if (isset($episode_xml_array['enclosure']['@url'])) {
						$episode_data['url'] = $episode_xml_array['enclosure']['@url'];
					} else {
						CakeLog::write('debug', 'parse_feed -> SKIPPED EPISODE WITH NO CONTENT: ' . $podcast['Podcast']['id'] . ' Title: ' . $episode_xml_array['title']);
						break;
					}

					if (isset($episode_xml_array['itunes:duration'])) {
						$episode_data['episode_length'] = $episode_xml_array['itunes:duration'];
					}

					if (isset($episode_xml_array['guid'])) {
						if (is_array($episode_xml_array['guid'])) {
							$guid = $episode_xml_array['guid']['@'];
						} else {
							$guid = $episode_xml_array['guid'];
						}
						$episode_data['guid'] = $guid;
					}

					if (isset($episode_xml_array['content:encoded'])) {
						$shownotes = $episode_xml_array['content:encoded'];
						$episode_data['shownotes'] = $shownotes;
					} else if (isset($episode_xml_array['itunes:summary'])) {
						$shownotes = $episode_xml_array['itunes:summary'];
						$episode_data['shownotes'] = $shownotes;
					}


					if (! $this->Episode->save($episode_data)) {
						CakeLog::write('debug', 'parse_feed -> Failed to save: ' . var_export($episode_data, true));
					}
				}
			}

		} else {
			CakeLog::write('debug', 'parse_feed -> NO FEED PROVIDED');
			return false;
		}

		CakeLog::write('debug', 'parse_feed -> FINISHED');

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