<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participation extends Model
{
    //table name
    public $table = 'participation';

    //primary key
    protected $primaryKey = 'participation_id';

    //Timestamps
    public $timestamps = true;

    //get the user who joins this participation
    public function participant() {
        return $this->belongsTo('App\User', 'participant_id', 'user_id');
    }

    //get the staff who create this participation
    public function staffCreate() {
        return $this->belongsTo('App\User', 'participant_added_by', 'user_id');
    }

    //get the activity of this participation
    public function activity() {
        return $this->belongsTo('App\Activity', 'activity_id', 'activity_id');
    }
}
