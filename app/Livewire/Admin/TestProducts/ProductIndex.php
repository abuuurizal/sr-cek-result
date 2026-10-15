<?php

namespace App\Livewire\Admin\TestProducts;

use App\Models\TesProduct;
use Livewire\Component;
use Livewire\WithPagination;

class ProductIndex extends Component
{
    use WithPagination;

    // Search & Filter
    public string $search = '';
    public string $filterCategory = '';
    public string $filterStatus = '';
    public string $priceMin = '';
    public string $priceMax = '';
    public string $stockMin = '';
    public string $stockMax = '';

    // Sorting
    public string $sortField = 'published_at';
    public string $sortDirection = 'desc';

    // Per page
    public int $perPage = 25;

    // Bulk selection
    public array $selected = [];
    public bool $selectAll = false;
    public bool $selectPage = false;

    // Reset pagination on filter change
    public function updatingSearch(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }
    public function updatingFilterCategory(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }
    public function updatingFilterStatus(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }
    public function updatingPriceMin(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }
    public function updatingPriceMax(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }
    public function updatingStockMin(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }
    public function updatingStockMax(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }
    public function updatingPerPage(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }

    // Sort
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // Select page
    public function updatedSelectPage(bool $value): void
    {
        if ($value) {
            $this->selected = $this->getProductsQuery()
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->selected = [];
        }
    }

    // Select all
    public function selectAllProducts(): void
    {
        $this->selectAll = true;
        $this->selected = $this->getProductsQuery(paginate: false)
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();
    }

    public function deselectAll(): void
    {
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
    }

    private function resetSelection(): void
    {
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
    }

    // Delete single
    public function deleteProduct(int $id): void
    {
        TesProduct::findOrFail($id)->delete();
        $this->resetSelection();
        session()->flash('success', 'Product deleted successfully.');
    }

    // Bulk delete
    public function bulkDelete(): void
    {
        $deleted = TesProduct::whereIn('id', $this->selected)->delete();
        $this->resetSelection();

        if ($deleted === 0) {
            session()->flash('error', 'No products were deleted.');
        } else {
            session()->flash('success', "{$deleted} product(s) deleted.");
        }
    }

    // Build query — uses cursor-based approach for fast filtering on large datasets
    private function getProductsQuery(bool $paginate = true)
    {
        $query = TesProduct::query()
            ->when($this->search, function ($q) {
                $q->where(function ($q) {
                    $q->where('sku', 'like', "%{$this->search}%")
                        ->orWhere('name', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filterCategory, fn($q) => $q->where('category', $this->filterCategory))
            ->when($this->filterStatus !== '', function ($q) {
                if ($this->filterStatus === 'active') {
                    $q->where('is_active', true);
                } elseif ($this->filterStatus === 'inactive') {
                    $q->where('is_active', false);
                }
            })
            ->when($this->priceMin !== '', fn($q) => $q->where('price', '>=', (float) $this->priceMin))
            ->when($this->priceMax !== '', fn($q) => $q->where('price', '<=', (float) $this->priceMax))
            ->when($this->stockMin !== '', fn($q) => $q->where('stock', '>=', (int) $this->stockMin))
            ->when($this->stockMax !== '', fn($q) => $q->where('stock', '<=', (int) $this->stockMax))
            ->orderBy($this->sortField, $this->sortDirection);

        return $paginate ? $query->paginate($this->perPage) : $query->get();
    }

    public function render()
    {
        return view('livewire.admin.test-products.product-index', [
            'products' => $this->getProductsQuery(),
            'categories' => TesProduct::distinct()->pluck('category')->sort()->values(),
            'selectedCount' => count($this->selected),
        ])->layout('layouts.admin');
    }
}
