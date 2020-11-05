@extends('layouts.app')

@section('content')
<div class="container">
  @if (session('status'))
    <div class="alert alert-success" role="alert">
      {{ session('status') }}
    </div>
  @endif

  <div>
    <h5>Welcome, {{ auth()->user()->email }}</h5>
    <h2>Features:</h2>
    <ul>
      <li>User Login</li>
      <li>User Registration</li>
      <li>Email Verification</li>
      <li>Forget Password</li>
      <li>Reset Password</li>
    </ul>
    <p>
      <a target="_blank" role="button">Github Source Code</a>
    </p>
  </div>
</div>
@endsection