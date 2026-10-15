<div>
    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('Test Products') }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ number_format($products->total()) }}
                {{ __('products') }}</p>
        </div>
        <a href="{{ route('admin.test-products.create') }}" wire:navigate
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
            <x-lucide-plus class="w-4 h-4" />
            {{ __('Add Product') }}
        </a>
    </div>

    {{-- Flash Messages --}}
    <x-flash-messages />

    {{-- Toolbar --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-4">
        <div class="flex flex-col lg:flex-row gap-3">
            {{-- Search --}}
            <div class="relative flex-1">
                <x-lucide-search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="{{ __('Search SKU or name...') }}"
                    class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all placeholder-gray-400" />
            </div>

            {{-- Category Filter --}}
            <select wire:model.live="filterCategory"
                class="px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all min-w-[140px]">
                <option value="">{{ __('All Categories') }}</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>

            {{-- Status Filter --}}
            <select wire:model.live="filterStatus"
                class="px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all min-w-[120px]">
                <option value="">{{ __('All Status') }}</option>
                <option value="active">{{ __('Active') }}</option>
                <option value="inactive">{{ __('Inactive') }}</option>
            </select>

            {{-- Per Page --}}
            <select wire:model.live="perPage"
                class="px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all min-w-[80px]">
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>

        {{-- Range Filters --}}
        <div class="flex flex-col lg:flex-row gap-3 mt-3">
            <div class="flex items-center gap-2 flex-1">
                <span
                    class="text-xs font-medium text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ __('Price') }}:</span>
                <input type="number" wire:model.live.debounce.500ms="priceMin" placeholder="{{ __('Min') }}"
                    class="w-full px-3 py-2 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all placeholder-gray-400" />
                <span class="text-gray-400">–</span>
                <input type="number" wire:model.live.debounce.500ms="priceMax" placeholder="{{ __('Max') }}"
                    class="w-full px-3 py-2 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all placeholder-gray-400" />
            </div>
            <div class="flex items-center gap-2 flex-1">
                <span
                    class="text-xs font-medium text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ __('Stock') }}:</span>
                <input type="number" wire:model.live.debounce.500ms="stockMin" placeholder="{{ __('Min') }}"
                    class="w-full px-3 py-2 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all placeholder-gray-400" />
                <span class="text-gray-400">–</span>
                <input type="number" wire:model.live.debounce.500ms="stockMax" placeholder="{{ __('Max') }}"
                    class="w-full px-3 py-2 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all placeholder-gray-400" />
            </div>
        </div>
    </div>

    {{-- Bulk Actions Bar --}}
    @if ($selectedCount > 0)
        <div
            class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-sm font-medium text-blue-700 dark:text-blue-300">
                    {{ $selectedCount }} {{ __('selected') }}
                </span>
                @if (!$selectAll && $selectedCount < $products->total())
                    <button wire:click="selectAllProducts"
                        class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-medium">
                        {{ __('Select all') }} {{ number_format($products->total()) }}
                    </button>
                @endif
                <button wire:click="deselectAll" class="text-sm text-gray-500 dark:text-gray-400 hover:underline">
                    {{ __('Deselect all') }}
                </button>
            </div>
            <button wire:click="bulkDelete"
                wire:confirm="{{ __('Are you sure you want to delete the selected products?') }}"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                <x-lucide-trash-2 class="w-4 h-4" />
                {{ __('Delete Selected') }}
            </button>
        </div>
    @endif

    {{-- Table --}}
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" wire:loading.class="opacity-50">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <th class="w-10 px-3 py-3">
                            <input type="checkbox" wire:model.live="selectPage"
                                class="w-4 h-4 text-blue-600 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 focus:ring-2" />
                        </th>
                        @php
                            $columns = [
                                'sku' => 'SKU',
                                'name' => 'Name',
                                'category' => 'Category',
                                'stock' => 'Stock',
                                'price' => 'Price',
                                'is_active' => 'Status',
                                'published_at' => 'Published',
                            ];
                        @endphp
                        @foreach ($columns as $field => $label)
                            <th class="text-left px-4 py-3">
                                <button wire:click="sortBy('{{ $field }}')"
                                    class="flex items-center gap-1 font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-xs hover:text-gray-900 dark:hover:text-white transition-colors">
                                    {{ __($label) }}
                                    @if ($sortField === $field)
                                        <x-dynamic-component :component="'lucide-' . ($sortDirection === 'asc' ? 'chevron-up' : 'chevron-down')"
                                            class="w-3.5 h-3.5" />
                                    @else
                                        <x-lucide-chevrons-up-down class="w-3.5 h-3.5 opacity-30" />
                                    @endif
                                </button>
                            </th>
                        @endforeach
                        <th
                            class="text-right px-4 py-3 font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-xs">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors {{ in_array((string) $product->id, $selected) ? 'bg-blue-50/50 dark:bg-blue-900/10' : '' }}"
                            wire:key="product-{{ $product->id }}">
                            <td class="px-3 py-3">
                                <input type="checkbox" wire:model.live="selected" value="{{ $product->id }}"
                                    class="w-4 h-4 text-blue-600 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 focus:ring-2" />
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-500 dark:text-gray-400">{{ $product->sku }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $product->name }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                    {{ $product->category }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300 tabular-nums">
                                {{ number_format($product->stock) }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300 tabular-nums">Rp
                                {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                @if ($product->is_active)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300">
                                        <x-lucide-check-circle class="w-3 h-3" /> Active
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400">
                                        <x-lucide-x-circle class="w-3 h-3" /> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs whitespace-nowrap">
                                {{ $product->published_at?->format('d M Y H:i') ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.test-products.edit', $product->id) }}" wire:navigate
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-sm font-medium text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors">
                                        <x-lucide-pencil class="w-3.5 h-3.5" />
                                    </a>
                                    <button wire:click="deleteProduct({{ $product->id }})"
                                        wire:confirm="{{ __('Delete this product?') }}"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                        <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <x-lucide-package class="w-12 h-12 mx-auto mb-3 opacity-30" />
                                <p class="font-medium">{{ __('No products found.') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($products->hasPages())
            <div
                class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Showing') }} {{ $products->firstItem() }}–{{ $products->lastItem() }} {{ __('of') }}
                    {{ number_format($products->total()) }}
                </p>
                <div>{{ $products->links() }}</div>
            </div>
        @else
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Total') }}: {{ number_format($products->total()) }} {{ __('products') }}
                </p>
            </div>
        @endif
    </div>
</div>