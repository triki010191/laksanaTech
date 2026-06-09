@extends('layouts.customer')

@section('content')
<h1 class="font-display text-2xl font-bold">Proyek Saya</h1>
<div class="mt-6 grid gap-4">
    @forelse($projects as $project)
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex justify-between">
                <h2 class="font-semibold">{{ $project->name }}</h2>
                <span class="text-sm capitalize text-slate-500">{{ $project->status }}</span>
            </div>
            <div class="mt-3 h-2 rounded-full bg-slate-100">
                <div class="h-2 rounded-full bg-brand-primary" style="width: {{ $project->progress }}%"></div>
            </div>
            <p class="mt-2 text-xs text-slate-500">Progress: {{ $project->progress }}%</p>
        </div>
    @empty
        <p class="text-slate-500">Belum ada proyek aktif.</p>
    @endforelse
</div>
<div class="mt-4">{{ $projects->links() }}</div>
@endsection
