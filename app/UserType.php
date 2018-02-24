<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    //table name
    protected $table = 'usertype';

    //primary key
    protected $primaryKey = 'usertype_id';

    //get the users for the usertype
    public function users() {
        return $this->hasMany('App\User', 'usertype', 'usertype_id');
    }
}
