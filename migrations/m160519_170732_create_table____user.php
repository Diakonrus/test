<?php

use yii\db\Schema;
use yii\db\Migration;
use app\models\User;

/**
 * Handles the creation for table `table____user`.
 */
class m160519_170732_create_table____user extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING.' NOT NULL',
            'email' => Schema::TYPE_STRING.' NOT NULL',
            'password' => Schema::TYPE_STRING.' NOT NULL',
            'role' => Schema::TYPE_STRING.'(32) NOT NULL',
            'status' => Schema::TYPE_INTEGER.' NOT NULL DEFAULT "1"',
            'auth_key' => Schema::TYPE_STRING.'(32) NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER.' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER.' NOT NULL',
        ]);
        $this->createIndex('username_unique','user','username',true);
        $this->createIndex('email_unique','user','email',true);


        //Создаем админа
        $model = new User();
        $model->username = 'admin';
        $model->email = ((isset(Yii::$app->params['adminEmail']))?(Yii::$app->params['adminEmail']):('devspn@inbox.ru'));
        $model->setPasswordHash('Qq123456');
        $model->role = User::ROLE_ADMIN;
        $model->genAuthKey();
        $model->status = User::STATUS_ACTIVE;
        $model->save();

    }


    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
