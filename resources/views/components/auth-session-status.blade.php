@props(['status'])

@if ($status)
    <div class="bg-secondary-container/20 border border-secondary-container/50 rounded-lg p-3 flex items-center gap-2">
        <span class="material-symbols-outlined text-secondary text-[18px]">check_circle</span>
        <p class="text-label-sm text-secondary font-medium">{{ $status }}</p>
    </div>
@endif
