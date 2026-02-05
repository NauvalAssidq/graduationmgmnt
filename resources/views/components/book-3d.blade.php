@props(['book' => null])

<style>
    .book-container {
        position: relative;
        /* Default mobile size */
        width: 200px;
        height: 280px;
        perspective: 1200px;
        margin: 0 auto;
        z-index: 10;
    }

    /* Desktop size */
    @media (min-width: 640px) {
        .book-container {
            width: 300px;
            height: 400px;
        }
    }

    .book {
        width: 100%;
        height: 100%;
        position: relative;
        transform-style: preserve-3d;
        transform: rotateX(20deg) rotateY(-30deg) rotateZ(5deg);
        transition: transform 1s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        cursor: pointer;
    }

    .book:hover, .book.is-open {
        transform: rotateX(10deg) rotateY(-10deg) rotateZ(0deg) translateY(-20px) translateX(20px);
    }

    /* FRONT COVER (Container) */
    .book-cover {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        transform-origin: left;
        transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        transform-style: preserve-3d;
        z-index: 20;
    }

    .book:hover .book-cover, .book.is-open .book-cover {
        transform: rotateY(-145deg);
    }

    /* Outer Side of Front Cover */
    .cover-outer {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #047857; /* UIN Primary */
        border-radius: 0 5px 5px 0;
        backface-visibility: hidden; /* Hides this side when flipped */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        box-shadow: inset 4px 0 10px rgba(0,0,0,0.1), 10px 10px 30px rgba(0,0,0,0.3);
        border-left: 2px solid rgba(255,255,255,0.1);
        z-index: 2;
    }

    /* Inner Side of Front Cover (The "White Page" behind cover) */
    .cover-inner {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #fdfbf7; /* Off-white paper */
        border-radius: 5px 0 0 5px;
        transform: rotateY(180deg); /* Text faces inwards */
        backface-visibility: hidden;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: inset 0 0 10px rgba(0,0,0,0.05);
    }

    /* SPINE */
    .book-spine {
        position: absolute;
        top: 0;
        left: 0;
        width: 40px; /* Thickness */
        height: 100%;
        background: #064e3b;
        transform: rotateY(-90deg) translateX(-20px); /* Centered spine pivot */
        transform-origin: left;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 5;
    }
    
    /* Adjust spine position to be clean */
    .book-spine {
        transform: rotateY(-90deg) translateX(-100%);
    }

    /* BACK COVER */
    .book-back {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #047857;
        transform: translateZ(-40px); /* Matches spine width */
        border-radius: 0 5px 5px 0;
        box-shadow: 10px 10px 30px rgba(0,0,0,0.3);
        z-index: 1;
    }

    /* PAGES BLOCK (The stack of pages on the right) */
    .book-pages-block {
        position: absolute;
        top: 4px; /* Slight inset */
        left: 2px;
        width: calc(100% - 6px);
        height: calc(100% - 8px);
        background: #fff;
        transform: translateZ(-35px); /* Positioned inside the spine */
        z-index: 2;
        box-shadow: inset 0 0 5px rgba(0,0,0,0.1);
        /* Gradient to simulate page layers on the side */
        background-image: repeating-linear-gradient(90deg, #f1f5f9 0px, #ffffff 1px, #f1f5f9 2px);
        border-right: 1px solid #cbd5e1;
    }

    /* The 'First Page' visible when book opens */
    .first-page {
        position: absolute;
        top: 4px;
        left: 2px;
        width: calc(100% - 6px);
        height: calc(100% - 8px);
        background: #fdfbf7;
        transform: translateZ(-2px); /* Just behind the cover */
        z-index: 15;
        border-radius: 0 4px 4px 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: inset 3px 0 5px rgba(0,0,0,0.05);
    }

    /* Inner Page Layers to create volume */
    .page-layer {
        position: absolute;
        top: 4px;
        left: 2px;
        width: calc(100% - 6px);
        height: calc(100% - 8px);
        background: #fff;
        border: 1px solid #f1f5f9;
        border-radius: 0 3px 3px 0;
        z-index: 5;
    }
</style>

<div class="book-container" x-data="{ open: false }">
    <div class="book" :class="open ? 'is-open' : ''" @click="open = !open">
        
        <!-- FLIPPABLE COVER GROUP -->
        <div class="book-cover">
            <!-- OUTER COVER DESIGN -->
            <div class="cover-outer">
                <!-- Top Decoration -->
                <div class="w-full flex justify-between items-center opacity-50">
                </div>
                
                <!-- Center Content -->
                <div class="text-center w-full space-y-4">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 mx-auto text-uin-gold drop-shadow-md">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L1 7l11 5 9-5-9-5zm0 9l2.5-1.25L12 8.5l-2.5 1.25L12 11zm0 2.5l-5-2.5-2 1V17h14v-5l-2-1-5 2.5z"/></svg>
                    </div>
                    <div class="space-y-1">
                        <h2 class="font-serif text-xl sm:text-3xl text-uin-gold font-bold tracking-wider">BUKU WISUDA</h2>
                        <div class="h-0.5 w-16 bg-uin-gold mx-auto opacity-70"></div>
                    </div>
                    @if($book)
                        <div class="text-white/90 text-xs sm:text-sm font-sans tracking-widest uppercase">
                            <p>{{ $book->gelombang }}</p>
                            <p class="opacity-75">Tahun {{ $book->tahun }}</p>
                        </div>
                    @else
                        <div class="text-white/90 text-xs sm:text-sm font-sans tracking-widest uppercase">
                            <p>Segera Hadir</p>
                        </div>
                    @endif
                </div>

                <!-- Bottom Decoration -->
                <div class="text-white/40 text-[10px] tracking-[0.3em] uppercase w-full text-center pb-2">
                    UIN Ar-Raniry
                </div>
            </div>

            <!-- INNER COVER (White Page Behind Cover) -->
            <div class="cover-inner relative overflow-hidden">
                 <!-- Paper Texture/Watermark -->
                 <div class="absolute inset-0 opacity-[0.03] pointer-events-none flex items-center justify-center transform scale-x-[-1]">
                    <svg class="w-48 h-48" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L1 7l11 5 9-5-9-5zm0 9l2.5-1.25L12 8.5l-2.5 1.25L12 11zm0 2.5l-5-2.5-2 1V17h14v-5l-2-1-5 2.5z"/></svg>
                 </div>
                 <div class="text-center p-6 opacity-60">
                     <p class="font-serif italic text-slate-400 text-sm">"Buku Wisuda"</p>
                 </div>
            </div>
        </div>

        <!-- SPINE -->
        <div class="book-spine">
            <span class="text-uin-gold text-xs font-bold tracking-widest rotate-90 whitespace-nowrap opacity-80">BUKU WISUDA</span>
        </div>

        <!-- FIRST PAGE (Visible on right when open) -->
        <div class="first-page">
            <div class="w-[80%] h-[90%] border border-slate-200 p-4 flex flex-col items-center justify-center text-center space-y-4">
                 <div class="w-12 h-12 text-uin-primary opacity-20">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L1 7l11 5 9-5-9-5zm0 9l2.5-1.25L12 8.5l-2.5 1.25L12 11zm0 2.5l-5-2.5-2 1V17h14v-5l-2-1-5 2.5z"/></svg>
                 </div>
                 <div class="space-y-2 w-full">
                     <div class="h-1 bg-slate-100 w-full rounded"></div>
                     <div class="h-1 bg-slate-100 w-5/6 rounded mx-auto"></div>
                     <div class="h-1 bg-slate-100 w-4/6 rounded mx-auto"></div>
                 </div>
                 <div class="pt-8">
                     <p class="text-[8px] uppercase tracking-widest text-slate-300">Buku Wisuda</p>
                 </div>
            </div>
        </div>

        <!-- LAYERED PAGES for Depth -->
        <div class="page-layer" style="transform: translateZ(-5px);"></div>
        <div class="page-layer" style="transform: translateZ(-10px);"></div>
        <div class="page-layer" style="transform: translateZ(-15px);"></div>
        <div class="page-layer" style="transform: translateZ(-20px);"></div>
        <div class="page-layer" style="transform: translateZ(-25px);"></div>
        <div class="page-layer" style="transform: translateZ(-30px);"></div>

        <!-- PAGES BLOCK (Thickness) -->
        <div class="book-pages-block"></div>

        <!-- BACK COVER -->
        <div class="book-back"></div>

    </div>
</div>
