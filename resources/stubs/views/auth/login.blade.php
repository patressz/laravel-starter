@extends('layouts.master')

@section('content')
    <div class="container sm:max-w-md py-8">
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <x-form::input
                    type="email"
                    variable="email"
                    label="E-mail"
                    autofocus
                    autocomplete="username"
                    :value="old('email')"
                />
            </div>

            <div>
                <x-form::input
                    type="password"
                    variable="password"
                    label="Heslo"
                    autocomplete="current-password"
                />
            </div>

            <div>
                <x-form::check
                    variable="remember"
                    label="Pamätaj si ma"
                />
            </div>

            <div class="flex justify-center">
                <button type="submit" class="btn btn--secondary">
                    Prihlásiť
                </button>
            </div>
        </form>
    </div>
@endsection

