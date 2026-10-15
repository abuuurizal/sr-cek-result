<div>
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <x-lucide-calendar class="w-7 h-7 text-blue-600 dark:text-blue-400" />
                {{ __('Events & Participants') }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ __('Manage race results, bib numbers, timing chips, and finish statuses.') }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.events.create') }}" wire:navigate
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-medium rounded-xl transition-all shadow-sm hover:shadow">
                <x-lucide-plus class="w-4 h-4" />
                {{ __('Add Participant') }}
            </a>
        </div>
    </div>

    {{-- Flash Messages --}}
    <x-flash-messages />

    {{-- KPI / Statistics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Participants --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Total Registered') }}</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1 tabular-nums">{{ number_format($stats['total']) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                <x-lucide-users class="w-6 h-6" />
            </div>
        </div>

        {{-- Finished --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">{{ __('Finished') }}</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1 tabular-nums">{{ number_format($stats['finished']) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                <x-lucide-trophy class="w-6 h-6" />
            </div>
        </div>

        {{-- DNS --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-amber-600 dark:text-amber-400 uppercase tracking-wider">{{ __('DNS (Did Not Start)') }}</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1 tabular-nums">{{ number_format($stats['dns']) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                <x-lucide-clock class="w-6 h-6" />
            </div>
        </div>

        {{-- DNF --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-red-600 dark:text-red-400 uppercase tracking-wider">{{ __('DNF (Did Not Finish)') }}</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1 tabular-nums">{{ number_format($stats['dnf']) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center">
                <x-lucide-x-circle class="w-6 h-6" />
            </div>
        </div>
    </div>

    {{-- Toolbar / Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
            {{-- Search --}}
            <div class="relative lg:col-span-4">
                <x-lucide-search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="{{ __('Search BIB, name, chip, category...') }}"
                    class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all placeholder-gray-400" />
            </div>

            {{-- Status Filter --}}
            <div class="lg:col-span-2">
                <select wire:model.live="filterStatus"
                    class="w-full px-3 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="finished">{{ __('Finished') }}</option>
                    <option value="dns">{{ __('DNS') }}</option>
                    <option value="dnf">{{ __('DNF') }}</option>
                </select>
            </div>

            {{-- Gender Filter --}}
            <div class="lg:col-span-2">
                <select wire:model.live="filterGender"
                    class="w-full px-3 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    <option value="">{{ __('All Gender') }}</option>
                    @foreach ($genders as $gender)
                        <option value="{{ $gender }}">{{ $gender }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Category Filter --}}
            <div class="lg:col-span-2">
                <select wire:model.live="filterKategori"
                    class="w-full px-3 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    <option value="">{{ __('All Categories') }}</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Per Page --}}
            <div class="lg:col-span-1">
                <select wire:model.live="perPage"
                    class="w-full px-3 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>

            {{-- Reset Filters --}}
            <div class="lg:col-span-1 flex items-center justify-end">
                @if ($search !== '' || $filterStatus !== '' || $filterGender !== '' || $filterKategori !== '')
                    <button wire:click="resetFilters" title="{{ __('Reset filters') }}"
                        class="w-full py-2.5 px-3 inline-flex items-center justify-center gap-1.5 text-xs font-medium text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 bg-gray-100 dark:bg-gray-700 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-xl transition-colors">
                        <x-lucide-rotate-ccw class="w-3.5 h-3.5" />
                        <span>{{ __('Reset') }}</span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Bulk Actions Bar --}}
    @if ($selectedCount > 0)
        <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="text-sm font-semibold text-blue-700 dark:text-blue-300">
                    {{ $selectedCount }} {{ __('participant(s) selected') }}
                </span>
                @if (!$selectAll && $selectedCount < $participants->total())
                    <button wire:click="selectAllParticipants"
                        class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-medium">
                        {{ __('Select all') }} {{ number_format($participants->total()) }}
                    </button>
                @endif
                <button wire:click="deselectAll" class="text-sm text-gray-500 dark:text-gray-400 hover:underline">
                    {{ __('Deselect all') }}
                </button>
            </div>

            <div class="flex items-center gap-2">
                {{-- Quick Status Change --}}
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Set Status:') }}</span>
                <button wire:click="bulkSetStatus('finished')"
                    class="px-2.5 py-1 text-xs font-medium bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors">
                    Finished
                </button>
                <button wire:click="bulkSetStatus('dns')"
                    class="px-2.5 py-1 text-xs font-medium bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition-colors">
                    DNS
                </button>
                <button wire:click="bulkSetStatus('dnf')"
                    class="px-2.5 py-1 text-xs font-medium bg-rose-600 hover:bg-rose-700 text-white rounded-lg transition-colors">
                    DNF
                </button>

                <div class="h-4 w-px bg-gray-300 dark:bg-gray-600 mx-1"></div>

                <button wire:click="bulkDelete"
                    wire:confirm="{{ __('Are you sure you want to delete the selected participants?') }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg transition-colors shadow-sm">
                    <x-lucide-trash-2 class="w-3.5 h-3.5" />
                    {{ __('Delete Selected') }}
                </button>
            </div>
        </div>
    @endif

    {{-- Main Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" wire:loading.class="opacity-60">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <th class="w-10 px-3 py-3">
                            <input type="checkbox" wire:model.live="selectPage"
                                class="w-4 h-4 text-blue-600 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 focus:ring-2" />
                        </th>

                        {{-- Sortable Columns --}}
                        <th class="text-left px-4 py-3">
                            <button wire:click="sortBy('no_bib')" class="flex items-center gap-1 font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-xs hover:text-gray-900 dark:hover:text-white transition-colors">
                                {{ __('BIB') }}
                                @if ($sortField === 'no_bib')
                                    <x-dynamic-component :component="'lucide-' . ($sortDirection === 'asc' ? 'chevron-up' : 'chevron-down')" class="w-3.5 h-3.5" />
                                @else
                                    <x-lucide-chevrons-up-down class="w-3.5 h-3.5 opacity-30" />
                                @endif
                            </button>
                        </th>

                        <th class="text-left px-4 py-3">
                            <button wire:click="sortBy('nama_lengkap')" class="flex items-center gap-1 font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-xs hover:text-gray-900 dark:hover:text-white transition-colors">
                                {{ __('Nama Lengkap') }}
                                @if ($sortField === 'nama_lengkap')
                                    <x-dynamic-component :component="'lucide-' . ($sortDirection === 'asc' ? 'chevron-up' : 'chevron-down')" class="w-3.5 h-3.5" />
                                @else
                                    <x-lucide-chevrons-up-down class="w-3.5 h-3.5 opacity-30" />
                                @endif
                            </button>
                        </th>

                        <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-xs">
                            {{ __('Gender') }}
                        </th>

                        <th class="text-left px-4 py-3">
                            <button wire:click="sortBy('kategori')" class="flex items-center gap-1 font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-xs hover:text-gray-900 dark:hover:text-white transition-colors">
                                {{ __('Kategori / Sub') }}
                                @if ($sortField === 'kategori')
                                    <x-dynamic-component :component="'lucide-' . ($sortDirection === 'asc' ? 'chevron-up' : 'chevron-down')" class="w-3.5 h-3.5" />
                                @else
                                    <x-lucide-chevrons-up-down class="w-3.5 h-3.5 opacity-30" />
                                @endif
                            </button>
                        </th>

                        <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-xs">
                            {{ __('Chip Code') }}
                        </th>

                        <th class="text-left px-4 py-3">
                            <button wire:click="sortBy('status')" class="flex items-center gap-1 font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-xs hover:text-gray-900 dark:hover:text-white transition-colors">
                                {{ __('Status') }}
                                @if ($sortField === 'status')
                                    <x-dynamic-component :component="'lucide-' . ($sortDirection === 'asc' ? 'chevron-up' : 'chevron-down')" class="w-3.5 h-3.5" />
                                @else
                                    <x-lucide-chevrons-up-down class="w-3.5 h-3.5 opacity-30" />
                                @endif
                            </button>
                        </th>

                        <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-xs">
                            {{ __('Gun Time') }}
                        </th>

                        <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-xs">
                            {{ __('Chip Time') }}
                        </th>

                        <th class="text-left px-4 py-3 font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-xs">
                            {{ __('Pace') }}
                        </th>

                        <th class="text-right px-4 py-3 font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-xs">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($participants as $participant)
                        <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/30 transition-colors {{ in_array((string) $participant->id, $selected) ? 'bg-blue-50/50 dark:bg-blue-900/10' : '' }}"
                            wire:key="participant-{{ $participant->id }}">
                            {{-- Checkbox --}}
                            <td class="px-3 py-3">
                                <input type="checkbox" wire:model.live="selected" value="{{ $participant->id }}"
                                    class="w-4 h-4 text-blue-600 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 focus:ring-2" />
                            </td>

                            {{-- BIB --}}
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold font-mono bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-600">
                                    {{ $participant->no_bib ?: '—' }}
                                </span>
                            </td>

                            {{-- Nama Lengkap --}}
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ $participant->nama_lengkap }}
                                </div>
                            </td>

                            {{-- Gender --}}
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300 text-xs">
                                @if (strtolower($participant->gender ?? '') === 'male' || strtolower($participant->gender ?? '') === 'm' || strtolower($participant->gender ?? '') === 'laki-laki')
                                    <span class="inline-flex items-center gap-1 text-blue-600 dark:text-blue-400 font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        {{ $participant->gender }}
                                    </span>
                                @elseif (strtolower($participant->gender ?? '') === 'female' || strtolower($participant->gender ?? '') === 'f' || strtolower($participant->gender ?? '') === 'perempuan')
                                    <span class="inline-flex items-center gap-1 text-pink-600 dark:text-pink-400 font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-pink-500"></span>
                                        {{ $participant->gender }}
                                    </span>
                                @else
                                    <span class="text-gray-400">{{ $participant->gender ?: '—' }}</span>
                                @endif
                            </td>

                            {{-- Kategori & Sub --}}
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-0.5">
                                    <span class="font-medium text-gray-900 dark:text-gray-100 text-xs">
                                        {{ $participant->kategori ?: '—' }}
                                    </span>
                                    @if ($participant->sub_kategori)
                                        <span class="text-[11px] text-gray-500 dark:text-gray-400">
                                            {{ $participant->sub_kategori }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Chip Code --}}
                            <td class="px-4 py-3 font-mono text-xs text-gray-500 dark:text-gray-400">
                                {{ $participant->chip_code ?: '—' }}
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-3">
                                @if ($participant->status === 'finished')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        <x-lucide-check-circle class="w-3.5 h-3.5" />
                                        Finished
                                    </span>
                                @elseif ($participant->status === 'dns')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                        <x-lucide-clock class="w-3.5 h-3.5" />
                                        DNS
                                    </span>
                                @elseif ($participant->status === 'dnf')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                        <x-lucide-x-circle class="w-3.5 h-3.5" />
                                        DNF
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">{{ $participant->status }}</span>
                                @endif
                            </td>

                            {{-- Gun Time --}}
                            <td class="px-4 py-3 font-mono text-xs text-gray-700 dark:text-gray-300 tabular-nums">
                                {{ $participant->gun_time ?: '—' }}
                            </td>

                            {{-- Chip Time --}}
                            <td class="px-4 py-3 font-mono text-xs text-gray-700 dark:text-gray-300 tabular-nums font-semibold">
                                {{ $participant->chip_time ?: '—' }}
                            </td>

                            {{-- Pace --}}
                            <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">
                                {{ $participant->pace ?: '—' }}
                            </td>

                            {{-- Actions --}}
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.events.edit', $participant->id) }}" wire:navigate
                                        title="{{ __('Edit') }}"
                                        class="inline-flex items-center p-1.5 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors">
                                        <x-lucide-pencil class="w-4 h-4" />
                                    </a>
                                    <button wire:click="deleteParticipant({{ $participant->id }})"
                                        wire:confirm="{{ __('Delete participant :name?', ['name' => $participant->nama_lengkap]) }}"
                                        title="{{ __('Delete') }}"
                                        class="inline-flex items-center p-1.5 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                        <x-lucide-trash-2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <div class="w-14 h-14 rounded-2xl bg-gray-100 dark:bg-gray-700/50 flex items-center justify-center text-gray-400 mb-3">
                                        <x-lucide-calendar class="w-7 h-7" />
                                    </div>
                                    <p class="font-semibold text-gray-800 dark:text-gray-200 text-base mb-1">{{ __('No participants found') }}</p>
                                    <p class="text-xs text-gray-400 mb-4">{{ __('Try adjusting your search or filter keywords, or add a new participant.') }}</p>
                                    <a href="{{ route('admin.events.create') }}" wire:navigate
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-xl transition-colors shadow-sm">
                                        <x-lucide-plus class="w-4 h-4" />
                                        {{ __('Add New Participant') }}
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($participants->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Showing') }} {{ $participants->firstItem() }}–{{ $participants->lastItem() }} {{ __('of') }}
                    {{ number_format($participants->total()) }} {{ __('participants') }}
                </p>
                <div>{{ $participants->links() }}</div>
            </div>
        @else
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Total') }}: {{ number_format($participants->total()) }} {{ __('participants') }}
                </p>
            </div>
        @endif
    </div>
</div>
