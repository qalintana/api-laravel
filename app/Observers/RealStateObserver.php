<?php

namespace App\Observers;

use App\RealState;
use Illuminate\Support\Str;

class RealStateObserver
{
    /**
     * Handle the real state "created" event.
     *
     * @param  \App\RealState  $realState
     * @return void
     */
    public function creating(RealState $realState)
    {
        $realState->slug = Str::kebab($realState->title);
    }

    /**
     * Handle the real state "updated" event.
     *
     * @param  \App\RealState  $realState
     * @return void
     */
    public function updating(RealState $realState)
    {
        //
        $realState->slug = Str::kebab($realState->title);
    }

    // /**
    //  * Handle the real state "deleted" event.
    //  *
    //  * @param  \App\RealState  $realState
    //  * @return void
    //  */
    // public function deleted(RealState $realState)
    // {
    //     //
    // }

    // /**
    //  * Handle the real state "restored" event.
    //  *
    //  * @param  \App\RealState  $realState
    //  * @return void
    //  */
    // public function restored(RealState $realState)
    // {
    //     //
    // }

    // /**
    //  * Handle the real state "force deleted" event.
    //  *
    //  * @param  \App\RealState  $realState
    //  * @return void
    //  */
    // public function forceDeleted(RealState $realState)
    // {
    //     //
    // }
}
