<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Poll;

class PollController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('create_poll');
    }

    public function create(Request $request)
    {
        $poll = new Poll;
        $poll->created_at = Carbon::now();
    }

    public function view(Request $request, Poll $poll)
    {
    }

    public function vote(Request $request, Poll $poll)
    {
    }

    public function admin(Request $request, Poll $poll)
    {
    }

    public function edit(Request $request, Poll $poll)
    {
    }
}
