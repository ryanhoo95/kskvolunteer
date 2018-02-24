<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VolunteerProfile extends Model
{
    //table name
    public $table = 'volunteer_profile';

    //primary key
    protected $primaryKey = 'volunteer_profile_id';

    //Timestamps
    public $timestamps = true;

    //get the user for this profile
    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'user_id');
    }

    //get the occupation for this profile
    public function occupation() {
        return $this->belongsTo('App\OccupationType', 'occupation', 'occupation_type_id');
    }

    //get the medium for this profile
    public function medium() {
        return $this->belongsTo('App\MediumType', 'medium', 'medium_type_id');
    }
}
