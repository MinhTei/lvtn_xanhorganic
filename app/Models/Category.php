<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable=['parent_id','slug','name','is_active','shelf_days'];

    public function products(){
        return $this->hasMany(Product::class,'category_id');
    }
    public function parent(){
        return $this->belongsTo(Category::class,'parent_id');
    }
    public function children(){
        return $this->hasMany(Category::class,'parent_id');
    }
}
