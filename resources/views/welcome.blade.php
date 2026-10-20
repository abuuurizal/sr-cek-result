<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Cek Result Live') }} - Live Timing Portal</title>

    <!-- Prevent search engines from indexing or caching this portal -->
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
    <meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|jet-brains-mono:600,700,800&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS CDN for instant styling without local compilation -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .cyber-grid {
            background-size: 30px 30px;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
        }
    </style>
</head>

<body class="min-h-full cyber-grid flex flex-col antialiased selection:bg-blue-600 selection:text-white"
      x-data="{
          selectedParticipant: null,
          openModal: false,
          showCard(p) {
              this.selectedParticipant = p;
              this.openModal = true;
          }
      }">

    <!-- Top Navigation Header -->
    <header class="border-b border-slate-800/80 bg-slate-900/60 backdrop-blur-md sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-cyan-400 flex items-center justify-center font-black text-white shadow-lg shadow-blue-500/20">
                    CR
                </div>
                <div>
                    <h1 class="text-base sm:text-lg font-bold tracking-tight text-white flex items-center gap-2">
                        Cek Result Live
                        <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            VPS Cloud
                        </span>
                    </h1>
                    <p class="text-xs text-slate-400 hidden sm:block">Portal Real-Time Live Timing & Hasil Peserta</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('api.sync.status') }}" target="_blank" class="text-xs text-slate-400 hover:text-slate-200 px-3 py-1.5 rounded-lg border border-slate-800 hover:border-slate-700 bg-slate-800/40 transition">
                    Status API
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">

        <!-- Stats Overview Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-8">
            <div class="bg-slate-900/40 border border-slate-800/80 rounded-xl p-3.5 sm:p-4 hover:border-slate-700/70 transition">
                <div class="text-xs font-medium text-slate-400">Total Peserta</div>
                <div class="text-2xl font-bold tracking-tight text-white mt-1">{{ number_format($stats['total']) }}</div>
                <div class="text-[11px] text-slate-500 mt-0.5">Terdaftar di sistem</div>
            </div>

            <div class="bg-slate-900/40 border border-slate-800/80 rounded-xl p-3.5 sm:p-4 hover:border-slate-700/70 transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-400">Finisher</span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-medium text-emerald-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        {{ $stats['total'] > 0 ? round(($stats['finished'] / $stats['total']) * 100) : 0 }}%
                    </span>
                </div>
                <div class="text-2xl font-bold tracking-tight text-white mt-1">{{ number_format($stats['finished']) }}</div>
                <div class="text-[11px] text-slate-500 mt-0.5">Melewati finish</div>
            </div>

            <div class="bg-slate-900/40 border border-slate-800/80 rounded-xl p-3.5 sm:p-4 hover:border-slate-700/70 transition">
                <div class="text-xs font-medium text-slate-400">DNS / DNF</div>
                <div class="text-2xl font-bold tracking-tight text-white mt-1">{{ number_format($stats['dns'] + $stats['dnf']) }}</div>
                <div class="text-[11px] text-slate-500 mt-0.5">{{ $stats['dns'] }} DNS &middot; {{ $stats['dnf'] }} DNF</div>
            </div>

            <div class="bg-slate-900/40 border border-slate-800/80 rounded-xl p-3.5 sm:p-4 hover:border-slate-700/70 transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-400">Sync Terakhir</span>
                    <span class="w-1.5 h-1.5 rounded-full {{ $lastSync ? 'bg-cyan-400' : 'bg-slate-600' }}"></span>
                </div>
                <div class="text-base sm:text-lg font-bold text-white mt-1 truncate" title="{{ $lastSync ? $lastSync->created_at->format('d M Y H:i:s') : 'Belum ada sync' }}">
                    {{ $lastSync ? $lastSync->created_at->diffForHumans() : 'Belum sync' }}
                </div>
                <div class="text-[11px] text-slate-500 mt-0.5 truncate">
                    {{ $lastSync ? $lastSync->records_count . ' data batch' : 'Standby menerima push' }}
                </div>
            </div>
        </div>

        <!-- Search & Filter Controls -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-4 sm:p-6 mb-8 backdrop-blur shadow-lg">
            <form method="GET" action="{{ route('cek-result.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                <div class="sm:col-span-6">
                    <label for="q" class="block text-xs font-medium text-slate-300 mb-1.5">Nomor BIB atau Nama Peserta</label>
                    <div class="relative">
                        <input type="text" name="q" id="q" value="{{ $search }}"
                               placeholder="Contoh: 1001 atau Budi Santoso"
                               class="w-full bg-slate-950 border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-medium" />
                        @if($search)
                            <a href="{{ route('cek-result.index', ['kategori' => $kategori]) }}" class="absolute right-3 top-2.5 text-slate-400 hover:text-white text-xs py-0.5 px-1.5 bg-slate-800 rounded">Reset</a>
                        @endif
                    </div>
                </div>

                <div class="sm:col-span-4">
                    <label for="kategori" class="block text-xs font-medium text-slate-300 mb-1.5">Kategori Lomba</label>
                    <select name="kategori" id="kategori" class="w-full bg-slate-950 border border-slate-700/80 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $kategori == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2.5 px-4 rounded-xl text-sm transition shadow-lg shadow-blue-600/30">
                        Cari Hasil
                    </button>
                </div>
            </form>
        </div>

        <!-- Participants Results Table -->
        <div class="bg-slate-900/60 border border-slate-800/90 rounded-2xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-900/90 text-xs uppercase tracking-wider text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="py-3.5 px-4">BIB</th>
                            <th class="py-3.5 px-4">Nama Peserta</th>
                            <th class="py-3.5 px-4 hidden sm:table-cell">Kategori</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4 text-right">Chip Time</th>
                            <th class="py-3.5 px-4 text-right hidden md:table-cell">Pace</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($participants as $p)
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="py-3.5 px-4 font-mono font-bold text-white tracking-wide">
                                    #{{ $p->no_bib }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-white">{{ $p->nama_lengkap }}</div>
                                    <div class="text-xs text-slate-400 sm:hidden mt-0.5">
                                        {{ $p->kategori ?? '-' }} &bull; {{ $p->gender ?? '-' }}
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 hidden sm:table-cell text-slate-300">
                                    <span class="px-2.5 py-1 rounded-md bg-slate-800 text-xs font-medium border border-slate-700/60">
                                        {{ $p->kategori ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($p->status === 'finished')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                                            FINISHER
                                        </span>
                                    @elseif($p->status === 'dnf')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-500/15 text-rose-400 border border-rose-500/30">
                                            DNF
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-700/30 text-slate-400 border border-slate-700/50">
                                            DNS
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right font-mono font-extrabold text-white text-base">
                                    {{ $p->chip_time ?: '--:--:--' }}
                                </td>
                                <td class="py-3.5 px-4 text-right font-mono text-xs text-slate-400 hidden md:table-cell">
                                    {{ $p->pace ? $p->pace . ' /km' : '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <button type="button"
                                            @click="showCard({{ json_encode($p) }})"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 transition">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-500">
                                    <div class="text-base font-semibold text-slate-400">Belum ada data peserta yang ditemukan</div>
                                    <p class="text-xs text-slate-500 mt-1">Gunakan kotak pencarian di atas atau tunggu sinkronisasi data dari venue.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bar -->
            @if($participants->hasPages())
                <div class="p-4 border-t border-slate-800 bg-slate-900/90">
                    {{ $participants->links() }}
                </div>
            @endif
        </div>
    </main>

    <!-- Modal E-Result Finisher Card -->
    <div x-show="openModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
         @keydown.escape.window="openModal = false">
        <div class="bg-slate-900 border border-slate-700/80 rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl relative text-center"
             @click.outside="openModal = false">
            
            <button @click="openModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-white p-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <template x-if="selectedParticipant">
                <div>
                    <!-- Badge Header -->
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-4 border"
                         :class="selectedParticipant.status === 'finished' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-slate-800 text-slate-400 border-slate-700'">
                        <span x-text="selectedParticipant.status.toUpperCase()"></span>
                    </div>

                    <!-- BIB & Name -->
                    <div class="text-4xl font-black font-mono tracking-wider text-blue-400 mb-1" x-text="'#' + selectedParticipant.no_bib"></div>
                    <div class="text-xl font-bold text-white mb-2" x-text="selectedParticipant.nama_lengkap"></div>
                    
                    <div class="flex items-center justify-center gap-2 text-xs text-slate-400 mb-6">
                        <span class="px-2 py-0.5 rounded bg-slate-800" x-text="selectedParticipant.kategori || 'Event Run'"></span>
                        <span class="px-2 py-0.5 rounded bg-slate-800" x-text="selectedParticipant.gender || '-'"></span>
                    </div>

                    <!-- Timing Highlight Box -->
                    <div class="bg-slate-950/80 border border-slate-800 rounded-2xl p-5 mb-6">
                        <div class="text-xs uppercase tracking-wider font-semibold text-slate-400 mb-1">Official Chip Time</div>
                        <div class="text-3xl sm:text-4xl font-extrabold font-mono text-emerald-400" x-text="selectedParticipant.chip_time || '--:--:--'"></div>
                        
                        <div class="grid grid-cols-2 gap-4 mt-4 pt-4 border-t border-slate-800 text-left">
                            <div>
                                <div class="text-[10px] uppercase text-slate-500 font-semibold">Gun Time</div>
                                <div class="text-sm font-mono font-bold text-slate-200 mt-0.5" x-text="selectedParticipant.gun_time || '--:--:--'"></div>
                            </div>
                            <div>
                                <div class="text-[10px] uppercase text-slate-500 font-semibold">Avg Pace</div>
                                <div class="text-sm font-mono font-bold text-slate-200 mt-0.5" x-text="(selectedParticipant.pace ? selectedParticipant.pace + ' /km' : '-')"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 justify-center">
                        <button type="button" onclick="window.print()" class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs transition shadow-lg shadow-blue-600/30">
                            Cetak Kartu Hasil
                        </button>
                        <button type="button" @click="openModal = false" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs transition">
                            Tutup
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Footer -->
    <footer class="border-t border-slate-800/80 py-6 text-center text-xs text-slate-500 mt-auto">
        &copy; {{ date('Y') }} Cek Result Live Timing &bull; Powered by VPS Cloud Receiver
    </footer>

</body>
</html>
