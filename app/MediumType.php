<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MediumType extends Model
{
    //table name
    protected $table = 'medium_type';

    //primary key
    protected $primaryKey = 'medium_type_id';

    //get the volunteer profile for this medium type
    public function volunteers() {
        return $this->hasMany('App\VolunteerProfile', 'medium', 'medium_type_id');
    }
}
