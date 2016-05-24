<?php

use yii\db\Schema;
use yii\db\Migration;

class m160522_132948_msg_template_user extends Migration
{
    public function up()
    {
        $this->createTable('msg_template_user', [
            'id' => Schema::TYPE_PK,
            'msg_template_id' => Schema::TYPE_INTEGER.' NOT NULL',
            'user_id' => Schema::TYPE_INTEGER.' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER.' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER.' NOT NULL',
        ]);

        $this->createIndex('msg_template_id_ind','msg_template_user','msg_template_id');
        $this->addForeignKey('msg_template_id_fk','msg_template_user','msg_template_id','msg_template','id','CASCADE');

        $this->createIndex('to_user_id_ind','msg_template_user','user_id');
        $this->addForeignKey('to_user_id_ind_fk','msg_template_user','user_id','user','id','CASCADE');
    }

    public function down()
    {
        $this->dropTable('msg_template_user');

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
