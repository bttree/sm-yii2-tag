<?php

use yii\db\Migration;

/**
 * Class m170830_152505_init_tag
 */
class m170830_152505_init_tag extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%tag}}',
                           [
                               'id'          => $this->primaryKey(),
                               'model_class' => $this->string()->notNull(),
                               'model_id'    => $this->integer()->notNull(),
                               'title'       => $this->string()->notNull(),

                           ],
                           $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%tag}}');
    }

}
