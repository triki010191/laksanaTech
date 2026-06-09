@props(['href', 'active' => false])

<a href="{{ $href }}"
   @class([
       'admin-nav-link block rounded-lg px-3 py-2.5 text-sm font-medium transition-colors',
       'text-slate-300 hover:bg-white/10 hover:text-white' => ! $active,
       'admin-nav-link--active bg-sky-500/15 text-white shadow-[inset_3px_0_0_0_var(--color-brand-primary)]' => $active,
   ])>
    {{ $slot }}
</a>
