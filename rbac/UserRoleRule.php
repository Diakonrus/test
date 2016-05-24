<?php
namespace app\rbac;

use app\models\User;
use Yii;
use yii\rbac\Rule;

class UserRoleRule extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'userRole';
    private $_assignments = [];

    public function execute($user, $item, $params)
    {
        if ($role = $this->userRole($user)) {
            switch ($item->name) {
                case User::ROLE_ADMIN:
                    return $role == User::ROLE_ADMIN;
                case User::ROLE_USER:
                    return $role == User::ROLE_ADMIN || $role == User::ROLE_USER;
                case User::ROLE_GUEST:
                    return in_array($role, User::$userRoleList);
            }
        }
        return false;
    }


    /**
     * @param $user_id
     * @return bool|string
     */
    protected function userRole($user_id)
    {
        $user = Yii::$app->user;
        if ($user_id === null) {
            if ($user->isGuest) {
                return User::ROLE_GUEST;
            }
            return false;
        }
        if (!isset($this->_assignments[$user_id])) {
            $role = false;
            if (!$user->isGuest && $user->id == $user_id) {
                $role = $user->role;
            } elseif ($user->isGuest || $user->id != $user_id) {
                $role = User::returnUserRole($user_id);
            }
            $this->_assignments[$user_id] = $role;
        }
        return $this->_assignments[$user_id];
    }
}