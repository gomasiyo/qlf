<?php

class Urls extends \Phalcon\Mvc\Model
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
    public $dashboard_id;

    /**
     *
     * @var integer
     */
    public $tags_id;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $comment;

    /**
     *
     * @var string
     */
    public $url;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('dashboard_id', 'Dashboard', 'id', array('alias' => 'Dashboard'));
        $this->belongsTo('tags_id', 'Tags', 'id', array('alias' => 'Tags'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'urls';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Urls[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Urls
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
