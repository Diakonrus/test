<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use \app\rbac\UserRoleRule;
use app\models\User;


class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->getAuthManager();
        $auth->removeAll();

        $template_access = $auth->createPermission('template_access');
        $template_access->description = 'Доступ к шаблонам';
        $auth->add($template_access);

        $posts_access = $auth->createPermission('posts_access');
        $posts_access->description = 'Доступ к просмотру / созданию постов';
        $auth->add($posts_access);

        $posts_access_action = $auth->createPermission('posts_access_action');
        $posts_access_action->description = 'Доступ к редактированию / удалению';
        $auth->add($posts_access_action);

        $users_api_access = $auth->createPermission('users_api_access');
        $users_api_access->description = 'Доступ к управлению пользователями';
        $auth->add($users_api_access);






        $userGroupRule = new UserRoleRule;
        $auth->add($userGroupRule);

        $admin = $auth->createRole(User::ROLE_ADMIN);
        $admin->ruleName = $userGroupRule->name;
        $auth->add($admin);
        $registered = $auth->createRole(User::ROLE_USER);
        $registered->ruleName = $userGroupRule->name;
        $auth->add($registered);
        $guest = $auth->createRole(User::ROLE_GUEST);
        $guest->ruleName = $userGroupRule->name;
        $auth->add($guest);

        $auth->addChild($registered, $guest);
        $auth->addChild($admin, $registered);
        $auth->addChild($registered, $posts_access);
        $auth->addChild($admin, $template_access);
        $auth->addChild($admin, $posts_access_action);
        $auth->addChild($admin, $users_api_access);
    }
}