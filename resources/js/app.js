import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
// Jangan Alpine.start() di sini — @livewireScripts yang start Alpine.
// Double-init merusak wire:click di panel admin (tombol Edit, dll.).
