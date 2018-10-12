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
        $validatedInput = $request->validate([
            'question' => 'required|string',
            'option' => 'required|min:3|distinct'

            /*
                question: Are traps gay?
                option[]: Yes
                option[]: No
                multiple_answers_allowed: on
                hide_results_until_closed: on
                automatically_close_poll: on
                automatically_close_poll_datetime: 2018-10-12T18:45
                set_admin_password: on
                admin_password: sadasdasdasdasdas
                duplicate_vote_checking: codes
                number_of_codes: 10
             */
        ]);

        debug($validatedInput);

        $poll = new Poll;
        $poll->created_at = Carbon::now();

        return view('create_poll');
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
