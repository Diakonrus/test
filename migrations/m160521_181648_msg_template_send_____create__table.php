<?php

use yii\db\Schema;
use yii\db\Migration;

class m160521_181648_msg_template_send_____create__table extends Migration
{
    public function up()
    {
        $this->createTable('sending_msg_log', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING.' NOT NULL',
            'body' => Schema::TYPE_TEXT.' NOT NULL',
            'user_id' => Schema::TYPE_INTEGER.' NOT NULL',
            'status' => Schema::TYPE_INTEGER.' NOT NULL',
            'type_msg' => Schema::TYPE_INTEGER.' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER.' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER.' NOT NULL',
        ]);

        $this->createIndex('msg_user_id_ind','sending_msg_log','user_id');
        $this->addForeignKey('msg_user_id_fk','sending_msg_log','user_id','user','id','CASCADE');
    }

    public function down()
    {
        $this->dropTable('sending_msg_log');
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
