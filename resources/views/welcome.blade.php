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
          openTimeModal: false,
          deviceClock: '',
          deviceDate: '',
          deviceTz: '',
          serverClock: '{{ $systemTimes['server']['time'] ?? '' }}',
          serverFull: '{{ $systemTimes['server']['full'] ?? '' }}',
          serverTz: '{{ $systemTimes['server']['timezone'] ?? '' }}',
          dbClock: '{{ $systemTimes['database']['time'] ?? '' }}',
          dbFull: '{{ $systemTimes['database']['full'] ?? '' }}',
          dbTz: '{{ $systemTimes['database']['timezone'] ?? '' }}',
          dbDriver: '{{ $systemTimes['database']['driver'] ?? '' }}',
          dbHost: '{{ $systemTimes['database']['host'] ?? '' }}',
          dbDatabase: '{{ $systemTimes['database']['database'] ?? '' }}',
          dbStatus: '{{ $systemTimes['database']['status'] ?? '' }}',
          isLoadingTime: false,

          updateDeviceClock() {
              const now = new Date();
              const h = String(now.getHours()).padStart(2, '0');
              const m = String(now.getMinutes()).padStart(2, '0');
              const s = String(now.getSeconds()).padStart(2, '0');
              this.deviceClock = `${h}:${m}:${s}`;
              this.deviceDate = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
              this.deviceTz = Intl.DateTimeFormat().resolvedOptions().timeZone || 'Local';
          },

          async refreshSystemTime() {
              this.isLoadingTime = true;
              try {
                  const res = await fetch('{{ route('system.time') }}');
                  if (res.ok) {
                      const data = await res.json();
                      this.serverClock = data.server.time;
                      this.serverFull = data.server.full;
                      this.serverTz = data.server.timezone;
                      this.dbClock = data.database.time;
                      this.dbFull = data.database.full;
                      this.dbTz = data.database.timezone;
                      this.dbDriver = data.database.driver;
                      this.dbHost = data.database.host;
                      this.dbDatabase = data.database.database;
                      this.dbStatus = data.database.status;
                  }
              } catch (e) {
                  console.error('Gagal mengambil info waktu:', e);
              } finally {
                  this.isLoadingTime = false;
              }
          },

          openEditModal: false,
          isSavingEdit: false,
          editError: '',
          editSuccess: '',
          editForm: {
              id: null,
              no_bib: '',
              nama_lengkap: '',
              gender: '',
              status: 'finished',
              pace: '',
              gun_time: '',
              chip_time: '',
          },

          openEdit(p) {
              this.editForm = {
                  id: p.id,
                  no_bib: p.no_bib,
                  nama_lengkap: p.nama_lengkap || '',
                  gender: p.gender || '',
                  status: p.status || 'dns',
                  pace: p.pace || '',
                  gun_time: p.gun_time || '',
                  chip_time: p.chip_time || '',
              };
              this.editError = '';
              this.editSuccess = '';
              this.openEditModal = true;
          },

          async saveEdit() {
              this.isSavingEdit = true;
              this.editError = '';
              this.editSuccess = '';

              try {
                  const res = await fetch(`/participants/${this.editForm.id}`, {
                      method: 'PUT',
                      headers: {
                          'Content-Type': 'application/json',
                          'Accept': 'application/json',
                          'X-CSRF-TOKEN': '{{ csrf_token() }}'
                      },
                      body: JSON.stringify(this.editForm)
                  });

                  const data = await res.json();

                  if (res.ok && data.success) {
                      this.editSuccess = 'Data peserta berhasil diperbarui!';
                      setTimeout(() => {
                          window.location.reload();
                      }, 700);
                  } else {
                      this.editError = data.message || 'Gagal menyimpan perubahan data peserta.';
                  }
              } catch (e) {
                  this.editError = 'Terjadi kesalahan jaringan atau server saat menyimpan data.';
              } finally {
                  this.isSavingEdit = false;
              }
          }
      }"
      x-init="updateDeviceClock(); setInterval(() => updateDeviceClock(), 1000)">

    <!-- Top Navigation Header -->
    <header class="border-b border-slate-800/80 bg-slate-900/60 backdrop-blur-md sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div>
                    <h1 class="text-base sm:text-lg font-bold tracking-tight text-white flex items-center gap-2">
                        Cek Result Live
                    </h1>
                    <p class="text-xs text-slate-400 hidden sm:block">Portal Real-Time Live Timing & Hasil Peserta</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" 
                        @click="openTimeModal = true; refreshSystemTime()" 
                        class="group flex items-center gap-2 font-mono text-xs sm:text-sm font-semibold tracking-wider text-slate-200 hover:text-white px-3 py-1.5 rounded-lg border border-slate-800 hover:border-blue-500/50 bg-slate-800/40 hover:bg-slate-800/80 transition-all duration-150 shadow-sm cursor-pointer focus:outline-none"
                        title="Klik untuk melihat perbandingan Waktu Device, Database, & Server">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span x-text="deviceClock">--:--:--</span>
                    <svg class="w-3.5 h-3.5 text-slate-500 group-hover:text-blue-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
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
            </div>

            <div class="bg-slate-900/40 border border-slate-800/80 rounded-xl p-3.5 sm:p-4 hover:border-slate-700/70 transition">
                <div class="text-xs font-medium text-slate-400">DNS / DNF</div>
                <div class="text-2xl font-bold tracking-tight text-white mt-1">{{ $stats['dns'] }} / {{ $stats['dnf'] }}</div>
            </div>

            <div class="bg-slate-900/40 border border-slate-800/80 rounded-xl p-3.5 sm:p-4 hover:border-slate-700/70 transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-400">Sync Terakhir</span>
                    <span class="w-1.5 h-1.5 rounded-full {{ $lastSync ? 'bg-cyan-400' : 'bg-slate-600' }}"></span>
                </div>
                <div class="text-base sm:text-lg font-bold text-white mt-1 truncate" title="{{ $lastSync ? $lastSync->created_at->format('d M Y H:i:s') : 'Belum ada sync' }}">
                    {{ $lastSync ? $lastSync->created_at->diffForHumans() : 'Belum sync' }}
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
                        @if($search || $kategori || $hasSearched)
                            <a href="{{ route('cek-result.index') }}" class="absolute right-3 top-2.5 text-slate-400 hover:text-white text-xs py-0.5 px-1.5 bg-slate-800 rounded">Reset</a>
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
                        Cari
                    </button>
                </div>
            </form>
        </div>

        @if($hasSearched)
            <!-- Participants Results Table (Maksimal 10 Row) -->
            <div class="bg-slate-900/60 border border-slate-800/90 rounded-2xl overflow-hidden shadow-xl mb-8">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="bg-slate-900/90 text-xs uppercase tracking-wider text-slate-400 border-b border-slate-800">
                            <tr>
                                <th class="py-3.5 px-4 font-semibold">No BIB</th>
                                <th class="py-3.5 px-4 font-semibold">Nama Lengkap</th>
                                <th class="py-3.5 px-4 text-center font-semibold">Status</th>
                                <th class="py-3.5 px-4 text-right font-semibold">Chip Time</th>
                                <th class="py-3.5 px-4 text-center font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @forelse($participants as $p)
                                <tr class="hover:bg-slate-800/40 transition">
                                    <td class="py-3.5 px-4 font-mono font-bold text-white tracking-wide">
                                        {{ $p->no_bib }}
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-white">
                                        {{ $p->nama_lengkap }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        @if(strtolower($p->status ?? '') === 'finished')
                                            <span class="text-xs font-bold text-emerald-400">
                                                FINISHER
                                            </span>
                                        @elseif(strtolower($p->status ?? '') === 'dnf')
                                            <span class="text-xs font-bold text-rose-400">
                                                DNF
                                            </span>
                                        @else
                                            <span class="text-xs font-bold text-slate-400">
                                                {{ strtoupper($p->status ?? 'DNS') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-mono font-extrabold text-white text-base">
                                        {{ $p->chip_time ?: '--:--:--' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <button type="button"
                                                @click="openEdit({{ json_encode($p) }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-amber-500/15 text-amber-300 hover:bg-amber-500/25 border border-amber-500/30 transition shadow-sm cursor-pointer">
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-slate-500">
                                        <div class="text-base font-semibold text-slate-400">Belum ada data peserta yang ditemukan</div>
                                        <p class="text-xs text-slate-500 mt-1">Coba gunakan nomor BIB atau nama lainnya.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </main>

    <!-- Modal Edit Data Peserta -->
    <div x-show="openEditModal" x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
         @keydown.escape.window="if (!isSavingEdit) openEditModal = false">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-lg w-full p-6 shadow-2xl relative"
             @click.outside="if (!isSavingEdit) openEditModal = false">
            
            <!-- Header -->
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-800">
                <div>
                    <h3 class="text-base font-bold text-white tracking-wide flex items-center gap-2">
                        Edit Data Peserta
                    </h3>
                </div>
                <button type="button" 
                        @click="openEditModal = false" 
                        :disabled="isSavingEdit"
                        class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition disabled:opacity-50 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Feedback Alerts -->
            <template x-if="editSuccess">
                <div class="mb-4 p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span x-text="editSuccess"></span>
                </div>
            </template>

            <template x-if="editError">
                <div class="mb-4 p-3 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span x-text="editError"></span>
                </div>
            </template>

            <!-- Form Edit -->
            <form @submit.prevent="saveEdit()" class="space-y-4">
                <!-- Row: No BIB & Status -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1">Nomor BIB</label>
                        <input type="text" :value="editForm.no_bib" disabled
                               class="w-full bg-slate-950/70 border border-slate-800 rounded-xl px-3 py-2 text-sm font-mono font-bold text-slate-400 cursor-not-allowed select-none" />
                    </div>

                    <div>
                        <label for="edit_status" class="block text-xs font-medium text-slate-300 mb-1">Status Lomba <span class="text-rose-400">*</span></label>
                        <select id="edit_status" x-model="editForm.status" required
                                class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="finished">FINISHED (Finisher)</option>
                            <option value="dns">DNS (Did Not Start)</option>
                            <option value="dnf">DNF (Did Not Finish)</option>
                        </select>
                    </div>
                </div>

                <!-- Row: Nama Lengkap -->
                <div>
                    <label for="edit_nama" class="block text-xs font-medium text-slate-300 mb-1">Nama Lengkap <span class="text-rose-400">*</span></label>
                    <input type="text" id="edit_nama" x-model="editForm.nama_lengkap" required
                           class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <!-- Row: Gender & Pace -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="edit_gender" class="block text-xs font-medium text-slate-300 mb-1">Gender</label>
                        <select id="edit_gender" x-model="editForm.gender"
                                class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih / Tidak Ditentukan --</option>
                            <option value="M">M (Male / Laki-laki)</option>
                            <option value="F">F (Female / Perempuan)</option>
                        </select>
                    </div>

                    <div>
                        <label for="edit_pace" class="block text-xs font-medium text-slate-300 mb-1">Average Pace</label>
                        <input type="text" id="edit_pace" x-model="editForm.pace" placeholder="Contoh: 05:30"
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm font-mono text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                </div>

                <!-- Row: Gun Time & Chip Time -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="edit_gun_time" class="block text-xs font-medium text-slate-300 mb-1">Gun Time</label>
                        <input type="text" id="edit_gun_time" x-model="editForm.gun_time" placeholder="HH:MM:SS"
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm font-mono text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <div>
                        <label for="edit_chip_time" class="block text-xs font-medium text-slate-300 mb-1">Chip Time</label>
                        <input type="text" id="edit_chip_time" x-model="editForm.chip_time" placeholder="HH:MM:SS"
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm font-mono font-bold text-emerald-400 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-800">
                    <button type="button" 
                            @click="openEditModal = false" 
                            :disabled="isSavingEdit"
                            class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition disabled:opacity-50 cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" 
                            :disabled="isSavingEdit"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition disabled:opacity-50 shadow-lg shadow-blue-600/30 cursor-pointer">
                        <svg x-show="isSavingEdit" class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span x-text="isSavingEdit ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- Modal Sinkronisasi & Perbandingan Waktu Sistem (Minimalis) -->
    <div x-show="openTimeModal" x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
         @keydown.escape.window="openTimeModal = false">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-xs sm:max-w-sm w-full p-5 shadow-2xl relative"
             @click.outside="openTimeModal = false">
            
            <!-- Header -->
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-800">
                <h3 class="text-sm font-bold text-white tracking-wide">Waktu Sistem</h3>
                <button @click="openTimeModal = false" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- List Waktu: Device, Database, Server -->
            <div class="space-y-2">
                <div class="flex items-center justify-between px-3 py-2.5 rounded-xl bg-slate-950/60 border border-slate-800/80">
                    <span class="text-xs text-slate-400">Waktu Device</span>
                    <span class="font-mono text-sm sm:text-base font-bold text-emerald-400 tracking-wider" x-text="deviceClock">--:--:--</span>
                </div>

                <div class="flex items-center justify-between px-3 py-2.5 rounded-xl bg-slate-950/60 border border-slate-800/80">
                    <span class="text-xs text-slate-400">Waktu Database</span>
                    <span class="font-mono text-sm sm:text-base font-bold text-amber-400 tracking-wider" x-text="dbClock">--:--:--</span>
                </div>

                <div class="flex items-center justify-between px-3 py-2.5 rounded-xl bg-slate-950/60 border border-slate-800/80">
                    <span class="text-xs text-slate-400">Waktu Server (PHP)</span>
                    <span class="font-mono text-sm sm:text-base font-bold text-blue-400 tracking-wider" x-text="serverClock">--:--:--</span>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-800">
                <button type="button" 
                        @click="refreshSystemTime()" 
                        :disabled="isLoadingTime"
                        class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-slate-200 transition disabled:opacity-50 cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-blue-400" :class="isLoadingTime ? 'animate-spin' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span x-text="isLoadingTime ? 'Memuat...' : 'Perbarui'"></span>
                </button>

                <button type="button" 
                        @click="openTimeModal = false" 
                        class="px-4 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-medium transition cursor-pointer">
                    Tutup
                </button>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="border-t border-slate-800/80 py-6 text-center text-xs text-slate-500 mt-auto">
        &copy; {{ date('Y') }} Cek Result Live Timing
    </footer>

</body>
</html>
