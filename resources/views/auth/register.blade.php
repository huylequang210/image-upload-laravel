@extends('layouts.app')

@section('section')
<div class="container h-full flex w-full">
  <div class="Art h-full hidden sm:block"></div>
  <div class="w-full md:w-1/3 h-full p-12">
    <div class="p-2 mb-4 font-bold border-b-2">{{ __('Register') }}</div>
    <div>
      <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-4">
          <label for="name">{{ __('Name') }}</label>

          <div class="">
            <input id="name" type="text" class="@error('name') is-invalid @enderror pt-1 pb-1 pl-2 w-full bg-gray-100 bg-rgba" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

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
            <input id="email" type="email" class="@error('email') is-invalid @enderror pt-1 pb-1 pl-2 w-full bg-gray-100 bg-rgba" name="email" value="{{ old('email') }}" required autocomplete="email">

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
            <input id="password" type="password" class="@error('password') is-invalid @enderror pt-1 pb-1 pl-2 w-full bg-gray-100 bg-rgba" name="password" required autocomplete="new-password">

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
            <input id="password-confirm" class="pt-1 pb-1 pl-2 w-full bg-gray-100 bg-rgba" type="password" name="password_confirmation" required autocomplete="new-password">
          </div>
        </div>

        <div class="mb-4">
          <div>
            <button type="submit" class="block w-full bg-indigo-400 pt-1 pb-1 bg-rgba text-center text-white">
                {{ __('Register') }}
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection