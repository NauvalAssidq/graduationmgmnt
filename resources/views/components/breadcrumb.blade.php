@props(['items' => []])

<nav class="flex text-sm text-slate-500 mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center font-medium hover:text-emerald-600 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                Dashboard
            </a>
        </li>
        @foreach($items as $label => $link)
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    @if(is_array($link))
                        {{-- Handle if item is just text (active page) --}}
                        <span class="ml-1 font-medium text-emerald-600 md:ml-2">{{ $label }}</span>
                    @elseif(is_null($link) || $loop->last)
                        <span class="ml-1 font-medium text-emerald-600 md:ml-2">{{ $label }}</span>
                    @else
                        <a href="{{ $link }}" class="ml-1 font-medium hover:text-emerald-600 md:ml-2 transition-colors">{{ $label }}</a>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>
