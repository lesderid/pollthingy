<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;

use DB;
use App\Poll;
use App\PollVote;
use App\PollVotingCode;

class PollController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('create_poll');
    }

    //TODO: Close polls
    //TODO: Implement poll editing

    public function create(Request $request)
    {
        if($request->has('options')) {
            $request['options'] = array_filter($request->input('options'), function($i) { return $i !== null; });
        }

        $request['allow_multiple_answers'] = $request->has('allow_multiple_answers');
        $request['hide_results_until_closed'] = $request->has('hide_results_until_closed');
        $request['automatically_close_poll'] = $request->has('automatically_close_poll');
        $request['set_admin_password'] = $request->has('set_admin_password');

        $validatedInput = $request->validate([
            'question' => 'required|string',
            'options' => 'required|min:2|distinct',
            'allow_multiple_answers' => 'required|boolean',
            'hide_results_until_closed' => 'required|boolean',
            'automatically_close_poll' => 'required|boolean',
            'automatically_close_poll_datetime' => 'required_if:automatically_close_poll,true|date|after:now',
            'set_admin_password' => 'required|boolean',
            'admin_password' => 'required_if:set_admin_password,true|nullable|string',
            'duplicate_vote_checking' => 'required|in:none,cookies,codes',
            'number_of_codes' => 'required_if:duplicate_vote_checking,codes|integer|min:2'
        ]);

        $poll = new Poll;
        $poll->question = $validatedInput['question'];
        $poll->duplicate_vote_checking = $validatedInput['duplicate_vote_checking'];
        $poll->allow_multiple_answers = $validatedInput['allow_multiple_answers'];
        $poll->hide_results_until_closed = $validatedInput['hide_results_until_closed'];
        $poll->created_at = Carbon::now();
        if($validatedInput['automatically_close_poll']) {
            $poll->closes_at = Carbon::parse($validatedInput['automatically_close_poll_datetime']);
        }
        if($validatedInput['set_admin_password']) {
            $poll->admin_password = $validatedInput['admin_password'];
        }
        $poll->save();

        $poll->options()->createMany(array_map(function($i) { return ['text' => $i]; }, $validatedInput['options']));
        $poll->save();

        if($poll->duplicate_vote_checking == 'codes') {
            $codes = $poll->createVotingCodes($validatedInput['number_of_codes']);
        }

        return redirect()->action('PollController@view', ['poll' => $poll])->with('new', true);
    }

    public function view(Request $request, Poll $poll)
    {
        $new = $request->session()->pull('new', false);

        if($request->format() == 'json') {
            if($new) {

            } else {

            }

            //TODO: Implement JSON output
            return null;
        } else {
            return view('view_poll')
                ->with('poll', $poll)
                ->with('new', $new)
                ->with('hasVoted', $this->hasVoted($request, $poll));
        }
    }

    public function viewResults(Request $request, Poll $poll)
    {
        $voted = $request->session()->pull('voted', false);

        return view('view_poll_results')
            ->with('poll', $poll)
            ->with('voted', $voted);
    }

    private static function createPieChart(Poll $poll)
    {
        //TODO
    }

    public function hasVoted(Request $request, Poll $poll)
    {
        if($poll->duplicate_vote_checking == 'cookies') {
            if($request->session()->exists($poll->id)) {
                return true;
            }
        } else if($poll->duplicate_vote_checking == 'codes') {
            $code = PollVotingCode::find($request->query('code'));

            if($code == null || $code->used) {
                return true;
            }
        }

        return false;
    }

    public function vote(Request $request, Poll $poll)
    {
        if($this->hasVoted($request, $poll)) {
            return null;
        }

        if($poll->allow_multiple_answers) {
            $validatedInput = $request->validate([
                'options' => 'required|distinct',
            ]);
        } else {
            $validatedInput = $request->validate([
                'options' => 'required|distinct|min:1|max:1',
            ]);
        }

        DB::beginTransaction();
        foreach($validatedInput['options'] as $option)
        {
            //TODO: Properly display errors

            if($poll->options()->find($option) == null) {
                DB::rollBack();

                return null;
            }

            $vote = new PollVote;
            $vote->poll_option_id = $option;
            $poll->votes()->save($vote);
        }
        DB::commit();

        if($poll->duplicate_vote_checking == 'cookies') {
            $request->session()->put($poll->id, null);
        } else if($poll->duplicate_vote_checking == 'codes') {
            $code->used = true;
            $code->save();
        }

        return redirect()->action('PollController@viewResults', ['poll' => $poll])->with('voted', true);
    }

    public function admin(Request $request, Poll $poll)
    {
    }

    public function edit(Request $request, Poll $poll)
    {
    }
}
