<div>
    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                {{ $participantId ? __('Edit Participant') : __('Add Participant') }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ $participantId ? __('Update participant event details and timing results.') : __('Register a new participant for the event.') }}
            </p>
        </div>
        <a href="{{ route('admin.events.index') }}" wire:navigate
            class="inline-flex items-center gap-2 px-4 py-2 text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-sm font-medium rounded-xl transition-colors shadow-sm">
            <x-lucide-arrow-left class="w-4 h-4" />
            {{ __('Back to Events') }}
        </a>
    </div>

    {{-- Form --}}
    <form wire:submit="save" class="space-y-6">
        {{-- Card 1: Participant Information --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <x-lucide-user class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                {{ __('Participant Information') }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nama Lengkap --}}
                <div class="md:col-span-2">
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Nama Lengkap') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama_lengkap" wire:model="nama_lengkap"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all placeholder-gray-400"
                        placeholder="{{ __('Nama lengkap peserta...') }}" />
                    @error('nama_lengkap')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nomor BIB --}}
                <div>
                    <label for="no_bib" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Nomor BIB') }}
                    </label>
                    <input type="text" id="no_bib" wire:model="no_bib"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all placeholder-gray-400 font-mono"
                        placeholder="{{ __('Contoh: 10024') }}" />
                    @error('no_bib')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Chip Code --}}
                <div>
                    <label for="chip_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Chip Code / RFID') }}
                    </label>
                    <input type="text" id="chip_code" wire:model="chip_code"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all placeholder-gray-400 font-mono"
                        placeholder="{{ __('Contoh: CHIP-98432') }}" />
                    @error('chip_code')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Gender --}}
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Gender / Jenis Kelamin') }}
                    </label>
                    <input type="text" id="gender" wire:model="gender" list="gender_list"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all placeholder-gray-400"
                        placeholder="{{ __('Male / Female') }}" />
                    <datalist id="gender_list">
                        <option value="Male"></option>
                        <option value="Female"></option>
                        <option value="Laki-laki"></option>
                        <option value="Perempuan"></option>
                    </datalist>
                    @error('gender')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Kategori') }}
                    </label>
                    <input type="text" id="kategori" wire:model="kategori" list="kategori_list"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all placeholder-gray-400"
                        placeholder="{{ __('Contoh: 10K, Half Marathon, Full Marathon, 5K') }}" />
                    <datalist id="kategori_list">
                        <option value="5K"></option>
                        <option value="10K"></option>
                        <option value="21K (Half Marathon)"></option>
                        <option value="42K (Full Marathon)"></option>
                    </datalist>
                    @error('kategori')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Sub Kategori --}}
                <div>
                    <label for="sub_kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Sub Kategori') }}
                    </label>
                    <input type="text" id="sub_kategori" wire:model="sub_kategori" list="sub_kategori_list"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all placeholder-gray-400"
                        placeholder="{{ __('Contoh: Open, Master 40+, National, Student') }}" />
                    <datalist id="sub_kategori_list">
                        <option value="Open"></option>
                        <option value="National"></option>
                        <option value="Master 40+"></option>
                        <option value="Master 50+"></option>
                        <option value="Student"></option>
                    </datalist>
                    @error('sub_kategori')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Card 2: Timing & Finish Status --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <x-lucide-timer class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                {{ __('Timing & Status Event') }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Status --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Status Peserta') }} <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="relative flex items-center gap-3 p-3.5 border rounded-xl cursor-pointer transition-all {{ $status === 'finished' ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/20 text-emerald-800 dark:text-emerald-300 ring-2 ring-emerald-500/20' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            <input type="radio" wire:model.live="status" value="finished" class="text-emerald-600 focus:ring-emerald-500" />
                            <div>
                                <p class="text-sm font-semibold">{{ __('Finished') }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Peserta menyelesaikan race') }}</p>
                            </div>
                        </label>

                        <label class="relative flex items-center gap-3 p-3.5 border rounded-xl cursor-pointer transition-all {{ $status === 'dns' ? 'border-amber-500 bg-amber-50/50 dark:bg-amber-950/20 text-amber-800 dark:text-amber-300 ring-2 ring-amber-500/20' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            <input type="radio" wire:model.live="status" value="dns" class="text-amber-600 focus:ring-amber-500" />
                            <div>
                                <p class="text-sm font-semibold">{{ __('DNS') }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Did Not Start (belum start)') }}</p>
                            </div>
                        </label>

                        <label class="relative flex items-center gap-3 p-3.5 border rounded-xl cursor-pointer transition-all {{ $status === 'dnf' ? 'border-rose-500 bg-rose-50/50 dark:bg-rose-950/20 text-rose-800 dark:text-rose-300 ring-2 ring-rose-500/20' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            <input type="radio" wire:model.live="status" value="dnf" class="text-rose-600 focus:ring-rose-500" />
                            <div>
                                <p class="text-sm font-semibold">{{ __('DNF') }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Did Not Finish (gugur)') }}</p>
                            </div>
                        </label>
                    </div>
                    @error('status')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Gun Time --}}
                <div>
                    <label for="gun_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Gun Time') }}
                    </label>
                    <input type="text" id="gun_time" wire:model="gun_time"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all placeholder-gray-400 font-mono"
                        placeholder="{{ __('Contoh: 00:45:10') }}" />
                    @error('gun_time')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Chip Time --}}
                <div>
                    <label for="chip_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Chip Time / Net Time') }}
                    </label>
                    <input type="text" id="chip_time" wire:model="chip_time"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all placeholder-gray-400 font-mono"
                        placeholder="{{ __('Contoh: 00:44:32') }}" />
                    @error('chip_time')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pace --}}
                <div class="md:col-span-2">
                    <label for="pace" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Pace') }}
                    </label>
                    <input type="text" id="pace" wire:model="pace"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all placeholder-gray-400 font-mono"
                        placeholder="{{ __('Contoh: 04:27 min/km') }}" />
                    @error('pace')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                <x-lucide-save class="w-4 h-4" />
                <span wire:loading.remove wire:target="save">
                    {{ $participantId ? __('Update Participant') : __('Save Participant') }}
                </span>
                <span wire:loading wire:target="save">{{ __('Saving...') }}</span>
            </button>
            <a href="{{ route('admin.events.index') }}" wire:navigate
                class="px-6 py-2.5 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white text-sm font-medium transition-colors">
                {{ __('Cancel') }}
            </a>
        </div>
    </form>
</div>
