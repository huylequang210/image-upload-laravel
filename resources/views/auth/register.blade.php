@extends('layouts.app')

@section('section')
<div class="container h-full flex">
  <div class="Art h-full"></div>
  <div class="h-full p-16 w-screen">
    <div class="p-2 mb-4 font-bold border-b-2">{{ __('Register') }}</div>
    <div>
      <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-4">
          <label for="name">{{ __('Name') }}</label>

          <div class="">
            <input id="name" type="text" class="@error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

            @error('name')
              <span role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="mb-4">
          <label for="email" >{{ __('E-Mail Address') }}</label>

          <div class="">
            <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

            @error('email')
              <span role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>

        <div class="mb-4">
          <label for="password">{{ __('Password') }}</label>

          <div class="">
            <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

            @error('password')
              <span role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div class="mb-4">
          <label for="password-confirm">{{ __('Confirm Password') }}</label>

          <div class="">
            <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">
          </div>
        </div>

        <div class="mb-4">
          <div>
            <button type="submit">
                {{ __('Register') }}
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection