@extends('layouts.main')
@section('title', 'Reset Password')

@section('content')
<div class="auth-page">
  <div class="auth-left">
    <div class="auth-left-content">
      <img src="{{ asset('images/greenorder_icon.png') }}" alt="GreenOrder" class="auth-left-logo">
      <h1>Create a New Password</h1>
      <p>Enter your email and a new password to complete the reset.</p>
    </div>
  </div>
  <div class="auth-right">
    <div class="auth-card">
      <div class="auth-card-header">
        <h2>Reset Password</h2>
        <p>Choose a strong password you haven't used before.</p>
      </div>

      @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
      @endif

      <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
          <label class="form-label" for="email">Email Address</label>
          <div class="input-wrapper">
            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $email) }}" placeholder="you@example.com" required>
          </div>
          @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label" for="password">New Password</label>
          <div class="input-wrapper">
            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="At least 8 characters" required>
          </div>
          @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label" for="password_confirmation">Confirm Password</label>
          <div class="input-wrapper">
            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-full btn-lg">Reset Password</button>
      </form>

      <div class="auth-card-footer">
        <a href="{{ route('auth.login') }}" onclick="event.preventDefault(); window.location.replace('{{ route('auth.login') }}');">Back to Sign In</a>
      </div>
    </div>
  </div>
</div>
<style>
</style>
@endsection

