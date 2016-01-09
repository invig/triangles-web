<?php
App::uses('AppModel', 'Model');

class Waiter extends AppModel {
    var $cacheQueries = false;

    public $validate = array(
        'email' => array(
            'unique' => array(
                'rule' => array('isUnique'),
                'allowEmpty' => false,
                'message' => 'Looks like you\'re already on the list'
            ),
            'syntax' => array(
                'rule' => array('email', true),
                'allowEmpty' => false,
                'message' => 'Invalid email'
            )
        )

    );
}

?>