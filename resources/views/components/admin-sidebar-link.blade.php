@props(['id', 'icon', 'label', 'href', 'current'])

@php
    $active = ($id === $current);
    $activeClass = $active ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800';
    $iconClass = $active ? 'text-white' : 'text-slate-500';
@endphp

<a href="{{ $href }}" 
   class="flex items-center gap-3 px-4 py-3 mx-2 my-1.5 rounded-xl text-sm font-medium transition-all duration-200 {{ $activeClass }}">
    <span class="material-symbols-outlined text-[20px] {{ $iconClass }}" 
          @if($active) style="font-variation-settings:'FILL' 1;" @endif>
        {{ $icon }}
    </span>
    <span class="sidebar-label">{{ $label }}</span>
</a>
