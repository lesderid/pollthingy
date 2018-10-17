@extends('layouts.app')

@section('title', "Results for '" . $poll->question . "'")

@section('content')
    @if ($voted)
        <section class="primary-box">
            <span>Your vote has been recorded!</span><br>
        </section>
    @endif

    @if($poll->results_visible)
        <section class="grid grid--large some-top-margin">
            <div class="some-bottom-margin">
                <div class="primary-box">
                    @php ($total = $poll->votes->count())

                    @foreach ($poll->options->sortByDesc(function($option) use($poll) { return $poll->votes->where('poll_option_id', $option->id)->count(); }) as $option)
                        @php ($votes = $poll->votes->where('poll_option_id', $option->id)->count())

                        <div class="some-more-bottom-margin">
                            <div>
                                <span>{{ $option->text }}</span>
                                <span style="float:right">{{ $votes }} votes</span>
                            </div>
                            <div>
                                <div style="background-color:#e83fb8;height:2rem;width:{{ $votes / $total * 100 }}%;">
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
                <div class="primary-box">
                    <span>TODO: Put a pie chart here.</span>
                </div>
            </div>
        </section>
    @else
        <section class="primary-box some-top-margin">
            <span>Results for this poll are hidden until the poll is closed.</span>
        </section>
    @endif
@endsection
