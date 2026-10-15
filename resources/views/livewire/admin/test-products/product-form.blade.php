<div>
    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ $productId ? __('Edit Product') : __('Create Product') }}
        </h1>
        <a href="{{ route('admin.test-products.index') }}" wire:navigate
            class="inline-flex items-center gap-2 px-4 py-2 text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-sm font-medium rounded-xl transition-colors shadow-sm">
            <x-lucide-arrow-left class="w-4 h-4" />
            {{ __('Back') }}
        </a>
    </div>

    {{-- Form --}}
    <form wire:submit="save" class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ __('Product Information') }}</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- SKU --}}
                <div>
                    <label for="sku"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('SKU') }}</label>
                    <input type="text" id="sku" wire:model="sku"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all placeholder-gray-400"
                        placeholder="PROD-0001" />
                    @error('sku') <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Name --}}
                <div>
                    <label for="name"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Name') }}</label>
                    <input type="text" id="name" wire:model="name"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all placeholder-gray-400"
                        placeholder="{{ __('Product name') }}" />
                    @error('name') <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label for="category"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Category') }}</label>
                    <input type="text" id="category" wire:model="category"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all placeholder-gray-400"
                        placeholder="{{ __('e.g. Electronic, Fashion, Food, Office') }}" />
                    @error('category') <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Price --}}
                <div>
                    <label for="price"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Price (Rp)') }}</label>
                    <input type="number" id="price" wire:model="price" step="0.01" min="0"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all placeholder-gray-400"
                        placeholder="0" />
                    @error('price') <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Stock --}}
                <div>
                    <label for="stock"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Stock') }}</label>
                    <input type="number" id="stock" wire:model="stock" min="0"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all placeholder-gray-400"
                        placeholder="0" />
                    @error('stock') <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Published At --}}
                <div>
                    <label for="published_at"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Published At') }}</label>
                    <input type="datetime-local" id="published_at" wire:model="published_at"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all placeholder-gray-400" />
                    @error('published_at') <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label for="description"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Description') }}</label>
                    <textarea id="description" wire:model="description" rows="3"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all placeholder-gray-400 resize-none"
                        placeholder="{{ __('Product description (optional)') }}"></textarea>
                    @error('description') <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Active --}}
                <div class="flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="is_active" class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500 peer-checked:bg-blue-600">
                        </div>
                    </label>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Active') }}</span>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                <x-lucide-save class="w-4 h-4" />
                <span wire:loading.remove
                    wire:target="save">{{ $productId ? __('Update Product') : __('Create Product') }}</span>
                <span wire:loading wire:target="save">{{ __('Saving...') }}</span>
            </button>
            <a href="{{ route('admin.test-products.index') }}" wire:navigate
                class="px-6 py-2.5 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white text-sm font-medium transition-colors">
                {{ __('Cancel') }}
            </a>
        </div>
    </form>
</div>