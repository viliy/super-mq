<?php

use \FastD\Migration\MigrationAbstract;
use \FastD\Migration\Table;
use \FastD\Migration\Key;


class System extends MigrationAbstract
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $table = new Table('systems');

        $table
            ->addColumn('id', 'int', 11, false, '0', '')
            ->addColumn('name', 'varchar', 50, true, '', '')
            ->addColumn('super_dns', 'varchar', 50, true, '', '')
            ->addColumn('super_auth', 'varchar', 255, true, '', '')
            ->addColumn('mq_auth', 'varchar', 255, true, '', '')
            ->addColumn('mq_api_dns', 'varchar', 50, true, '', '')
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