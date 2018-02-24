<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OccupationType extends Model
{
    //table name
    protected $table = 'occupation_type';

    //primary key
    protected $primaryKey = 'occupation_type_id';

    //get the volunteer profile for this occupation type
    public function volunteers() {
        return $this->hasMany('App\VolunteerProfile', 'occupation', 'occupation_type_id');
    }
}
