@extends('layouts.app')

@section('title', "Results for '" . $poll->question . "'")

@section('content')
    @if ($voted)
        <section class="primary-box">
            <span>Your vote has been recorded!</span><br>
        </section>

        <div class="text-browser"><br></div>
    @elseif ($alreadyClosed)
        <section class="primary-box">
            <span>The poll is already closed.</span><br>
        </section>

        <div class="text-browser"><br></div>
    @endif

    @if ($poll->results_visible)
        @php
            $sortedOptions = $poll->options->sortByDesc(function($option) { return $option->vote_count; });
            $nonZeroOptions = $sortedOptions->filter(function($option) { return $option->vote_count > 0; });

            $cache = Cache::get($poll->id);

            $total = $cache['vote_count'];
        @endphp

        <section class="grid grid--large some-top-margin">
            <div class="some-bottom-margin">
                <div class="primary-box">

                    @foreach ($sortedOptions as $option)
                        @php ($votes = $poll->votes->where('poll_option_id', $option->id)->count())

                        <div class="some-more-bottom-margin">
                            <div>
                                <span>{{ $option->text }}</span>
                                <span class="text-browser">:</span>
                                <span class="float-right">{{ $votes }} votes</span>
                            </div>
                            <div>
                                <div class="vote-bar" style="width:{{ $total == 0 ? 0 : ($votes / $total * 100) }}%;">
                                    <div class="text-browser">
                                        <span>{{ $total == 0 ? 0 : round($votes / $total * 100, 2) }}% of votes</span>
                                        <br><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="some-top-margin">
                        <span>Total votes: {{ $total }}</span>
                    </div>
                </div>
            </div>
            <div>

                @if ($total != 0)
                    <div class="primary-box">
                        <img src="{{ $cache['pie_chart']}}" class="centered-image">

                        <br>

                        <div class="text-browser"><br></div>

                        @foreach ($nonZeroOptions as $option)
                            <img src="{{ $cache['colour_squares'][$option->id] }}" class="inline-block">
                            <span>{{ $option->text }}</span>

                            <br>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @else
        <section class="primary-box some-top-margin">
            <span>Results for this poll are hidden until the poll is closed.</span>
        </section>
    @endif
@endsection
