<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flipbook - {{ $book->nama_buku }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #333;
            margin: 0;
            padding: 0;
            overflow: hidden;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .header {
            background: rgba(0,0,0,0.5);
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
        }
        .flipbook-container {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            overflow: hidden;
            background-color: #333;
        }
        /* Specific override for the flipbook element created by the library */
        .stf__wrapper {
            margin: 0 auto;
        }
        .flip-book {
           /* Box shadow handled by library or specific pages */
        }
        .page {
            padding: 0;
            background-color: white;
            /* Ensure page has a shadow or border */
            box-shadow: inset -1px 0 5px rgba(0,0,0,0.1);
        }
        /* Hard cover style if needed */
        .page.--hard {
            background-color: #f7f7f7;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('buku.show', $book->slug) }}" class="text-white hover:text-gray-300 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back
            </a>
            <h1 class="font-bold text-lg hidden sm:block">{{ $book->nama_buku }}</h1>
        </div>
        <div class="controls">
            <button id="prevBtn" class="bg-white/10 hover:bg-white/20 text-white px-3 py-1 rounded">Prev</button>
            <span id="pageInfo" class="text-sm self-center">Loading...</span>
            <button id="nextBtn" class="bg-white/10 hover:bg-white/20 text-white px-3 py-1 rounded">Next</button>
        </div>
    </div>

    <div class="flipbook-container">
        <div id="book" class="flip-book bg-white hidden">
            <!-- Pages will be injected here -->
        </div>
        <div id="loader" class="text-white">
            <svg class="animate-spin h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-2 text-center">Loading PDF...</p>
        </div>
    </div>

    <!-- PDF.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    </script>

    <!-- Page Flip -->
    <script src="https://cdn.jsdelivr.net/npm/page-flip/dist/js/page-flip.browser.js"></script>

    <script>
        const pdfUrl = "{{ asset('storage/' . $book->file_pdf) }}";
        const bookElement = document.getElementById('book');
        const loader = document.getElementById('loader');
        const pageInfo = document.getElementById('pageInfo');
        
        let pageFlip;
        let pdfDoc = null;

        // A4 Aspect Ratio (Width / Height)
        // A4 is 210mm x 297mm approx 0.707
        const A4_RATIO = 210 / 297; 

        async function loadApp() {
            try {
                // 1. Load PDF
                pdfDoc = await pdfjsLib.getDocument(pdfUrl).promise;
                const totalPages = pdfDoc.numPages;
                pageInfo.innerText = `1 / ${totalPages}`;

                // 2. Render Pages
                for (let i = 1; i <= totalPages; i++) {
                    const page = await pdfDoc.getPage(i);
                    // Use a standard scale to get good quality, but the visual size will be controlled by CSS/Flipbook
                    const viewport = page.getViewport({ scale: 2 }); 
                    
                    const div = document.createElement('div');
                    // Mark first and last pages as hard covers
                    if (i === 1 || i === totalPages) {
                        div.className = 'page --hard';
                    } else {
                        div.className = 'page';
                    }
                    
                    // Logic: Even if PDF is not A4, we center it in an A4 container or stretch it?
                    // "It should show in A4 size". Let's force the canvas to fit the container.
                    div.style.backgroundColor = 'white';
                    
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    
                    // Set canvas internal dimensions to match PDF viewport for quality
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;
                    
                    // Style canvas to fit the div
                    canvas.style.width = '100%';
                    canvas.style.height = '100%';
                    canvas.style.objectFit = 'contain'; // Maintain aspect ratio within the A4 page
                    
                    const renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    
                    await page.render(renderContext).promise;
                    
                    div.appendChild(canvas);
                    bookElement.appendChild(div);
                }

                // 3. Initialize Flipbook
                loader.classList.add('hidden');
                bookElement.classList.remove('hidden');

                // Calculate optimal size based on window height to fit A4
                const container = document.querySelector('.flipbook-container');
                const availableHeight = container.clientHeight - 20; // Padding
                const availableWidth = container.clientWidth - 20;

                // Max height limited by available height
                let bookHeight = availableHeight;
                // Width for SINGLE page based on A4 ratio
                let bookWidth = bookHeight * A4_RATIO;

                // Check if 2 pages (open book) fit in width
                if (bookWidth * 2 > availableWidth) {
                    bookWidth = availableWidth / 2;
                    bookHeight = bookWidth / A4_RATIO;
                }

                pageFlip = new St.PageFlip(bookElement, {
                    width: bookWidth, // Width of ONE page
                    height: bookHeight,
                    size: 'fixed', // Use fixed to respect our A4 calc
                    // minWidth: 200,
                    // maxWidth: 800,
                    // minHeight: 300,
                    // maxHeight: 1000,
                    showCover: true,
                    drawShadow: true,
                    maxShadowOpacity: 0.2,
                    mobileScrollSupport: false,
                    usePortrait: false, // Force 2-page view on large screens, auto on mobile
                    startPage: 0 // Explicitly start at 0 (Cover)
                });

                pageFlip.loadFromHTML(document.querySelectorAll('.page'));

                // Events
                pageFlip.on('flip', (e) => {
                    // +1 because index is 0-based
                    pageInfo.innerText = `${e.data + 1} / ${totalPages}`;
                });

                document.getElementById('prevBtn').addEventListener('click', () => {
                    pageFlip.flipPrev();
                });

                document.getElementById('nextBtn').addEventListener('click', () => {
                    pageFlip.flipNext();
                });

            } catch (error) {
                console.error(error);
                loader.innerHTML = `<p class="text-red-400">Error loading PDF: ${error.message}</p>`;
            }
        }

        loadApp();
    </script>
</body>
</html>
