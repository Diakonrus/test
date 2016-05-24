<?php

use yii\db\Schema;
use yii\db\Migration;
use app\models\MsgTemplate;

class m160521_140617_msg_template____create__table extends Migration
{
    public function up()
    {
        $this->createTable('msg_template', [
            'id' => Schema::TYPE_PK,
            'code' => Schema::TYPE_INTEGER.' NOT NULL',
            'name' => Schema::TYPE_STRING.' NOT NULL',
            'title' => Schema::TYPE_STRING.' NOT NULL',
            'body' => Schema::TYPE_TEXT.' NOT NULL',
            'user_id' => Schema::TYPE_INTEGER.' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER.' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER.' NOT NULL',
        ]);
        $this->createIndex('code_pk','msg_template','code');

        $this->createIndex('from_user_id_ind','msg_template','user_id');
        $this->addForeignKey('from_user_id_fk','msg_template','user_id','user','id','CASCADE');
/*
        //Создаем предопределенные шаблоны
        //Новый пользователь
        $model = new MsgTemplate();
        $model->name = 'Регистрация на сайте';
        $model->code = MsgTemplate::CODE_TEMPLATE_NEW_USER;
        $model->title = 'Вы были зарегистрированы на сайте {sitename}';
        $model->body = 'Поздравляем! Вы были зарегистрированы на сайте {sitename}:
        Ваш логин: {username}
        ';
        $model->from_user_id = 1;
        $model->to_user = 0;
        $model->type = MsgTemplate::TYPE_MSG_EMAIL;
        $model->save();

        //Отключить (заблокировать) пользователя
        $model = new MsgTemplate();
        $model->name = 'Отключить (заблокировать) пользователя';
        $model->code = MsgTemplate::CODE_TEMPLATE_DISABLE_USER;
        $model->title = 'Вы были заблокированы на сайте {sitename}';
        $model->body = 'Ваша учетная запись была зарегистрирована на сайте {sitename} администратором';
        $model->from_user_id = 1;
        $model->to_user = 0;
        $model->type = MsgTemplate::TYPE_MSG_EMAIL;
        $model->save();

        //Включить (разблокировать) пользователя
        $model = new MsgTemplate();
        $model->name = 'Включить (разблокировать) пользователя';
        $model->code = MsgTemplate::CODE_TEMPLATE_ACTIVE_USER;
        $model->title = 'Ваша учетная запись была активирована сайте {sitename}';
        $model->body = 'Ваша учетная запись была активирована на сайте {sitename} администратором.
                            Ваш логин: {username}';
        $model->from_user_id = 1;
        $model->to_user = 0;
        $model->type = MsgTemplate::TYPE_MSG_EMAIL;
        $model->save();

        //Удаление пользователя
        $model = new MsgTemplate();
        $model->name = 'Удаление пользователя';
        $model->code = MsgTemplate::CODE_TEMPLATE_DELETE_USER;
        $model->title = 'Ваша учетная запись была удалена на сайте {sitename}';
        $model->body = 'Ваша учетная запись была удалена на сайте {sitename} администратором.';
        $model->from_user_id = 1;
        $model->to_user = 0;
        $model->type = MsgTemplate::TYPE_MSG_EMAIL;
        $model->save();

        //Новая статья
        $model = new MsgTemplate();
        $model->name = 'Новая статья';
        $model->code = MsgTemplate::CODE_TEMPLATE_NEW_POST;
        $model->title = 'На сайте {sitename} была создана новая запись';
        $model->body = 'Уважаемый пользователь {username}! На сайте {sitename} добавлена новая статья "{article_name}".
                            {shortText}...
                            "читать далее"';
        $model->from_user_id = 1;
        $model->to_user = 0;
        $model->type = MsgTemplate::TYPE_MSG_EMAIL;
        $model->save();
*/



    }

    public function down()
    {
        $this->dropTable('msg_template');

        return false;
    }

}
