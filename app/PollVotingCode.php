<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PollVotingCode extends Model
{
    public $timestamps = false;

    public $keyType = "string";

    public function __construct()
    {
        parent::__construct();

        $this->id = PollVotingCode::createId();
    }

    private static function createId()
    {
        //TODO: Check if id is unique

        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $id = '';
        for($i = 0; $i < 32; $i++) {
            $id .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $id;
    }

    public function poll()
    {
        return $this->belongsTo('App\Poll');
    }
}
