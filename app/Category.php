<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description'];

    public function realStates()
    {
        return $this->belongsToMany(RealState::class, 'real_state_categories');
    }
}
