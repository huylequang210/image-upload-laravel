@extends('layouts.app')

@section('section')
<div class="container">
  <div class="Art h-full hidden sm:block"></div>
  <div class="w-full md:w-1/3 h-full p-12">
    <div class="card-header p-2 mb-4 font-bold border-b-2">{{ __('Reset Password') }}</div>

    <div>
      @if (session('status'))
        <div class="alert alert-success" role="alert">
          {{ session('status') }}
        </div>
      @endif

      <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div>
          <label for="email">{{ __('E-Mail Address') }}</label>
          <div>
            <input id="email" type="email" class="@error('email') is-invalid @enderror pt-1 pb-1 pl-2 w-full bg-gray-100 bg-rgba" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

            @error('email')
              <span role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>
        <div>
          <button class="mt-2">
              {{ __('Send Password Reset Link') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection