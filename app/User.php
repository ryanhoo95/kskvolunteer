<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    //table name
    public $table = 'user';

    //primary key
    protected $primaryKey = 'user_id';

    //Timestamps
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //get the usertype for this user
    public function usertype() {
        return $this->belongsTo('App\UserType', 'usertype', 'usertype_id');
    }

    //get the volunteer profile for this user
    public function volunteerProfile() {
        return $this->hasOne('App\VolunteerProfile', 'user_id', 'user_id');
    }

    //get the activity type created by this user
    public function activityTypes() {
        return $this->hasMany('App\ActivityType', 'created_by', 'user_id');
    }

    //get the activity created by this user
    public function activities() {
        return $this->hasMany('App\Activity', 'created_by', 'user_id');
    }

    //get the participations joined by this user (volunteer)
    public function participationsVolunteer() {
        return $this->hasMany('App\Participation', 'participant_id', 'user_id');
    }

    //get the participations created by this user (added manually by staff)
    public function participationsStaff() {
        return $this->hasMany('App\Participation', 'participant_added_by', 'user_id');
    }

    //get the invitations sent by this user
    public function invitationsSent() {
        return $this->hasMany('App\Invitation', 'invited_by', 'user_id');
    }

    //get the invitations received by this user
    public function invitationsReceived() {
        return $this->hasMany('App\Invitation', 'target_to', 'user_id');
    }
}
