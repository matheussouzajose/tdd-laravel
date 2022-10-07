<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Str;

class UserObserver
{
    /**
     * @param User $user
     * @return void
     */
    public function creating(User $user): void
    {
        $user->id = Str::uuid();
    }
}
