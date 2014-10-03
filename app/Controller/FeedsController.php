<?php
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');

class FeedsController extends AppController {
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow();
	}
	
	public function index(){
		
	}
	
	public function parse_feed($feed_id, Array $return_action = null) {
		CakeLog::write('debug', 'parse_feed -> Feed ID: ' . $feed_id);
		if (isset($feed_id)) {
			$feed = $this->Feed->findById($feed_id);
			if (! empty($feed)) {
				$podcast_feed_xml = Xml::build($feed['Feed']['url']);
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
			} else {
				$this->Session->setFlash('Feed ID invalid.');
			}
		} else {
			$this->Session->setFlash('Feed ID required to fetch a feed.');
		}
	}
}
?>