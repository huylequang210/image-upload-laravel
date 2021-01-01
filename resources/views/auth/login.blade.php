@extends('layouts.app')
@section('title', 'Login')
@section('section')
<div class="container h-full flex w-full">
  <div class="Art h-full hidden sm:block"></div>
  <div class="w-full md:w-1/3 h-full p-12">
    <div class="p-2 mb-4 font-bold border-b-2">{{ __('Login') }}</div>
    <div>
      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-4">
          <label for="name">{{ __('Username') }}</label>
          <div>
            <input id="name" type="text"
              class="@error('name') is-invalid @enderror pt-1 pb-1 pl-2 w-full bg-gray-100 bg-rgba" name="name"
              value="{{ old('name') }}" required>
            @error('name')
              <span role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div>
          <label for="password">{{ __('Password') }}</label>
          <div>
            <input id="password" type="password"
            class="@error('password') is-invalid @enderror pt-1 pb-1 pl-2 w-full bg-gray-100 bg-rgba" name="password"
            required autocomplete="current-password">
            @error('password')
              <span role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <div>
          <div>
            <div>
              <input type="checkbox" name="remember"
                id="remember" {{ old('remember') ? 'checked' : '' }} class="mb-4">

              <label for="remember">
                {{ __('Remember Me') }}
              </label>
            </div>
          </div>
        </div>

        <div>
          <div>
            <button type="submit" class="mb-4 block w-full bg-indigo-400 pt-1 pb-1 bg-rgba text-center text-white">
            {{ __('Login') }}
            </button>

            {{-- @if (Route::has('password.request'))
              <a href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
              </a>
            @endif --}}
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection