@extends('layouts.app')

@section('title', "Results for '" . $poll->question . "'")

@section('content')
    @if ($voted)
        <section class="primary-box">
            <span>Your vote has been recorded!</span><br>
        </section>

        <div class="text-browser"><br></div>
    @endif

    @if ($poll->results_visible)
        @php
            $sortedOptions = $poll->options->sortByDesc(function($option) use($poll) { return $poll->votes->where('poll_option_id', $option->id)->count(); });
            $nonZeroOptions = $sortedOptions->filter(function($option) use($poll) { return $poll->votes->where('poll_option_id', $option->id)->count() > 0; });

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
                                <span style="float:right">{{ $votes }} votes</span>
                            </div>
                            <div>
                                <div style="background-color:#e83fb8;height:2rem;width:{{ $total == 0 ? 0 : ($votes / $total * 100) }}%;" "{{ $total == 0 ? 0 : ($votes / $total * 100)}}% of votes">
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
                        <img src="{{ $cache['pie_chart']}}" style="display:block;margin: 0 auto">

                        <br>

                        <div class="text-browser"><br></div>

                        @foreach ($nonZeroOptions as $option)
                            <img src="{{ $cache['colour_squares'][$option->id] }}" style="display:inline-block">
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
