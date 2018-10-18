@extends('layouts.app')

@section('title', "Results for '" . $poll->question . "'")

@section('content')
    @if ($voted)
        <section class="primary-box">
            <span>Your vote has been recorded!</span><br>
        </section>
    @endif

    @if($poll->results_visible)
        @php ($sortedOptions = $poll->options->sortByDesc(function($option) use($poll) { return $poll->votes->where('poll_option_id', $option->id)->count(); }))
        @php ($nonZeroOptions = $sortedOptions->filter(function($option) use($poll) { return $poll->votes->where('poll_option_id', $option->id)->count() > 0; }))

        <section class="grid grid--large some-top-margin">
            <div class="some-bottom-margin">
                <div class="primary-box">
                    @php ($total = $poll->votes->count())

                    @foreach ($sortedOptions as $option)
                        @php ($votes = $poll->votes->where('poll_option_id', $option->id)->count())

                        <div class="some-more-bottom-margin">
                            <div>
                                <span>{{ $option->text }}</span>
                                <span style="float:right">{{ $votes }} votes</span>
                            </div>
                            <div>
                                <!-- TODO: title attribute -->
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
                @php ($cache = Cache::get($poll->id))

                <div class="primary-box">
                    <img src="{{ $cache['pie_chart']}}" style="display:block;margin: 0 auto">
                    <br>
                    @foreach ($nonZeroOptions as $option)
                    <!-- TODO: Generate images so this works on text browsers -->
                    <div style="display:inline-block;height:1rem;width:1rem;background-color:{{$cache['colours'][$option->id]}};margin-right:1rem"></div>
                    <span>{{ $option->text }}</span>
                    <br>
                    @endforeach
                </div>
            </div>
        </section>
    @else
        <section class="primary-box some-top-margin">
            <span>Results for this poll are hidden until the poll is closed.</span>
        </section>
    @endif
@endsection
