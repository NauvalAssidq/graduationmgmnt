@props([
    'name',
    'label' => 'Select Option',
    'options' => [], 
    'value' => null,
    'searchable' => false,
    'placeholder' => 'Select...',
    'class' => '',
    'submitOnChange' => false
])

<div x-data='{
        open: false,
        search: "",
        selected: @json($value),
        rawOptions: @json($options),
        submitOnChange: @json($submitOnChange),

        get optionsList() {
            // Normalize everything to [{value: "val", label: "Label"}]
            if (Array.isArray(this.rawOptions)) {
                return this.rawOptions.map(opt => ({ value: opt, label: opt }));
            }
            return Object.entries(this.rawOptions).map(([key, val]) => ({ value: key, label: val }));
        },

        get filteredOptions() {
            let list = this.optionsList;
            if (this.search && {{ $searchable ? "true" : "false" }}) {
                const q = this.search.toLowerCase();
                list = list.filter(opt => String(opt.label).toLowerCase().includes(q));
            }
            return list;
        },

        get selectedLabel() {
            if (this.selected === null || this.selected === "") return "{{ $placeholder }}";
            const found = this.optionsList.find(opt => String(opt.value) === String(this.selected));
            return found ? found.label : this.selected;
        },

        select(val) {
            this.selected = val;
            this.open = false;
            $dispatch("change", val);
            if ({{ $submitOnChange ? "true" : "false" }}) {
                $nextTick(() => $el.closest("form")?.submit());
            }
        }
    }'
    class="relative {{ $class }}"
    @click.outside="open = false"
>
    <!-- Hidden Input -->
    <input type="hidden" name="{{ $name }}" :value="selected">

    <!-- Trigger -->
    <button type="button"
            @click="open = !open"
            class="w-full flex items-center justify-between px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
    >
        <span x-text="selectedLabel" :class="{'text-gray-500': !selected, 'text-gray-900': selected}" class="truncate"></span>
        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Dropdown -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl max-h-60 overflow-hidden flex flex-col"
         style="display: none;"
    >
        @if($searchable)
        <div class="p-2 border-b border-gray-100 bg-gray-50">
            <input type="text" x-model="search" class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:border-emerald-500" placeholder="Cari...">
        </div>
        @endif

        <div class="overflow-y-auto flex-1 p-1 custom-scrollbar">
            <!-- Reset -->
            <div @click="select('')"
                 class="px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 rounded cursor-pointer transition-colors"
            >
                {{ $placeholder }} (Reset)
            </div>

            <!-- Options -->
            <template x-for="opt in filteredOptions" :key="opt.value">
                <div @click="select(opt.value)"
                     class="px-3 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 rounded cursor-pointer transition-colors flex items-center justify-between"
                     :class="{'bg-emerald-50 text-emerald-800 font-medium': String(selected) === String(opt.value)}"
                >
                    <span x-text="opt.label"></span>
                    <svg x-show="String(selected) === String(opt.value)" class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </template>

             <div x-show="filteredOptions.length === 0" class="px-3 py-4 text-sm text-center text-gray-400 italic">
                Tidak ada hasil.
            </div>
        </div>
    </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
</style>
