<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Wisuda - {{ $book->nama_buku }}</title>
    @if(isset($isPdf) && $isPdf)
        <style>
            /* Inject Built CSS */
            @php
                $cssFiles = glob(public_path('build/assets/app-*.css'));
                if (!empty($cssFiles)) {
                    echo file_get_contents($cssFiles[0]);
                }
            @endphp
            
            /* PDF Overrides */
            * { box-sizing: border-box !important; }
            html, body { margin: 0; padding: 0; width: 100%; height: 100%; background: #ffffff; }
            .a4-page, .sheet { 
                width: 210mm !important; 
                margin: 0 !important; 
                padding: 0 !important;
                background: #ffffff; 
                position: relative; 
                page-break-after: always; 
            }
            
            /* Fixed Cover/Front Matter Pages */
            .template-page {
                height: 297mm !important; 
                overflow: hidden; 
            }
            
            /* Flowing Data Pages */
            .listing-page {
                height: auto !important; 
                min-height: 297mm;
                overflow: visible; 
            }
            .content-padding { padding: 2.5cm !important; }
            .fixed-height { height: 297mm !important; overflow: hidden; }
            img { max-width: 100%; }
            .page-break { page-break-after: always; }
        </style>
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <style>
        @media print {
            @page { 
                margin: 0mm; 
                size: A4 portrait; 
            }
            body { 
                background: white !important; /* Ensure default bg is white */
                margin: 0 !important;
                padding: 0 !important;
                -webkit-print-color-adjust: exact !important; 
                print-color-adjust: exact !important;
                color-adjust: exact !important; 
            }
            .no-print { display: none !important; }
            
            /* Enforce A4 dimensions on print */
            .a4-page, .sheet { 
                width: 210mm;
                height: 297mm; 
                margin: 0 !important; 
                padding: 2cm !important; /* Fixed standard padding */
                background-color: white !important;
                box-shadow: none !important; 
                page-break-after: always !important; 
                break-after: page !important;
                overflow: hidden;
                position: relative;
                border: none !important;
            }
            .a4-page:last-child, .sheet:last-child {
                page-break-after: auto !important;
                break-after: auto !important;
            }

            /* Force background colors on specific elements if needed */
            .bg-emerald-700, .bg-emerald-800, .bg-gray-100, .bg-gray-50, .bg-amber-400 {
                -webkit-print-color-adjust: exact !important; 
                print-color-adjust: exact !important;
            }
        }
        
        @media screen {
            body {
                background-color: #525659;
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 2rem 0;
            }
            .a4-page, .sheet {
                width: 210mm;
                min-height: 297mm;
                margin: 0 auto 2rem auto !important; /* Forced visual separation */
                background: white;
                padding: 20mm;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                position: relative;
            }
            /* Visual page separator line */
            .a4-page::after, .sheet::after {
                content: "";
                position: absolute;
                bottom: -2rem;
                left: 0;
                width: 100%;
                height: 1px;
                background: transparent;
            }
        }

        /* Custom CSS from Template */
        @if($book->template && $book->template->custom_css)
            {!! $book->template->custom_css !!}
        @endif
    </style>
</head>
<body class="bg-gray-100 font-serif antialiased">

    @if(!isset($isPdf) || !$isPdf)
    <!-- Floating Print Button -->
    <div class="fixed bottom-8 right-8 no-print z-50">
        <button onclick="window.print()" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold py-3 px-6 rounded-full shadow-lg transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak / Simpan PDF
        </button>
    </div>
    @endif
    
    <!-- LOGIC: Dynamic ToC & Data Preparation -->
    @php
        // Group graduates by Faculty then Prodi
        $groupedGrads = $book->wisudawan->groupBy('fakultas')->map(function($facultyGrads) {
            return $facultyGrads->groupBy('prodi');
        });
        
        // Process Custom HTML to inject ToC & Pagination
        $usageHtml = $book->template->cover_html ?? '';
        
        if ($usageHtml) {
            // Split by sheet/a4-page tags. 
            // PREG_SPLIT_DELIM_CAPTURE: Returns the delimiters (tags) in the array.
            // We remove PREG_SPLIT_NO_EMPTY to keep indices predictable (Text, Tag, Text, Tag, Text).
            $pages = preg_split('/(<div[^>]*class=["\'](?:.*?\s)?(?:a4-page|sheet)(?:\s.*?)?["\'][^>]*>)/i', $usageHtml, -1, PREG_SPLIT_DELIM_CAPTURE);
            
            // Expected Structure:
            // [0] Pre-text (usually empty or comments)
            // [1] Tag 1
            // [2] Content 1
            // [3] Tag 2
            // [4] Content 2
            // ...
            
            $newHtml = $pages[0] ?? ''; // Keep the pre-text
            $pNum = 1; // 1=Cover, 2=ii, 3=iii ...
            
            // Calculate Page Numbers for Data
            // We assume Data Pages start with Page 1 (after the Front Matter)
            // Logic: Each Prodi has 1 Separator Page + N Listing Pages (10 grads per page)
            
            $tocData = [];
            $runningPage = 1; // This will be the page number for the first data page (after front matter)
            
            foreach($groupedGrads as $faculty => $prodis) {
                $facultyStart = $runningPage;
                $prodiList = [];
                
                foreach($prodis as $prodi => $grads) {
                    $prodiStart = $runningPage;
                    $count = $grads->count();
                    // Each listing page holds 4 graduates now (List View)
                    $listingPages = ceil($count / 4);
                    // Total pages for this prodi segment = 1 (Separator Page) + Listing Pages
                    $totalPages = 1 + ($listingPages > 0 ? $listingPages : 0); 
                    
                    $prodiList[] = [
                        'name' => $prodi,
                        'page' => $prodiStart
                    ];
                    
                    $runningPage += $totalPages;
                }
                
                $tocData[] = [
                    'faculty' => $faculty,
                    'page' => $facultyStart,
                    'prodis' => $prodiList
                ];
            }

            // Iterate starting from 1 (First Tag), taking 2 items at a time (Tag + Content)
            for ($i = 1; $i < count($pages); $i+=2) {
                // Determine Tag and Content
                $tag = $pages[$i];
                $content = $pages[$i+1] ?? '';
                
                // --- 1. TOC INJECTION ---
                if (stripos($content, 'DAFTAR ISI') !== false) {
                    
                     // A. Build Dynamic List HTML
                     $dynamicList = '';
                     $fC = 1;
                     foreach($tocData as $data) {
                        $dynamicList .= '<div class="toc-row" style="padding-left: 20px; margin-bottom: 4px;"><span class="toc-label">'. $fC . '. ' . $data['faculty'] .'</span><span class="toc-dots" style="border-bottom: 1px dotted #000; flex-grow: 1; margin: 0 5px;"></span><span class="toc-page">'. $data['page'] .'</span></div>';
                        
                        foreach($data['prodis'] as $pData) {
                              $dynamicList .= '<div class="toc-row" style="padding-left: 40px; margin-bottom: 2px; font-style: italic; font-size: 11pt;"><span class="toc-label">- '. $pData['name'] .'</span><span class="toc-dots" style="border-bottom: 1px dotted #000; flex-grow: 1; margin: 0 5px;"></span><span class="toc-page">'. $pData['page'] .'</span></div>';
                        }
                        $fC++;
                     }
                     
                     // B. CLEANUP STATIC ITEMS
                     // Strategy: Remove ANY <div class="toc-row"> that is NOT a Header ("Sambutan", "SK", "Daftar Lulusan").
                     // This clears old faculties AND prodis.
                     $content = preg_replace_callback('/<div class="toc-row".*?>.*?<\/div>/si', function($matches) {
                         $row = $matches[0];
                         // Keep Headers (Case Insensitive checks)
                         if (stripos($row, 'SAMBUTAN') !== false) return $row;
                         if (stripos($row, 'SK REKTOR') !== false) return $row;
                         if (stripos($row, 'DAFTAR LULUSAN') !== false) return $row; // The Header itself
                         
                         return ''; // Remove everything else (Static Items)
                     }, $content);

                     // C. INJECT DYNAMIC LIST
                     // Find the "DAFTAR LULUSAN" header again (it should be preserved)
                     $marker = 'DAFTAR LULUSAN FAKULTAS';
                     $markerPos = stripos($content, $marker);
                     
                     if ($markerPos !== false) {
                         // Find insertion point after this header's closing div
                         $closeDivPos = stripos($content, '</div>', $markerPos);
                         if ($closeDivPos !== false) {
                             $insertionPoint = $closeDivPos + 6; 
                             $content = substr_replace($content, $dynamicList, $insertionPoint, 0);
                         }
                     }
                }

                // --- 2. PAGINATION INJECTION ---
                // Only if NOT Cover (Page 1) and NOT the Separator Pages (which are green)
                // Actually user requested pagination on "html" template.
                if ($pNum > 1) {
                     $suffix = match($pNum) { 2=>'ii', 3=>'iii', 4=>'iv', default=>$pNum };
                     $paginationDiv = '<div class="page-number" style="position: absolute; bottom: 1.5cm; left: 0; width: 100%; text-align: center; font-weight: bold; font-family: serif; color:black;">'.$suffix.'</div>';
                     
                     $lastClose = strrpos($content, '</div>');
                     if ($lastClose !== false) {
                        $content = substr_replace($content, $paginationDiv . '</div>', $lastClose, 6);
                     } else {
                        $content .= $paginationDiv;
                     }
                }
                
                $newHtml .= $tag . $content;
                $pNum++;
            }
            $book->template->cover_html = $newHtml;
        }

        // Fix Images for PDF to use Local Paths
        if (isset($isPdf) && $isPdf) {
            $book->template->cover_html = str_replace(url('/'), public_path(), $book->template->cover_html ?? '');
            $book->template->cover_html = preg_replace('/src=["\']\/([^"\']+)["\']/', 'src="' . public_path('/$1') . '"', $book->template->cover_html);
        }
    @endphp

    <!-- COVER PAGE & FRONT MATTER -->
    @if($book->template && $book->template->cover_html)
        {!! $book->template->cover_html !!}
    @else
        <!-- Fallback Default Cover -->
        <div class="a4-page flex flex-col items-center justify-center text-center bg-white">
            <div class="border-4 border-emerald-800 p-2 w-full h-full flex flex-col items-center justify-between py-20">
                <div class="space-y-6">
                     <h1 class="text-4xl font-bold text-emerald-900 tracking-wide uppercase">Buku Wisuda</h1>
                     <h2 class="text-2xl font-semibold text-emerald-800">{{ $book->nama_buku }}</h2>
                </div>
            </div>
        </div>
    @endif
    
    <!-- DATA PAGES (Graduate Lists) -->
    <!-- We need to loop through groupedGrads instead of flat list to make separators -->
    
    @foreach($groupedGrads as $faculty => $prodis)
        @foreach($prodis as $prodi => $grads)
             <!-- SEPARATOR PAGE -->
             <div class="a4-page sheet font-serif center" style="display: flex; flex-direction: column; justify-content: center; background-color: #047857; color: white; page-break-before: always; page-break-after: always; position:relative;">
                <div style="border: 5px solid #fbbf24; padding: 2cm; margin: 1cm;">
                    <h3 style="font-size: 16pt; margin-bottom: 10px;">DAFTAR LULUSAN</h3>
                    <h1 class="bold" style="font-size: 28pt; margin: 0; line-height: 1.2; text-transform: uppercase;">{{ $faculty }}</h1>
                    <div style="width: 5cm; height: 2px; background-color: #fbbf24; margin: 1cm auto;"></div>
                    <h2 style="font-size: 18pt; text-transform: uppercase;">{{ $prodi }}</h2>
                </div>
            </div>
        
            <!-- LISTING PAGE -->
            <div class="a4-page sheet page-break {{ isset($isPdf) && $isPdf ? 'content-padding listing-page' : '' }}" style="page-break-before: always;">
                <h3 class="text-center font-bold text-xl uppercase mb-8 pb-4 border-b border-gray-300">
                    {{ $faculty }} <br> <span class="text-base text-gray-600">{{ $prodi }}</span>
                </h3>
                
                <div class="space-y-6">
                @foreach($grads as $grad)
                @if(isset($isPdf) && $isPdf)
                    <!-- PDF Layout: Table Based for Precision -->
                    <table style="width: 100%; page-break-inside: avoid; margin-bottom: 2rem;">
                        <tr>
                            <td style="width: 120px; vertical-align: top; padding-right: 20px;">
                                <div style="width: 112px; height: 144px; border: 1px solid #d1d5db; background-color: #f3f4f6;">
                                    @if($grad->foto)
                                        <img src="{{ public_path('storage/'.$grad->foto) }}" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                    @else
                                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 10px;">No Foto</div>
                                    @endif
                                </div>
                            </td>
                            <td style="vertical-align: top;">
                                <table style="width: 100%; font-family: serif; font-size: 11pt; line-height: 1.3;">
                                    <tr>
                                        <td style="width: 140px; vertical-align: top;">Nomor</td>
                                        <td style="width: 10px; vertical-align: top;">:</td>
                                        <td style="vertical-align: top; font-weight: bold;">{{ $grad->nomor ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">Nama</td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: top; font-weight: bold; text-transform: uppercase;">{{ $grad->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">NIM</td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">{{ $grad->nim }}</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">Tempat/Tgl Lahir</td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: top; text-transform: uppercase;">{{ $grad->ttl }}</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">Jenis Kelamin</td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">{{ $grad->jenis_kelamin }}</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">Program Studi</td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">{{ $grad->prodi }}</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">IPK / Yudisium</td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">{{ $grad->ipk }} / {{ $grad->ka_yudisium }}</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">Judul Skripsi/Tesis</td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: top; font-style: italic; text-align: justify;">{{ $grad->judul_thesis }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                @else
                    <div class="flex gap-6 break-inside-avoid" style="page-break-inside: avoid;">
                        <!-- Photo Column -->
                        <div class="w-32 flex-shrink-0">
                            <div class="w-28 h-36 bg-gray-100 overflow-hidden border border-gray-300 shadow-sm">
                                @if($grad->foto)
                                    <img src="{{ asset('storage/'.$grad->foto) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-50 flex-col">
                                        <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        <span class="text-[10px]">No Foto</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Details Column (Matching PDF Format) -->
                        <div class="flex-1 text-[11pt] font-serif leading-snug">
                            <table class="w-full">
                                <tr>
                                    <td class="w-40 align-top">Nomor</td>
                                    <td class="w-4 align-top">:</td>
                                    <td class="align-top font-bold">{{ $grad->nomor ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="align-top">Nama</td>
                                    <td class="align-top">:</td>
                                    <td class="align-top font-bold uppercase">{{ $grad->nama }}</td>
                                </tr>
                                <tr>
                                    <td class="align-top">NIM</td>
                                    <td class="align-top">:</td>
                                    <td class="align-top">{{ $grad->nim }}</td>
                                </tr>
                                <tr>
                                    <td class="align-top">Tempat/Tgl Lahir</td>
                                    <td class="align-top">:</td>
                                    <td class="align-top uppercase">{{ $grad->ttl }}</td>
                                </tr>
                                <tr>
                                    <td class="align-top">Jenis Kelamin</td>
                                    <td class="align-top">:</td>
                                    <td class="align-top">{{ $grad->jenis_kelamin }}</td>
                                </tr>
                                <tr>
                                    <td class="align-top">Program Studi</td>
                                    <td class="align-top">:</td>
                                    <td class="align-top">{{ $grad->prodi }}</td> <!-- PDFs list "S1 - ..." here usually, or plain name -->
                                </tr>
                                <tr>
                                    <td class="align-top">IPK / Yudisium</td>
                                    <td class="align-top">:</td>
                                    <td class="align-top">{{ $grad->ipk }} / {{ $grad->ka_yudisium }}</td>
                                </tr>
                                <tr>
                                    <td class="align-top">Judul Skripsi/Tesis</td>
                                    <td class="align-top">:</td>
                                    <td class="align-top italic text-justify leading-tight">
                                        {{ $grad->judul_thesis }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif
        
                    <!-- Manual Page Break Logic (approx 4 students per page to be safe with this detailed layout) -->
                    @if((!isset($isPdf) || !$isPdf) && $loop->iteration % 4 == 0 && !$loop->last)
                        </div></div><div class="a4-page sheet page-break"><div class="space-y-6 pt-8">
                    @endif
                @endforeach
                </div>
            </div>
        @endforeach
    @endforeach

</body>
</html>
