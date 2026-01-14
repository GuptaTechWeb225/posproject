<?php

namespace App\Services\Pos\Product\Duration;

use App\Models\Pos\Product\Duration\Duration;
use App\Services\Core\BaseService;

class DurationServices extends BaseService
{
    public function __construct(Duration $duration)
    {
        $this->model = $duration;
    }

}