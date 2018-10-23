<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    public $timestamps = false;

    protected $fillable = ['text'];

    public function poll()
    {
        return $this->belongsTo('App\Poll');
    }

    public function getVoteCountAttribute()
    {
        return $this->poll->votes->where('poll_option_id', $this->id)->count();
    }
}
