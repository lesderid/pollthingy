<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;
use DB;
use Cache;

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

        $this->createPieChart($poll);

        return view('view_poll_results')
            ->with('poll', $poll)
            ->with('voted', $voted);
    }

    private function createPieChart(Poll $poll)
    {
        $voteCount = $poll->votes->count();

        if(Cache::has($poll->id) && Cache::get($poll->id)['vote_count'] == $voteCount) {
            return;
        }

        $baseColours = [[0xE8, 0x96, 0x3F], [0xAD, 0x3F, 0xE8], [0x3F, 0xE8, 0x6F], [0xE8, 0xE3, 0x3F], [0x3F, 0x64, 0xEB], [0xE8, 0x3F, 0x65], [0x3F, 0xE8, 0xDB]];
        shuffle($baseColours);

        $supersamplingFactor = 8;

        $width = 512 * $supersamplingFactor;
        $height = 512 * $supersamplingFactor;
        $padding = 16 * $supersamplingFactor;

        $chartWidth = $width - 2 * $padding;
        $chartHeight = $height - 2 * $padding;

        $pieChart = imagecreatetruecolor($width, $height);
        imagefill($pieChart, 0, 0, imagecolorallocate($pieChart, 0xFF, 0xFF, 0xFF));
        imageantialias($pieChart, true);

        $primary = imagecolorallocate($pieChart, 0xE8, 0x3F, 0xB8);

        $colours = [];

        $startDegrees = 0;
        $sortedOptions = $poll->options->sortByDesc(function($option) use($poll) { return $poll->votes->where('poll_option_id', $option->id)->count(); });
        $nonZeroOptions = $sortedOptions->filter(function($option) use($poll) { return $poll->votes->where('poll_option_id', $option->id)->count() > 0; })->values();
        debug($nonZeroOptions);
        for($i = 0; $i < $poll->options->count(); $i++) {
            $option = $nonZeroOptions[$i];

            //TODO: Fix gaps
            $degrees = round($poll->votes->where('poll_option_id', $option->id)->count() / $voteCount * 360);
            $endDegrees = min($startDegrees + $degrees, 360);

            $c = function($j) use($i, $baseColours, $nonZeroOptions) {
                return $baseColours[$i % count($baseColours)][$j]
                    + (255 - $baseColours[$i % count($baseColours)][$j])
                    * floor($i / count($baseColours)) / (floor($nonZeroOptions->count() / count($baseColours)) + 1);
            };
            $colour = imagecolorallocate($pieChart, $c(0), $c(1), $c(2));
            $colours[$option->id] = '#' . dechex($c(0) << 16 | $c(1) << 8 | $c(2) << 0);

            debug([$option->text, [$startDegrees, $endDegrees], [$c(0), $c(1), $c(2)]]);

            imagefilledarc($pieChart, $width / 2, $height / 2, $chartWidth, $chartHeight, $startDegrees, $endDegrees, $colour, IMG_ARC_PIE);

            $startDegrees = $endDegrees;
        }

        debug($colours);

        $resized = imagecreatetruecolor($width / $supersamplingFactor, $height / $supersamplingFactor);
        imagecopyresampled($resized, $pieChart, 0, 0, 0, 0, $width / $supersamplingFactor, $height / $supersamplingFactor, $width, $height);
        $pieChart = $resized;

        ob_start();
        imagepng($pieChart);
        $dataUri = "data:image/png;base64," . base64_encode(ob_get_contents());
        ob_end_clean();

        Cache::put($poll->id, ['vote_count' => $voteCount, 'pie_chart' => $dataUri, 'colours' => $colours], now()->addDays(1));
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
