@extends('layouts.app')

@section('title', $poll->question)

@php
$type = $poll->allow_multiple_answers ? "checkbox" : "radio";
@endphp

@section('content')
    @if($hasVoted)
        <section>
            <span>You have already voted on this poll.</span>

            <br>

            @if($poll->results_visible)
                <div class="some-top-margin">
                    <span><a href="{{ action('PollController@viewResults', ['poll' => $poll]) }}">Results</a></span>
                </div>
            @endif
        </section>
    @else
        <form action"={{ action('PollController@vote', ['poll' => $poll]) }}" method="post">
            @csrf

            @foreach ($poll->options as $option)
                <label class="{{$type}} no-bottom-margin">
                    <input type="{{$type}}" name="options[]" value="{{$option->id}}">
                    <span class="{{$type}}__label">{{$option->text}}</span>
                </label>

                <br>
            @endforeach

            <section class="some-top-margin">
                    <input type="submit" class="btn" value="Submit vote">
            </section>
        </form>
    @endif
@endsection
