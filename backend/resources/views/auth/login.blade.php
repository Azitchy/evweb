@extends('layouts.auth')
@section('title', 'Login')
@section('auth-subtitle', 'Sign in to manage your EV charging network')

@section('content')
<form method="POST" action="{{ route('web.login') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label fw-semibold">Email Address</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="admin@evweb.com">
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label fw-semibold">Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" class="form-control" required placeholder="••••••••">
        </div>
    </div>
    <div class="mb-3 form-check">
        <input type="checkbox" name="remember" class="form-check-input" id="remember">
        <label class="form-check-label" for="remember">Remember me</label>
    </div>
    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
        <i class="bi bi-box-arrow-in-right"></i> Sign In
    </button>
</form>
@endsection
