<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Setting Time perKategori &bull; Timing Engine</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|jet-brains-mono:600,700,800&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
        .font-mono-sport { font-family: 'JetBrains Mono', monospace; }
        .cyber-grid {
            background-size: 32px 32px;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
        }
    </style>
</head>

<body class="min-h-full cyber-grid flex flex-col antialiased selection:bg-amber-600 selection:text-white">

    <!-- Top Navigation Header -->
    <header class="border-b border-slate-800/80 bg-slate-900/60 backdrop-blur-md sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3 sm:gap-4">
                <a href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-semibold transition border border-slate-700/60 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <span class="text-slate-600 hidden sm:inline">/</span>
                <div>
                    <h1 class="text-base sm:text-lg font-bold tracking-tight text-white flex items-center gap-2">
                        Setting Time perKategori
                    </h1>
                    <p class="text-xs text-slate-400 hidden md:block">Atur Gun Time (waktu tembakan) dan batas Fastest per kategori lomba</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full space-y-4">

        <!-- Flash Notification: Success -->
        @if(session('status'))
            <div class="flash-alert p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm flex items-center justify-between shadow-lg transition-all duration-200">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
                <button type="button" onclick="this.closest('.flash-alert').remove()" class="text-emerald-400 hover:text-emerald-200 hover:bg-emerald-500/20 p-1.5 rounded-lg transition-colors focus:outline-none ml-4 flex-shrink-0" title="Tutup">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Flash Notification: Error -->
        @if(session('error'))
            <div class="flash-alert p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-sm flex items-center justify-between shadow-lg transition-all duration-200">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.closest('.flash-alert').remove()" class="text-rose-400 hover:text-rose-200 hover:bg-rose-500/20 p-1.5 rounded-lg transition-colors focus:outline-none ml-4 flex-shrink-0" title="Tutup">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="flash-alert p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-sm space-y-1 shadow-lg">
                <div class="flex items-center gap-2 font-semibold text-rose-400 mb-1">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span>Terjadi kesalahan pengisian form:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-0.5 pl-2 text-rose-200">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 sm:p-6 shadow-lg backdrop-blur space-y-4">
            
            <!-- Header bar: Title & Search -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 pb-3 border-b border-slate-800/80">
                <div>
                    <h2 class="text-sm font-semibold text-white">Daftar Waktu Kategori</h2>
                    <p class="text-xs text-slate-400">Atur Gun Time (waktu start) dan batas Fastest per kategori lomba</p>
                </div>

                <div class="w-full sm:w-48">
                    <input type="text" id="tableSearch" placeholder="Cari kategori..." class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-1.5 text-xs text-slate-200 placeholder-slate-500 focus:outline-none focus:border-amber-500 transition">
                </div>
            </div>

            <!-- Input / Edit Bar -->
            <form id="categoryTimeForm" method="POST" action="{{ route('category-times.store') }}" class="flex flex-col sm:flex-row gap-2.5 items-stretch sm:items-center">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="flex-1">
                    <label for="inputKategori" class="sr-only">Kategori</label>
                    <input type="text" id="inputKategori" name="kategori" value="{{ old('kategori') }}" placeholder="Nama Kategori (mis. 10K)" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-1.5 text-xs text-slate-200 placeholder-slate-500 focus:outline-none focus:border-amber-500 transition">
                </div>

                <div class="w-full sm:w-56">
                    <label for="inputGunTime" class="sr-only">Gun Time</label>
                    <input type="text" id="inputGunTime" name="gun_time" value="{{ old('gun_time') }}" placeholder="Gun Time (2026-10-20 11:46:26)" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-1.5 text-xs text-slate-200 placeholder-slate-500 font-mono-sport focus:outline-none focus:border-amber-500 transition">
                </div>

                <div class="w-full sm:w-36">
                    <label for="inputFastest" class="sr-only">Fastest</label>
                    <input type="text" id="inputFastest" name="fastest" value="{{ old('fastest') }}" placeholder="Fastest (00:29:00)" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-1.5 text-xs text-slate-200 placeholder-slate-500 font-mono-sport focus:outline-none focus:border-amber-500 transition">
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" id="btnSubmit" class="px-4 py-1.5 rounded-lg bg-amber-600 hover:bg-amber-500 text-white text-xs font-semibold transition whitespace-nowrap shadow-sm shadow-amber-600/30">
                        + Tambah
                    </button>
                    <button type="button" id="btnCancel" onclick="cancelEdit()" class="hidden px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white text-xs transition">
                        Batal
                    </button>
                </div>
            </form>

            <!-- Table -->
            <div class="overflow-x-auto rounded-xl border border-slate-800">
                <table class="w-full text-left text-xs sm:text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-950 text-slate-400 border-b border-slate-800 text-[11px] uppercase tracking-wider font-semibold">
                            <th class="py-2.5 px-4 w-12 text-center">No</th>
                            <th class="py-2.5 px-4">Kategori</th>
                            <th class="py-2.5 px-4 font-mono">Gun Time (Timestamp)</th>
                            <th class="py-2.5 px-4 font-mono">Fastest</th>
                            <th class="py-2.5 px-4 text-right w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        @forelse($categories as $index => $item)
                            <tr class="category-row hover:bg-slate-800/30 transition">
                                <td class="py-3 px-4 text-center text-slate-500 font-mono-sport">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 font-medium text-white whitespace-nowrap cat-name">{{ $item->kategori }}</td>
                                <td class="py-3 px-4 font-mono-sport text-slate-300 whitespace-nowrap">
                                    {{ $item->gun_time ? \Carbon\Carbon::parse($item->gun_time)->format('Y-m-d H:i:s') : '-' }}
                                </td>
                                <td class="py-3 px-4 font-mono-sport text-slate-300 whitespace-nowrap">{{ $item->fastest }}</td>
                                <td class="py-3 px-4 text-right whitespace-nowrap space-x-2">
                                    <button type="button" onclick="editCategory({{ $item->id }}, '{{ addslashes($item->kategori) }}', '{{ $item->gun_time ? \Carbon\Carbon::parse($item->gun_time)->format('Y-m-d H:i:s') : '' }}', '{{ $item->fastest }}')" class="text-xs text-amber-400 hover:text-amber-300 font-medium transition cursor-pointer">
                                        Edit
                                    </button>
                                    <form action="{{ route('category-times.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori \'{{ addslashes($item->kategori) }}\'?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-slate-500 hover:text-rose-400 font-medium transition cursor-pointer">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 px-4 text-center text-slate-500">
                                    Belum ada kategori terdaftar di database.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-800 py-6 text-center text-xs text-slate-500 mt-auto">
        &copy; {{ date('Y') }} Timing Engine &bull; Connected to Database
    </footer>

    <!-- Client-side Interactive Helpers -->
    <script>
        const searchInput = document.getElementById('tableSearch');
        const rows = document.querySelectorAll('.category-row');
        const categoryForm = document.getElementById('categoryTimeForm');
        const formMethod = document.getElementById('formMethod');
        const inputKategori = document.getElementById('inputKategori');
        const inputGunTime = document.getElementById('inputGunTime');
        const inputFastest = document.getElementById('inputFastest');
        const btnSubmit = document.getElementById('btnSubmit');
        const btnCancel = document.getElementById('btnCancel');

        const baseUrl = "{{ url('/category-times') }}";
        const storeUrl = "{{ route('category-times.store') }}";

        // Live search filter across rows
        searchInput?.addEventListener('input', () => {
            const query = (searchInput.value || '').toLowerCase();
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });

        // Set form to edit mode
        function editCategory(id, kategori, gunTime, fastest) {
            categoryForm.action = baseUrl + '/' + id;
            formMethod.value = 'PUT';

            inputKategori.value = kategori;
            inputGunTime.value = gunTime;
            inputFastest.value = fastest;

            btnSubmit.textContent = 'Simpan';
            btnCancel.classList.remove('hidden');

            inputKategori.focus();
            categoryForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        // Cancel edit mode and reset to store
        function cancelEdit() {
            categoryForm.action = storeUrl;
            formMethod.value = 'POST';

            inputKategori.value = '';
            inputGunTime.value = '';
            inputFastest.value = '';

            btnSubmit.textContent = '+ Tambah';
            btnCancel.classList.add('hidden');
        }
    </script>

</body>
</html>
