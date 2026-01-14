<?php


namespace App\Filters\Traits;


trait SubjectFilter
{
    public function name ($subject = null)
    {
        $this->whereClause('subject', "%{$subject}%", "LIKE");
    }
}