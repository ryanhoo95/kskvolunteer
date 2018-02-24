<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    //table name
    protected $table = 'activity';

    //primary key
    protected $primaryKey = 'activity_id';

    //Timestamps
    public $timestamps = true;

    //get the user who create this activity
    public function user() {
        return $this->belongsTo('App\User', 'created_by', 'user_id');
    }

    //get the participation of this activity
    public function participations() {
        return $this->hasMany('App\Participation', 'activity_id', 'activity_id');
    }

}
