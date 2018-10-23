@extends('layouts.app')

@section('title', "Edit poll '" . $poll->question . "'")

@section('content')
    @if ($poll->closed)
        <div class="primary-box">
            <span>The poll is closed and can no longer be edited.</span>
        </div>
    @else
        @if ($changed || $extraCodes != null)
            <div class="primary-box">
                @if ($changed)
                    <span>Your changes have been saved.</span>
                @elseif ($extraCodes != null)
                    <span>Your extra voting URLs have been generated:</span>
                    <textarea class="copyarea" readonly>{{ collect($extraCodes)->map(function($c) use($poll) { return action('PollController@view', ['poll' => $poll, 'code' => $c]); })->implode("\n") }}</textarea>
                @endif
            </div>

            <div class="text-browser"><br></div>
        @endif

        <section class="grid grid--large">
            <div class="some-top-margin">
                <form action="{{ action('PollController@edit', ['poll' => $poll, 'password' => $poll->admin_password]) }}" method="post">
                    @method('PATCH')

                    @csrf

                    <div class="some-bottom-margin">
                        <span>Change settings:</span>
                    </div>
                    <label class="checkbox no-bottom-margin">
                        <input type="checkbox" name="hide_results_until_closed" @if($poll->hide_results_until_closed) checked @endif>
                        <span class="checkbox__label">Hide results until poll is closed</span>
                    </label>
                    <br>
                    <label class="checkbox no-bottom-margin">
                        <input type="checkbox" name="automatically_close_poll" @if($poll->closes_at != null) checked @endif>
                        <span class="checkbox__label">Automatically close poll at </span>
                        <input type="datetime-local" name="automatically_close_poll_datetime" class="inline-block" value="{{ $poll->closes_at != null ? Carbon\Carbon::parse($poll->closes_at)->format('Y-m-d\TH:i') : Carbon\Carbon::now()->addHour()->format('Y-m-d\TH:i') }}" min="{{ Carbon\Carbon::now()->format('Y-m-d\TH:i') }}">
                    </label>
                    <br>
                    <label class="checkbox no-bottom-margin">
                        <input type="checkbox" name="set_admin_password" @if($poll->admin_password != null) checked @endif>
                        <span class="checkbox__label">Set an admin password: </span>
                        <input type="text" name="admin_password" class="inline-text" @if($poll->admin_password != null) value="{{ $poll->admin_password}}" @endif>
                    </label>
                    <br>
                    <div class="some-top-margin">
                        <input type="submit" class="btn" value="Confirm">
                    </div>
                </form>

                <div class="text-browser"><br></div>
            </div>

            @if ($poll->duplicate_vote_checking == 'codes')
                <div class="some-top-margin">
                    <form action="{{ action('PollController@edit', ['poll' => $poll, 'password' => $poll->admin_password]) }}" method="post">
                        @method('PATCH')

                        @csrf

                        <div class="some-bottom-margin">
                            <span>Generate extra voting codes: </span>
                        </div>
                        <label class="free-number">
                            <input type="number" min="1" max="1000" value="10" name="extra_codes">
                            <span class="radio__label">codes</span>
                        </label>
                        <br>
                        <div class="some-top-margin">
                            <input type="submit" class="btn" value="Confirm">
                        </div>
                    </form>
                </div>
            @endif
        </section>

        <div class="text-browser"><br></div>

        <section>
            <form action="{{ action('PollController@edit', ['poll' => $poll, 'password' => $poll->admin_password]) }}" method="post">
                @method('PATCH')

                @csrf

                <div class="some-top-margin">
                    <input type="hidden" name="close_now" value="1">

                    <input type="submit" class="btn huge-button" value="Close poll now">
                </div>
            </form>
        </section>
    @endif
@endsection
