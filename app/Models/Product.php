<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /**
     * Maps a product's first listed color to a frame/lens tint applied to
     * its shared GLB model in the 3D viewer and Try On — the same static
     * asset is reused across every model_path product, so this is what
     * lets, e.g., a gold-frame product actually render gold instead of the
     * model's own baked-in chrome finish.
     *
     * @var array<string, array{frame: string, lens: string}>
     */
    private const MODEL_TINTS = [
        'Brushed Gold' => ['frame' => '#c9a24b', 'lens' => '#6b4a23'],
        'Gunmetal Blue' => ['frame' => '#54606b', 'lens' => '#1b3a4b'],
        'Matte Black' => ['frame' => '#232323', 'lens' => '#0a0a0a'],
        'Black' => ['frame' => '#1a1a1a', 'lens' => '#0a0a0a'],
        'Rose Gold' => ['frame' => '#caa08a', 'lens' => '#5c3a30'],
        'Silver' => ['frame' => '#c7cdd1', 'lens' => '#1a1a1a'],
    ];

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'image_url',
        'images',
        'model_path',
        'price',
        'compare_at_price',
        'stock',
        'sizes',
        'colors',
        'is_featured',
        'is_active',
        'requires_prescription',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_at_price' => 'decimal:2',
            'stock' => 'integer',
            'sizes' => 'array',
            'colors' => 'array',
            'images' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'requires_prescription' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return array<int, string>
     */
    public function gallery(): array
    {
        $images = array_values(array_filter($this->images ?? []));

        return $images !== [] ? $images : [$this->image_url];
    }

    /**
     * @return array{frame: string, lens: string}|null
     */
    public function modelTint(): ?array
    {
        $primaryColor = $this->colors[0] ?? null;

        return $primaryColor ? (self::MODEL_TINTS[$primaryColor] ?? null) : null;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlistedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}
