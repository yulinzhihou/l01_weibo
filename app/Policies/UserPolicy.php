<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 用户更新资料权限方法，判断当前用户ID 与当前登录的用户ID 是否相同
     * @param User $currentUser
     * @param User $user
     * @return bool
     */
    public function update(User $currentUser,User $user)
    {
        return $currentUser->id === $user->id;
    }
}
