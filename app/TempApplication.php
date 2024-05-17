<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class TempApplication extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'age',
        'forum_name',
        'steam_url',

        'rules_opinion',
        'rp_definition',
        'past_characters',
        'character_idea',
        'streamer',
        'me_do',
        'ooc_vs_ic',
        'do_lying',
        'tweet',
        'revenge_kill',
        'brutally_wounded',
        'meta_gaming',
        'power_gaming',
        'forget',
        'crash',
    ];
    
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
