<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    //table name
    public $table = 'invitation';

    //primary key
    protected $primaryKey = 'invitation_id';

    //Timestamps
    public $timestamps = true;

    //get the user who sent this invitation
    public function sender() {
        return $this->belongsTo('App\User', 'invited_by', 'user_id');
    }

    //get the staff who create this participation
    public function recipient() {
        return $this->belongsTo('App\User', 'target_to', 'user_id');
    }
}
