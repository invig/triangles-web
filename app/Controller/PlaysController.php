<?php
App::uses('AppController', 'Controller');

class PlaysController extends AppController {
    public $uses = array('Play', 'User');

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

    public function mark_all_finished() {
        //TODO: Implement

        
        $this->redirect(array('controller' => 'episodes', 'action' => 'unplayed'));
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