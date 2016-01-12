<?php
App::uses('AppController', 'Controller');

class PlaysController extends AppController {
    public $uses = array('Play', 'User', 'Episode', 'UserPodcast');

    public function set_currently_playing() {
        $this->autoRender = false;
        if ($this->request->is('POST') && isset($this->request->data['episode_id'])) {

            $episode_id = $this->request->data['episode_id'];
            $user = $this->Auth->user();
            $user['current_episode_id'] = $episode_id;

            if ($this->User->save($user)) {
                echo true;
                return;
            }
        }

        echo false;
    }

    private function all_finished_play_ids() {
        $user_id = $this->Auth->user('id');

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

        return $finished_ids;
    }

    private function all_podcasts_ids() {
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

        return $podcast_ids;
    }

    public function mark_all_finished() {
        $this->autoRender = false;

        $podcast_ids = $this->all_podcasts_ids();
        $finished_ids = $this->all_finished_play_ids();

        //Get the unfinished episodes for the current user sorted by published date
        $episodes = $this->Episode->find('all', array(
            'conditions' => array(
                'Episode.podcast_id' => $podcast_ids,
                'NOT' => array(
                    'Episode.id' => $finished_ids
                )
            ),
            'order' => array('Episode.episode_date' => 'DESC'),
            'fields' => array("Episode.id")
        ));

        foreach  ($episodes as $episode) {
            $this->save_finished($episode['Episode']['id']);
        }

        $this->redirect(array('controller' => 'episodes', 'action' => 'unplayed'));
    }

    public function mark_all_except_most_recent_finished() {
        $this->autoRender = false;

        $podcast_ids = $this->all_podcasts_ids();
        $finished_ids = $this->all_finished_play_ids();

        //Get the unfinished episodes for the current user sorted by published date
        $episodes = $this->Episode->find('all', array(
            'conditions' => array(
                'Episode.podcast_id' => $podcast_ids,
                'NOT' => array(
                    'Episode.id' => $finished_ids
                )
            ),
            'order' => array('Episode.episode_date' => 'DESC'),
            'fields' => array("Episode.id", "Episode.podcast_id")
        ));

        // For all of the episodes,
        // find the first one for each podcast and remove it so it does not get marked as played
        // this will only work if the episodes are ordered correctly
        foreach ($podcast_ids as $podcast_id) {
            foreach ($episodes as $episode_key => $episode) {
                if ($episode['Episode']['podcast_id'] == $podcast_id) {
                    unset($episodes[$episode_key]);
                    break;
                }
            }
        }

        foreach  ($episodes as $episode) {
            $this->save_finished($episode['Episode']['id']);
        }

        $this->redirect(array('controller' => 'episodes', 'action' => 'unplayed'));
    }

    public function mark_finished($episode_id) {
        if (! $this->save_finished($episode_id)) {
            $this->Session->setFlash("Error: Failed to mark podcast as finished");
        }

        $this->redirect(array('controller' => 'episodes', 'action' => 'unplayed'));
    }

    private function save_finished($episode_id) {
        $user_id = $this->Auth->user('id');
        $play = $this->Play->find('first', array(
            'conditions' => array(
                'user_id' => $user_id,
                'episode_id' => $episode_id
            )
        ));

        $data = array();

        if (! isset($play) || empty($play)) {
            $this->Play->create();
            $data['user_id'] = $user_id;
            $data['episode_id'] = $episode_id;
        } else {
            $data['id'] = $play['Play']['id'];
        }

        $data['finished_playing'] = true;

        if ($this->Play->save($data)) {
            return true;
        } else {
            return false;
        }
    }

    // API only call to update the play state on an episode.
    public function update_play_state() {
        $this->autoRender = false;
        if ($this->request->is('POST') &&
            isset($this->request->data['episode_id']) &&
            isset($this->request->data['position']) &&
            isset($this->request->data['finished'])
        ) {
            $user_id = $this->Auth->user('id');
            $episode_id = $this->request->data['episode_id'];
            $timestamp = $this->request->data['position'];
            $finished = $this->request->data['finished'];

            $play = $this->Play->find('first', array(
                'conditions' => array(
                    'user_id' => $user_id,
                    'episode_id' => $episode_id
                )
            ));

            $data = array();

            if (! isset($play) || empty($play)) {
                $this->Play->create();
                $data['user_id'] = $user_id;
                $data['episode_id'] = $episode_id;
            } else {
                $data['id'] = $play['Play']['id'];
            }

            $data['position'] = $timestamp;
            $data['finished_playing'] = $finished;

            if ($this->Play->save($data)) {
                echo true;
                return;
            }
        }

        echo false;
    }
}
?>