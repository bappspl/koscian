<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;
use Symfony\Component\Yaml\Yaml;

class CmsCreateUsers extends AbstractMigration
{

    public function up()
    {
        $this->table('cms_users', array())
             ->addColumn('name', 'string')
             ->addColumn('surname', 'string')
             ->addColumn('password', 'string')
             ->addColumn('password_salt', 'string')
             ->addColumn('email', 'string')
             ->addColumn('email_confirmed', 'integer', array('limit' => 1))
             ->addColumn('role', 'integer' , array('limit' => 1))
             ->addColumn('active', 'integer' , array('limit' => 1))
             ->addColumn('filename', 'string' , array('null' => true))
             ->addColumn('registration_date', 'datetime')
             ->addColumn('registration_token', 'string')
             ->save();

        $this->insertYamlValues('cms_users');
    }

    public function insertYamlValues($tableName)
    {
        $filename = './data/fixtures/'.$tableName.'.yml';
        $array = Yaml::parse(file_get_contents($filename));

        foreach ($array as $sArray){
            $value = '';

            foreach ($sArray as $kCol => $vCol) {
                $vCol === null ? $value = $value . $kCol .' = NULL , ' : $value = $value . $kCol .' = "' . $vCol . '", ';
            }

            $realValue = substr($value, 0, -2);

            $this->execute("SET NAMES UTF8");
            $this->adapter->execute('insert into '.$tableName.' set '.$realValue);
        }
    }

    public function down()
    {
        $this->dropTable('cms_users');
    }
}