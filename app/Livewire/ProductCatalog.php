<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductCatalog extends Component
{
    use WithPagination;

    public bool $isShopPage = false;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'category')]
    public string $category = '';

    #[Url(as: 'color')]
    public string $color = '';

    #[Url(as: 'sort')]
    public string $sort = '';

    #[Url(as: 'min_price')]
    public string $minPrice = '';

    #[Url(as: 'max_price')]
    public string $maxPrice = '';

    public bool $filtersOpen = false;

    public function mount(bool $isShopPage = false): void
    {
        $this->isShopPage = $isShopPage;
    }

    public function updated(string $property): void
    {
        if (in_array($property, ['search', 'category', 'color', 'sort', 'minPrice', 'maxPrice'], true)) {
            $this->resetPage();
        }
    }

    public function selectCategory(string $slug): void
    {
        $this->category = $slug;
        $this->resetPage();
    }

    public function selectColor(string $color): void
    {
        $this->color = $this->color === $color ? '' : $color;
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'category', 'color', 'minPrice', 'maxPrice']);
        $this->resetPage();
    }

    public function toggleFilters(): void
    {
        $this->filtersOpen = ! $this->filtersOpen;
    }

    public function render(): View
    {
        $selectedCategory = $this->category !== ''
            ? Category::where('slug', $this->category)->first()
            : null;

        $minPrice = $this->minPrice !== '' ? (float) $this->minPrice : null;
        $maxPrice = $this->maxPrice !== '' ? (float) $this->maxPrice : null;

        $products = Product::query()
            ->active()
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->when($this->search !== '', function ($query): void {
                $likeSearch = "%{$this->search}%";

                $query->where(function ($query) use ($likeSearch): void {
                    $query
                        ->where('name', 'like', $likeSearch)
                        ->orWhere('description', 'like', $likeSearch)
                        ->orWhere('colors', 'like', $likeSearch)
                        ->orWhere('sizes', 'like', $likeSearch)
                        ->orWhereHas('category', fn ($query) => $query->where('name', 'like', $likeSearch));
                });
            })
            ->when($selectedCategory !== null, fn ($query) => $query->whereBelongsTo($selectedCategory))
            ->when($this->color !== '', fn ($query) => $query->whereJsonContains('colors', $this->color))
            ->when($minPrice !== null, fn ($query) => $query->where('price', '>=', $minPrice))
            ->when($maxPrice !== null, fn ($query) => $query->where('price', '<=', $maxPrice))
            ->when($this->sort === 'price_asc', fn ($query) => $query->orderBy('price'))
            ->when($this->sort === 'price_desc', fn ($query) => $query->orderByDesc('price'))
            ->when(! in_array($this->sort, ['price_asc', 'price_desc'], true), fn ($query) => $query->latest())
            ->paginate(9);

        return view('livewire.product-catalog', [
            'products' => $products,
            'categories' => Category::withCount(['products' => fn ($query) => $query->active()])->get(),
            'availableColors' => Product::active()->pluck('colors')->flatten()->unique()->sort()->values(),
            'selectedCategory' => $selectedCategory,
            'wishlistedProductIds' => auth()->user()?->wishlistedProducts()->pluck('products.id')->all() ?? [],
        ]);
    }
}
