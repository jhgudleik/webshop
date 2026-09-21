<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Product extends Model
{
    use CrudTrait, HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'price',
        'quantity',
        'active',
        'image',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($product) {
            if (empty($product->slug) && ! empty($product->title)) {
                $product->slug = Str::slug($product->title);
            }

            // Handle image upload
            if (request()->hasFile('image')) {
                // Delete old image if exists
                if ($product->image) {
                    \Storage::disk('public')->delete($product->image);
                }
                $product->image = request()->file('image')->store('products', 'public');
            }
        });

        static::deleting(function ($product) {
            if ($product->image) {
                \Storage::disk('public')->delete($product->image);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
