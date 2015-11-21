<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class TagsMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'tags',
            array(
            'columns' => array(
                new Column(
                    'id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'autoIncrement' => true,
                        'size' => 11,
                        'first' => true
                    )
                ),
                new Column(
                    'tag',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 45,
                        'after' => 'id'
                    )
                ),
                new Column(
                    'urls_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 11,
                        'after' => 'tag'
                    )
                )
            ),
            'indexes' => array(
                new Index('fk_tags_urls1_idx', array('urls_id'))
                new Index('PRIMARY', array('id')),
            ),
            'references' => array(
                new Reference('fk_tags_urls1', array(
                    'referencedSchema' => 'qlf',
                    'referencedTable' => 'urls',
                    'columns' => array('urls_id'),
                    'referencedColumns' => array('id')
                ))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '1',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_general_ci'
            )
        )
        );
    }
}
