<?php

namespace App\Http\Controllers\Pos\Api\Product\Duration;

use App\Filters\Pos\Product\Duration\DurationFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pos\Product\Duration\DurationRequest;
use App\Models\Pos\Product\Duration\Duration;
use App\Services\Pos\Product\Duration\DurationServices;

class DurationController extends Controller
{
    public function __construct(DurationServices $services, DurationFilter $filter)
    {
        $this->service = $services;
        $this->filter = $filter;
    }

    public function index()
    {
        return  $this->service->paginate(request()->per_page ?? 15);
    }

    public function store(DurationRequest $request)
    {
        $this->service->save($request->only('type', 'context','status_id'));
        return created_responses('duration');
    }


    public function show(Duration $duration):object
    {
        return $duration;
    }


    public function update(Duration $duration, DurationRequest $request)
    {
        $this->service
            ->setModel($duration)
            ->save($request->only('type', 'context','status_id'));

        return updated_responses('duration', [
            'duration' => $duration
        ]);
    }


    public function destroy(Duration $duration)
    {
        $duration->delete();
        return deleted_responses('duration');
    }
}
