<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'image', 'is_active'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_recipe', 'recipe_id', 'product_id');
    }
}
