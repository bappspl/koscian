<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCreateGallery extends AbstractMigration
{

    public function up()
    {
        $this->table('cms_gallery', array())
            ->addColumn('status_id', 'integer')
            ->addColumn('name', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('url', 'string')
            ->addForeignKey('status_id', 'cms_status', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->save();
    }

    public function down()
    {
        $this->dropTable('cms_gallery');
    }
}