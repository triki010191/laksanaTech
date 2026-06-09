@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-md px-4 py-16">
    <h1 class="text-page-title">Masuk</h1>
    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
        @csrf
        <div>
            <label class="mb-1 block text-card-body font-medium text-heading">Email</label>
            <input name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-card-body">
            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="mb-1 block text-card-body font-medium text-heading">Password</label>
            <input name="password" type="password" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-card-body">
        </div>
        <label class="flex items-center gap-2 text-card-body"><input type="checkbox" name="remember"> Ingat saya</label>
        <button type="submit" class="text-btn w-full rounded-2xl bg-brand-primary py-3 text-white">Masuk</button>
    </form>
    <p class="text-card-body mt-6 text-center">Belum punya akun? <a href="{{ route('register') }}" class="text-link">Daftar</a></p>
</section>
@endsection
