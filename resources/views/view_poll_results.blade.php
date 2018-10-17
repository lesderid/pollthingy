@extends('layouts.app')

@section('title', "Results for '" . $poll->question . "'")

@section('content')
    @if ($voted)
        <section class="primary-box">
            <span>Your vote has been recorded!</span><br>
        </section>
    @endif

    @if($poll->results_visible)
        <section class="grid grid--large @if($voted) some-top-margin @endif">
            <div>
                <div class="primary-box">
                    <span>TODO: Put results here.</span>
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
