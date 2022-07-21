<?php

namespace App\Policies;

use App\Models\User;
use App\Security\Permissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class SoldProductPolicy
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

    public function viewAny(User $user): bool
    {
        return $user->can(Permissions::READ_SALES);
    }
}
