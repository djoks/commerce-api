<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;

class Category extends BaseModel
{
    use Sluggable;

    protected $fillable = [
        'name',
        'slug'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
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
