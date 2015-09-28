<?php

class Share extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $users_id;

    /**
     *
     * @var string
     */
    public $urls;

    /**
     *
     * @var string
     */
    public $pin;

    /**
     *
     * @var integer
     */
    public $killtime;

    /**
     *
     * @var integer
     */
    public $display_name;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('users_id', 'Users', 'id', array('alias' => 'Users'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'share';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Share[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Share
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
