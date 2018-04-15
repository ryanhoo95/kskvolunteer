<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    //table name
    public $table = 'enquiry';

    //primary key
    protected $primaryKey = 'enquiry_id';

    //Timestamps
    public $timestamps = true;
}
