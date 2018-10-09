@extends('layouts.app')

@section('title', 'Create a Poll')

@section('content')
    <form action="{{ action('PollController@create') }}" method="post">
        @csrf

        <section class="grid grid-large">
            <div class="textfield">
                <input type="text" class="question" name="question" placeholder="Type your question here" required><br>
            </div>
        </section>

        @for ($i = 0; $i < 5; $i++)
        <section class="grid grid--large">
            <div class="textfield">
                <input type="text" class="option" name="option[]" placeholder="Enter a poll option" @if($i == 0 ) required @endif>
            </div>
            <div class="textfield">
                <input type="text" class="option" name="option[]" placeholder="Enter a poll option" @if($i == 0 ) required @endif>
            </div>
            <div class="textfield">
                <input type="text" class="option" name="option[]" placeholder="Enter a poll option">
            </div>
        </section>
        @endfor
        <br>

        <section>
                <input type="submit" class="btn" value="Create poll">
        </section>
    </form>
@endsection
