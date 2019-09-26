<?php

use \FastD\Migration\MigrationAbstract;
use \FastD\Migration\Table;
use \FastD\Migration\Key;


class Process extends MigrationAbstract
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $table = new Table('processes');

        $table
            ->addColumn('id', 'int', 11, false, '0', '')
            ->addColumn('name', 'varchar', 100, true, '', '')
            ->addColumn('system_id', 'int', 11, true, '', '')
            ->addColumn('group', 'varchar', 100, true, '', '')
            ->addColumn('mq_name', 'varchar', 100, true, '', '')
            ->addColumn('mq_node', 'varchar', 50, true, '', '')
            ->addColumn('mq_vhost', 'varchar', 100, true, '', '')
            ->addColumn('mq_consumer', 'tinyint', 4, false, '0', '')
            ->addColumn('mq_min_consumer', 'tinyint', 4, false, '0', '')
            ->addColumn('status', 'tinyint', 4, false, '0', '')
            ->addColumn('abstract', 'varchar', 255, true, '', '')
            ->addColumn('created_at', 'timestamp', null, true, 'CURRENT_TIMESTAMP', '')
            ->addColumn('updated_at', 'timestamp', null, true, 'CURRENT_TIMESTAMP', '')
            ->addColumn('deleted_at', 'timestamp', null, true, 'CURRENT_TIMESTAMP', '')
            ->addIndex('id', Key::PRIMARY)
        ;
        $table->getColumn('id')->withIncrement()->withUnsigned(true);

        return $table;
    }
}