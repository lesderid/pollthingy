<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PollVote extends Model
{
    public $timestamps = false;

    public function poll()
    {
        return $this->belongsTo('App\Poll');
    }
}
