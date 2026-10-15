<?php

namespace App\Livewire\Admin\TestProducts;

use App\Models\TesProduct;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ProductForm extends Component
{
    public ?int $productId = null;
    public string $sku = '';
    public string $name = '';
    public string $description = '';
    public int $stock = 0;
    public string $price = '0';
    public string $category = '';
    public bool $is_active = true;
    public ?string $published_at = null;

    public function mount(?int $productId = null): void
    {
        if ($productId) {
            $this->productId = $productId;
            $product = TesProduct::findOrFail($productId);
            $this->sku = $product->sku;
            $this->name = $product->name;
            $this->description = $product->description ?? '';
            $this->stock = $product->stock;
            $this->price = (string) $product->price;
            $this->category = $product->category;
            $this->is_active = $product->is_active;
            $this->published_at = $product->published_at?->format('Y-m-d\TH:i');
        }
    }

    public function save(): void
    {
        $rules = [
            'sku' => ['required', 'string', 'max:255', Rule::unique('tes_products')->ignore($this->productId)],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'is_active' => 'boolean',
            'published_at' => 'nullable|date',
        ];

        $this->validate($rules);

        $data = [
            'sku' => $this->sku,
            'name' => $this->name,
            'description' => $this->description ?: null,
            'stock' => $this->stock,
            'price' => $this->price,
            'category' => $this->category,
            'is_active' => $this->is_active,
            'published_at' => $this->published_at ?: null,
        ];

        if ($this->productId) {
            TesProduct::findOrFail($this->productId)->update($data);
        } else {
            TesProduct::create($data);
        }

        session()->flash('success', $this->productId ? 'Product updated successfully.' : 'Product created successfully.');
        $this->redirect(route('admin.test-products.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.test-products.product-form')
            ->layout('layouts.admin');
    }
}
