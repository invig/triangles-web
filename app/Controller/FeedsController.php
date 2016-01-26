<?php
App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');
App::uses('Xml', 'Utility');

class FeedsController extends AppController {
	public $uses = array('Podcast', 'Episode', 'Feed');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('parse_all_feeds');
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
				if (! $response->isOk()) {
					CakeLog::write('error', 'Response from feed: '. $feed_url .' was not ok.');
					CakeLog::write('error', var_export($response, true));
					//throw new Exception('Response from feed was not ok.', $response->code);
					return false;
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


	private function parse_feed($feed) {
		$failures = array();

		if (isset($feed) && !empty($feed)) {
			$podcast_feed_xml = $this->get_feed($feed['Feed']['url']);
			if ($podcast_feed_xml == false) {
				CakeLog::write('error', "Failed to read feed at URL: " .  $feed['Feed']['url']);
				return;
			}
			$podcast_feed_xml_array = Xml::toArray($podcast_feed_xml);
			$podcast_feed_xml_array = $podcast_feed_xml_array['rss']['channel'];

			// Get the podcast so we can build the episodes.
			$podcast = $this->Podcast->find('first', array(
				'conditions' => array(
					'id' => $feed['Feed']['podcast_id'],
				)
			));

			// Catch fetching where no podcast exists.
			if (!isset($podcast) || empty($podcast)) {
				CakeLog::write('error', 'Failed to parse feed with ID: ' . $feed['Feed']['id']);
				return false;
			}

			// Fetch our list of existing episodes in one query.
			$existing_episodes = $this->Episode->find('all', array(
				'conditions' => array(
					'podcast_id' => $podcast['Podcast']['id']
				),
				'fields' => array('id','guid','title')
			));

			// Build the episodes
			$episodes_xml_array = $podcast_feed_xml_array['item'];

			foreach ($episodes_xml_array as $episode_xml_array) {
				if (isset($episode_xml_array['guid'])) {
					if (is_array($episode_xml_array['guid'])) {
						$guid = $episode_xml_array['guid']['@'];
					} else {
						$guid = $episode_xml_array['guid'];
					}
					$episode_xml_guid = $guid;
				} else {
					$episode_xml_guid = $podcast['Podcast']['id'].'-'.$episode_xml_array['title'];
				}

				// Match on any existing episodes
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

					if (isset($episode_xml_array['enclosure']['@url'])) {
						$episode_data['url'] = $episode_xml_array['enclosure']['@url'];
					} else {
						CakeLog::write('debug', 'parse_feed -> SKIPPED EPISODE WITH NO CONTENT: ' . $podcast['Podcast']['id'] . ' Title: ' . $episode_xml_array['title']);
						break;
					}

					if (isset($episode_xml_array['itunes:duration'])) {
						$episode_data['episode_length'] = $episode_xml_array['itunes:duration'];
					}

					if (isset($episode_xml_array['content:encoded'])) {
						$shownotes = $episode_xml_array['content:encoded'];
						$episode_data['shownotes'] = $shownotes;
					} else if (isset($episode_xml_array['itunes:summary'])) {
						$shownotes = $episode_xml_array['itunes:summary'];
						$episode_data['shownotes'] = $shownotes;
					}

					$episode_data['guid'] = $guid;

					if (! $this->Episode->save($episode_data)) {
						CakeLog::write('debug', 'parse_feed -> Failed to save: ' . var_export($episode_data, true));
					}
				}
			}

		} else {
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
