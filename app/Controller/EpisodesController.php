<?php
App::uses('AppController', 'Controller');

class EpisodesController extends AppController {
    public $uses = array('Podcast', 'UserPodcast', 'Episode', 'Play', 'User');
    public $components = array('Paginator', 'RequestHandler');
    public $helpers = array('Paginator');

    public $paginate = array(
        'limit' => 25,
        'order' => array(
            'Episode.episode_date' => 'DESC'
        )
    );

    public function index($podcast_id) {
        if (!isset($podcast_id)) {
            $this->Session->setFlash(__('Select a podcast to view its episode list'));
            $this->redirect(array('controller'=>'podcasts','action'=>'index'));
        }

        $podcast = $this->Podcast->find('first', array(
           'conditions' => array(
               'id' => $podcast_id
           )
        ));

        $this->Paginator->settings = array(
            'conditions' => array(
                'podcast_id' => $podcast_id
            ),
            'order' => array(
                "episode_date" => 'DESC'
            ),
            'limit' => 10
        );

        $unplayed_episodes = $this->Paginator->paginate('Episode');

        $episode_ids = array();
        foreach ($unplayed_episodes as $episode) {
            array_push($episode_ids, $episode['Episode']['id']);
        }

        $plays = $this->Play->find('all', array(
           'conditions' => array(
               'user_id' => $this->Auth->user('id'),
               'episode_id' => $episode_ids,
               'finished_playing' => 1
           )
        ));

        // sort the episodes by played status
        $played_episodes = array();
        foreach($plays as $play) {
            array_push($played_episodes, array('Episode'=>$play['Episode']));

            foreach($unplayed_episodes as $key=>$unplayed_episode) {
                if ($unplayed_episode['Episode']['id'] == $play['Play']['episode_id']) {
                    unset($unplayed_episodes[$key]);
                }
            }
        }

        $unplayed_episodes = array_values($unplayed_episodes);

        $this->set_most_recent_playing();
        $this->set('podcast', $podcast);
        $this->set('played_episodes', $played_episodes);
        $this->set('unplayed_episodes', $unplayed_episodes);
        $this->set('_serialize', array('unplayed_episodes', 'played_episodes', 'podcast', 'current_episode'));
        $this->set('_jsonp', true);
    }

    public function play($id) {
        if (!isset($id) || empty($id)) {
            $this->Session->setFlash("Select an episode to play it");
            if (! $this->redirectToReferrer()) {
                $this->redirect("/");
            }
        }

        $episode = $this->Episode->findById($id);

        $play = $this->Play->find('first', array(
            'conditions' => array(
                'user_id' => $this->Auth->user('id'),
                'episode_id' => $id
            )
        ));

        if (isset($play) && !empty($play)) {
          $this->set('play', $play);
        }

        $this->set_most_recent_playing();
        $this->set('episode', $episode);
    }

    private function redirectToReferrer() {
        // TODO: implement redirect.
        // Check that the referrer is set and is on our site.
        // $this->redirect($this->referrer());
        return false;
    }

    public function unplayed() {
        $user_id = $this->Auth->user('id');

        // Get the podcasts for the current user
        $podcasts = $this->UserPodcast->find('all', array(
            'conditions' => array(
                'UserPodcast.user_id' => $user_id
            ),
            'fields' => array('podcast_id')
        ));

        $podcast_ids = array();
        foreach ($podcasts as $podcast) {
            array_push($podcast_ids, $podcast['UserPodcast']['podcast_id']);
        }

        // Get the finished episode list for the current user
        $finished_episodes = $this->Play->find('list', array(
            'conditions' => array(
                'user_id' => $user_id,
                'finished_playing' => true
            ),
            'fields' => array('episode_id')
        ));

        $finished_ids = array();

        foreach ($finished_episodes as $finished_episode) {
            array_push($finished_ids, $finished_episode);
        }

        //Get the unfinished episodes for the current user sorted by published date
        $this->Paginator->settings = array(
            'conditions' => array(
                'Episode.podcast_id' => $podcast_ids,
                'NOT' => array(
                    'Episode.id' => $finished_ids
                )
            ),
            'order' => array('Episode.episode_date' => 'DESC'),
            'limit' => 10
        );

        $episodes = $this->Paginator->paginate('Episode');
        $this->set('episodes', $episodes);
        $this->set_most_recent_playing();
        $this->set('_serialize', array('episodes', 'current_episode'));
        $this->set('_jsonp', true);
    }

    private function set_most_recent_playing() {
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
}
?>
