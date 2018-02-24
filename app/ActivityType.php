<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    //table name
    protected $table = 'activity_type';

    //primary key
    protected $primaryKey = 'activity_type_id';

    //Timestamps
    public $timestamps = true;

    //get the user who create this activity type
    public function user() {
        return $this->belongsTo('App\User', 'created_by', 'user_id');
    }
}
