<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $guarded = ['id'];

    public function files()
    {
        return $this->hasMany('App\Models\File', 'user_id', 'id');
    }
}
