@extends('layouts.app')

@section('title', $poll->question)

@php
$type = $poll->allow_multiple_answers ? "checkbox" : "radio";
@endphp

@section('content')
    @if ($new)
        <section class="primary-box">
            <span>Your poll has been created!</span><br>

            <div class="text-browser"><br></div>

            @if ($poll->admin_password != null)
                <div class="some-top-margin">
                    <span>Admin URL: <a href="{{ action('PollController@admin', ['poll' => $poll, 'password' => $poll->admin_password]) }}">{{ action('PollController@admin', ['poll' => $poll, 'password' => $poll->admin_password]) }}</a></span>
                </div>

                <div class="text-browser"><br></div>
            @endif

            <div class="some-top-margin">
                @if ($poll->duplicate_vote_checking == 'codes')
                    <span>Voting URLs:</span>
                    <textarea class="copyarea" readonly>{{ $poll->voting_codes()->get()->map(function($c) use($poll) { return action('PollController@view', ['poll' => $poll, 'code' => $c]); })->implode("\n") }}</textarea>
                @else
                    <span>Poll URL: <a href="{{ action('PollController@view', ['poll' => $poll]) }}">{{ action('PollController@view', ['poll' => $poll]) }}</a></span>
                @endif
            </div>
        </section>
    @endif

    <section @if($new) class="some-top-margin" @endif>
        @if ($hasVoted)
            @if (!$new || $poll->duplicate_vote_checking != 'codes')
                <div class="text-browser"><br></div>

                <div class="primary-box">
                    <span>You have already voted on this poll or need a code to vote.</span>
                </div>

                @if ($poll->results_visible)
                    <div class="some-top-margin">
                        <span><a href="{{ action('PollController@viewResults', ['poll' => $poll]) }}">Results</a></span>
                    </div>
                @endif
            @endif
        @else
            <div class="text-browser"><br></div>

            @if ($code != null)
                <form action="{{ action('PollController@vote', ['poll' => $poll, 'code' => $code]) }}" method="post">
            @else
                <form action="{{ action('PollController@vote', ['poll' => $poll]) }}" method="post">
            @endif

                @csrf

                @foreach ($poll->options as $option)
                    <label class="{{$type}} no-bottom-margin">
                        <input type="{{$type}}" name="options[]" value="{{$option->id}}">
                        <span class="{{$type}}__label">{{$option->text}}</span>
                    </label>

                    <br>
                @endforeach

                <div class="some-top-margin">
                        <input type="submit" class="btn" value="Submit vote">
                </div>
            </form>
        @endif
    </section>
@endsection
