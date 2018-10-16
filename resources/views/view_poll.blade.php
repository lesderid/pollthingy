@extends('layouts.app')

@section('title', $poll->question)

@php
$type = $poll->allow_multiple_answers ? "checkbox" : "radio";
@endphp

@section('content')
    <form>
        <section class="grid grid--large">
            <div>
                @foreach ($poll->options as $option)
                    <label class="{{$type}} no-bottom-margin">
                        <input type="{{$type}}" name="option" value="{{$option->id}}">
                        <span class="{{$type}}__label">{{$option->text}}</span>
                    </label>

                    <br>
                @endforeach
            </div>
            <div>
                <!-- TODO: Pie chart (http://lavacharts.com/#example-pie) -->
            </div>
        </section>

        <section class="some-top-margin">
                <input type="submit" class="btn" value="Submit vote">
        </section>
    </form>
@endsection
