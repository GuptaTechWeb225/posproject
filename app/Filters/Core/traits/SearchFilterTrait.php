<?php


namespace App\Filters\Core\traits;


trait SearchFilterTrait
{
    public function search($search = null)
    {
        $this->builder
            ->where('name', 'LIKE', "%{$search}%");
    }
}
