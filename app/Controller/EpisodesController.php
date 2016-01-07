<?php
App::uses('AppController', 'Controller');

class EpisodesController extends AppController {
    public $uses = array('Podcast', 'UserPodcast', 'Episode', 'Play');

    public function index($podcast_id) {
        // TODO: Finish view.

        if (!isset($podcast_id)) {
            $this->Session->setFlash(__('Select a podcast to view its episode list'));
            $this->redirect(array('controller'=>'podcasts','action'=>'index'));
        }

        $podcast = $this->Podcast->find('first', array(
           'conditions' => array(
               'id' => $podcast_id
           )
        ));

        $episodes = $this->Episode->find('all', array(
            'conditions' => array(
                'podcast_id' => $podcast_id
            ),
            'order' => array(
                "episode_date" => 'DESC'
            )
        ));

        $this->set('podcast', $podcast);
        $this->set('episodes', $episodes);
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
        $episodes = $this->Episode->find('all', array(
            'conditions' => array(
                'Episode.podcast_id' => $podcast_ids,
                'NOT' => array(
                    'Episode.id' => $finished_ids
                )
            ),
            'order' => array('Episode.episode_date' => 'DESC')
        ));

        $this->set('episodes', $episodes);
    }
}
?>