<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function created(User $user): void
    {
        Log::info('Successfully created user.');
    }

    /**
     * Handle the User "updated" event.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function updated(User $user): void
    {
        Log::info('Successfully updated user');
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function deleted(User $user): void
    {
        Log::info('Successfully deleted user');
    }

    /**
     * Handle the User "restored" event.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        $user->deleted_by = auth()->user()->id;
    }

    public function creating(User $user): void
    {
        $user->created_by = auth()->user()->id;
    }

    public function saving(User $user): void
    {
        $user->updated_by = auth()->user()->id;
    }

    public function deleting(User $user): void
    {
        $user->deleted_by = auth()->user()->id;
        $user->save();
    }
}
