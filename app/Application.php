<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Application extends Model
{
    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Uuid::generate(4);
        });
    }
}
