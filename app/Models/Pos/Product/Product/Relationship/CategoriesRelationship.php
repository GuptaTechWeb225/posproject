<?php

namespace App\Models\Pos\Product\Product\Relationship;
use App\Models\Pos\Product\Category\Category;

trait CategoriesRelationship
{
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}