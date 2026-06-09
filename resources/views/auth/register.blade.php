@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-md px-4 py-16">
    <h1 class="text-page-title">Daftar Akun</h1>
    <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
        @csrf
        <div>
            <label class="mb-1 block text-card-body font-medium text-heading">Nama</label>
            <input name="name" type="text" value="{{ old('name') }}" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-card-body">
            @error('name')<p class="mt-1 text-caption text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="mb-1 block text-card-body font-medium text-heading">Email</label>
            <input name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-card-body">
            @error('email')<p class="mt-1 text-caption text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="mb-1 block text-card-body font-medium text-heading">Telepon</label>
            <input name="phone" type="text" value="{{ old('phone') }}" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-card-body">
        </div>
        <div>
            <label class="mb-1 block text-card-body font-medium text-heading">Password</label>
            <input name="password" type="password" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-card-body">
            @error('password')<p class="mt-1 text-caption text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="mb-1 block text-card-body font-medium text-heading">Konfirmasi Password</label>
            <input name="password_confirmation" type="password" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-card-body">
        </div>
        <button type="submit" class="text-btn w-full rounded-2xl bg-brand-primary py-3 text-white">Daftar</button>
    </form>
    <p class="text-card-body mt-6 text-center">Sudah punya akun? <a href="{{ route('login') }}" class="text-link">Masuk</a></p>
</section>
@endsection
