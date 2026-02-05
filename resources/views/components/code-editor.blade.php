@props([
    'name',
    'label' => false,
    'value' => '',
    'mode' => 'css', // 'css' or 'htmlmixed'
    'height' => '300px',
    'class' => ''
])

<div class="w-full relative group {{ $class }}"
     x-data="{
        fullscreen: false,
        editor: null,
        
        init() {
            this.waitForCodeMirror();
        },

        waitForCodeMirror() {
            if (typeof CodeMirror === 'undefined') {
                setTimeout(() => this.waitForCodeMirror(), 100);
                return;
            }
            // Initial mount to inline
            this.mountEditor(this.$refs.inlineMount);
        },

        mountEditor(targetNode) {
            // Ensure target is empty
            targetNode.innerHTML = '';
            
            const hiddenInput = this.$refs.input;

            const editor = CodeMirror(targetNode, {
                value: hiddenInput.value || '', 
                mode: '{{ $mode }}',
                theme: 'dracula',
                lineNumbers: true,
                lineWrapping: true,
                viewportMargin: Infinity,
                autoCloseTags: true,
                autoCloseBrackets: true,
            });

            editor.setSize('100%', '100%');

            editor.on('change', (instance) => {
                const val = instance.getValue();
                hiddenInput.value = val;
                hiddenInput.dispatchEvent(new Event('input'));
            });

            this.editor = editor;
            
            // Refresh layout after short delay to handle transitions
            setTimeout(() => editor.refresh(), 100);
        },

        toggleFullscreen() {
            // 1. Capture current value
            const currentValue = this.editor ? this.editor.getValue() : this.$refs.input.value;
            this.$refs.input.value = currentValue; // Sync just in case

            // 2. Destroy current instance (visually clear it)
            if (this.editor) {
                // Determine where it was mounted
                const oldWrapper = this.editor.getWrapperElement();
                if (oldWrapper && oldWrapper.parentNode) {
                    oldWrapper.parentNode.innerHTML = '';
                }
                this.editor = null;
            }
            
            // 3. Toggle State
            this.fullscreen = !this.fullscreen;
            
            // 4. Re-mount in new location
            this.$nextTick(() => {
                const target = this.fullscreen ? this.$refs.fullscreenMount : this.$refs.inlineMount;
                this.mountEditor(target);
                
                // Focus
                this.editor.focus();
                
                // Lock Body Scroll
                document.body.style.overflow = this.fullscreen ? 'hidden' : '';
            });
        }
     }"
     @keydown.escape.window="if(fullscreen) toggleFullscreen()"
     x-id="['code-editor']"
>
    <!-- Label -->
    @if($label)
        <label :for="$id('code-editor')" class="block text-sm font-medium text-slate-700 mb-1">
            {{ $label }}
        </label>
    @endif

    <!-- Hidden Input -->
    <textarea x-ref="input" name="{{ $name }}" :id="$id('code-editor')" class="hidden">{{ $value }}</textarea>

    <!-- INLINE CONTAINER -->
    <div class="relative rounded-lg overflow-hidden border border-gray-300 shadow-sm bg-[#282a36]"
         :style="'height: {{ $height }}'"
    >
        <!-- Editor attaches here initially -->
        <div x-ref="inlineMount" class="h-full w-full"></div>

        <!-- Inline Maximize Button -->
        <button type="button" 
                @click="toggleFullscreen()"
                class="absolute bottom-4 right-4 z-20 p-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full shadow-lg opacity-50 group-hover:opacity-100 transition-all transform hover:scale-105"
                title="Open Fullscreen Terminal"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
        </button>
    </div>

    <!-- FULLSCREEN MODAL (Teleported to Body) -->
    <template x-teleport="body">
        <div x-show="fullscreen" 
             style="display: none;" 
             class="fixed inset-0 z-[9999] bg-[#282a36] flex flex-col"
        >
            <!-- Header -->
            <div class="flex items-center justify-between px-4 py-3 bg-[#1e1f29] border-b border-[#44475a] text-gray-300 shrink-0">
                <div class="flex items-center gap-3">
                    <span class="font-mono text-sm font-bold text-emerald-400">TERMINAL MODE</span>
                    <span class="text-xs text-gray-500">|</span>
                    <span class="text-xs font-semibold tracking-wider uppercase">{{ $mode }}</span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-xs text-slate-500 hidden md:block">(ESC to exit)</span>
                    <button type="button" @click="toggleFullscreen()" class="text-gray-400 hover:text-white hover:bg-white/10 p-1 rounded transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Editor attaches here when fullscreen -->
            <div class="flex-1 relative w-full h-full overflow-hidden">
                <div x-ref="fullscreenMount" class="absolute inset-0 w-full h-full"></div>
            </div>
        </div>
    </template>
</div>

<!-- Assets -->
@once
    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/dracula.min.css">
        <style>
            .CodeMirror { font-family: 'Fira Code', 'Consolas', monospace; font-size: 14px; border-radius: 0.5rem; }
            .cm-s-dracula .CodeMirror-gutters { background-color: #282a36 !important; border-right: 1px solid #44475a; }
        </style>
    @endpush
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/css/css.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/xml/xml.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/htmlmixed/htmlmixed.min.js"></script>
    @endpush
@endonce
