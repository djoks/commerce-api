<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends BaseModel implements HasMedia
{
    use InteractsWithMedia, Sluggable;

    protected $fillable = [
        'category_id',
        'name',
        'barcode',
        'cost_price',
        'selling_price',
        'lease_price',
        'status',
        'meta',
        'notes',
    ];

    protected $casts = [
        'meta' => 'json'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            if (request()->hasFile('photo')) {
                $model->clearMediaCollection('products');
                $model->addMediaFromRequest('photo')->toMediaCollection('products');
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stock()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function getSellingPriceAttribute($value)
    {
        return ($value / 100);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('products')->singleFile();
    }

    public function getPhotoAttribute()
    {
        return !$this->getFirstMediaUrl('products') ? null : $this->getFirstMediaUrl('products');
    }

    public function scopeFilterByName($query)
    {
        return $query->when(request('name'), fn ($query) => $query->where('name', 'like', '%' . request('name') . '%'));
    }

    public function scopeFilterByCategory($query)
    {
        return $query->when(request('category_id'), fn ($query) => $query->where('category_id', request('category_id')));
    }

    public function scopeWithAvailableStock($query)
    {
        return $query->with(['stock' => function ($query) {
            return $query->where('available_quantity', '>', 0)
                ->whereDate('expiry_date', '>', now());
        }]);
    }

    public function scopeWithPreferredOrder($query)
    {
        $orderBy = request('order_by', 'latest');
        $order = request('order', 'asc');

        if ($orderBy === 'latest') {
            return $query->latest();
        }

        return $query->orderBy($orderBy, $order);
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
