<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    public function __construct()
    {
        parent::__construct();

        $this->id = Poll::create_id();
    }

    private static function create_id()
    {
        //TODO: Check if id is unique

		$characters = 'abcdefghijklmnopqrstuvwxyz';
		$id = '';
		for($i = 0; $i < 6; $i++)
		{
			$id .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $id;
    }
}
