@include('partials.event-grid-list', ['events' => $events])

@if($events->isEmpty())
<div class="col-span-1 sm:col-span-2 lg:col-span-3 py-20 text-center text-slate-400 bg-slate-50 rounded-[2.5rem] border-2 border-dashed border-slate-200">
    <span class="material-symbols-outlined text-5xl mb-4 text-slate-300">event_busy</span>
    <p class="font-medium text-lg">{{ __('site.no_active_events') }}</p>
</div>
@endif



