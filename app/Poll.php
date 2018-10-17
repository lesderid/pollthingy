<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Poll extends Model
{
    public $timestamps = false;

    public $keyType = "string";

    public function __construct()
    {
        parent::__construct();

        $this->id = Poll::createId();
    }

    private static function createId()
    {
        //TODO: Check if id is unique

        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $id = '';
        for($i = 0; $i < 6; $i++) {
            $id .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $id;
    }

    public function options()
    {
        return $this->hasMany('App\PollOption');
    }

    public function votes()
    {
        return $this->hasMany('App\PollVote');
    }

    public function voting_codes()
    {
        return $this->hasMany('App\PollVotingCode');
    }

    public function createVotingCodes($n)
    {
        $codes = [];
        for($i = 0; $i < $n; $i++) {
            $codes[] = new PollVotingCode;
        }

        $this->voting_codes()->saveMany($codes);

        return $codes;
    }

    public function getResultsVisibleAttribute()
    {
        return !$this->hide_results_until_closed || ($this->closes_at != null && Carbon::parse($this->closes_at)->isPast());
    }
}
