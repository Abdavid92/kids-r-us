<?php

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

if (! function_exists('user')) {

    /**
     * Obtiene el usuario autenticado.
     *
     * @return User|Authenticatable|null
     */
    function user(): User|Authenticatable|null
    {
        return Auth::user();
    }
}
