<?php
namespace App\Helpers;

use App\Models\Entities\User;
use Auth;

class UserHelper
{

    public static function getLoggedUserId($params = [])
    {
        return Auth::user()->user_id_pk ?? $params['loggedUser']->user_id_pk;
    }

    public static function getLoggedUserHasAnyRole($roles)
    {
        return Auth::user()->hasAnyRole($roles);
    }

    public static function checkUserHasAnyRole($roles, $user = null)
    {
        if ($user)
            return $user->hasAnyRole($roles);
        return false;
    }

    public static function checkUserHasRole($role, $user = null)
    {
        if ($user)
            return $user->hasRole($role);
        return false;
    }

    public static function checkUserIsLoggedIn($user, $params = [])
    {
       if (isset($params['schedule']) && $params['schedule'])
            return false;
        return $user->user_id_pk === self::getLoggedUserId($params);
    }

    public static function getUserBranchDept($user = null)
    {
        if ($user)
            return $user->departmentUser->department;
        return Auth::user()->departmentUser->department;
    }
}