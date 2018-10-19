@extends('layouts.app')

@section('title', 'Create a Poll')

@section('content')
    <form action="{{ action('PollController@create') }}" method="post">
        @csrf

        <!-- TODO: Get rid of <br>s -->

        <section class="grid grid--large">
            <div class="textfield">
                <span class="text-browser">Question:<br></span>

                <input type="text" class="question" name="question" placeholder="Type your question here" required>
            </div>
        </section>

        <br>

        <section>
            <span class="text-browser">Options:<br></span>

            @for ($i = 0; $i < 5; $i++)
                <div class="grid grid--large">
                    <div class="textfield">
                        <input type="text" class="option" name="options[]" placeholder="Enter a poll option" @if($i == 0 ) required @endif>
                    </div>
                    <div class="textfield">
                        <input type="text" class="option" name="options[]" placeholder="Enter a poll option" @if($i == 0 ) required @endif>
                    </div>
                    <div class="textfield">
                        <input type="text" class="option" name="options[]" placeholder="Enter a poll option">
                    </div>
                </div>
            @endfor

            <div class="text-browser"><br></div>
        </section>

        <section class="grid grid--large">
            <div class="some-top-margin">
                <label class="checkbox no-bottom-margin">
                    <input type="checkbox" name="allow_multiple_answers">
                    <span class="checkbox__label">Allow multiple answers</span>
                </label>
                <br>
                <label class="checkbox no-bottom-margin">
                    <input type="checkbox" name="hide_results_until_closed">
                    <span class="checkbox__label">Hide results until poll is closed</span>
                </label>
                <br>
                <label class="checkbox no-bottom-margin">
                    <input type="checkbox" name="automatically_close_poll">
                    <span class="checkbox__label">Automatically close poll at </span>
                    <input type="datetime-local" name="automatically_close_poll_datetime" class="inline-block" value="{{ Carbon\Carbon::now()->addHour()->format('Y-m-d\TH:i') }}" min="{{ Carbon\Carbon::now()->format('Y-m-d\TH:i') }}">
                </label>
                <br>
                <label class="checkbox no-bottom-margin">
                    <input type="checkbox" name="set_admin_password">
                    <span class="checkbox__label">Set an admin password: </span>
                    <input type="text" name="admin_password" class="inline-text">
                </label>

                <div class="text-browser"><br></div>
            </div>
            <div class="some-top-margin">
                <div class="some-bottom-margin">
                    <span>Duplicate vote checking:</span>
                </div>
                <label class="radio no-bottom-margin">
                    <input type="radio" name="duplicate_vote_checking" value="none" checked>
                    <span class="radio__label">None</span>
                </label>
                <br>
                <label class="radio no-bottom-margin">
                    <input type="radio" name="duplicate_vote_checking" value="cookies">
                    <span class="radio__label">Browser cookies</span>
                </label>
                <br>
                <label class="radio no-bottom-margin">
                    <input type="radio" name="duplicate_vote_checking" value="codes">
                    <span class="radio__label">Voting codes</span>
                </label>
                <br>
                <label class="number">
                    <input type="number" min="2" max="1000" value="10" name="number_of_codes">
                    <span class="radio__label">codes</span>
                </label>
            </div>
        </section>

        <div class="text-browser"><br></div>

        <section>
                <input type="submit" class="btn" value="Create poll">
        </section>
    </form>
@endsection
