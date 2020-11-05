@extends('layouts.app')

@section('content')
<div class="container">
  <div>
    <div class="card-header">{{ __('Reset Password') }}</div>

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
            <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

            @error('email')
              <span role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>
        <div>
          <button>
              {{ __('Send Password Reset Link') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection