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
}
