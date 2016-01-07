<?php
App::uses('AppController', 'Controller');

class PlaysController extends AppController {
    public $uses = array('Play', 'User');

    // API only call to update the play state on an episode.
    public function update_play_state() {
        $this->autoRender = false;
        if ($this->request->is('POST')) {
            $user_id = $this->Auth->user('id');
            $episode_id = $this->request->data['episode_id'];
            $timestamp = $this->request->data['position'];

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

            if ($this->Play->save($data)) {
                echo true;
                return;
            }
        }

        echo false;
    }
}
?>