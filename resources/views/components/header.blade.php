@props(['title' => 'Dashboard'])

<header class="h-16 bg-white border-b border-gray-300 flex items-center justify-between px-8 sticky top-0 z-20">
    <h2 class="text-lg font-semibold text-slate-800">{{ $title }}</h2>
    
    <div class="flex items-center gap-6">
        <x-profile-dropdown />
    </div>
</header>

