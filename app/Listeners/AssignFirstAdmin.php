<?php

namespace App\Listeners;

use App\Models\User;
use App\Security\Roles;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AssignFirstAdmin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Registered $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if (User::query()->count() == 1) {

            $event->user->assignRole(Roles::ADMIN);
        }
    }
}
