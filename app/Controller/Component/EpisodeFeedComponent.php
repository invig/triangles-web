<?php 
App::uses('Component', 'Controller');
App::uses('Xml', 'Utility');

class EpisodeFeedComponent extends Component {
	public $uses = array('Episode', 'Podcast');
    
	public function importEpisodesFromFeed($feed_url, $podcast_id = NULL) {
		return $this->importEpisodesFromFeedArray(Xml::build($feed_url), $podcast_id);
    }
	
	public function importEpisodesFromFeedArray($feed_array,  $podcast_id = NULL) {
		
	}
}
?>