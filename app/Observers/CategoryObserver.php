<?php

namespace App\Observers;

use App\Category;
use Illuminate\Support\Str;

class CategoryObserver
{
    /**
     * Handle the category "created" event.
     *
     * @param  \App\Category  $category
     * @return void
     */
    public function creating(Category $category)
    {
        //
        $category->slug = Str::slug($category->name);
    }

    /**
     * Handle the category "updated" event.
     *
     * @param  \App\Category  $category
     * @return void
     */
    public function updating(Category $category)
    {

        $category->slug = Str::slug($category->name);
    }

    // /**
    //  * Handle the category "deleted" event.
    //  *
    //  * @param  \App\Category  $category
    //  * @return void
    //  */
    // public function deleted(Category $category)
    // {
    //     //
    // }

    // /**
    //  * Handle the category "restored" event.
    //  *
    //  * @param  \App\Category  $category
    //  * @return void
    //  */
    // public function restored(Category $category)
    // {
    //     //
    // }

    // /**
    //  * Handle the category "force deleted" event.
    //  *
    //  * @param  \App\Category  $category
    //  * @return void
    //  */
    // public function forceDeleted(Category $category)
    // {
    //     //
    // }
}
