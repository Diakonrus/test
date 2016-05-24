<?php

use yii\db\Schema;
use yii\db\Migration;

class m160522_133632_posts extends Migration
{
    public function up()
    {
        $this->createTable('posts', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING.' NOT NULL',
            'body' => Schema::TYPE_TEXT.' NOT NULL',
            'user_id' => Schema::TYPE_INTEGER.' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER.' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER.' NOT NULL',
        ]);

        $this->createIndex('user_id_ind','posts','user_id');
        $this->addForeignKey('user_id_fk','posts','user_id','user','id','CASCADE');
    }

    public function down()
    {
        $this->dropTable('posts');

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
