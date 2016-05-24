<?php

use yii\db\Schema;
use yii\db\Migration;

class m160522_133000_msg_template_type extends Migration
{
    public function up()
    {
        $this->createTable('msg_template_type', [
            'id' => Schema::TYPE_PK,
            'msg_template_id' => Schema::TYPE_INTEGER.' NOT NULL',
            'type' => Schema::TYPE_INTEGER.' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER.' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER.' NOT NULL',
        ]);

        $this->createIndex('IND_msg_template_type_msg_template','msg_template_type','msg_template_id');
        $this->addForeignKey('FK_msg_template_type_msg_template','msg_template_type','msg_template_id','msg_template','id','CASCADE');

    }

    public function down()
    {
        $this->dropTable('msg_template_type');

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
