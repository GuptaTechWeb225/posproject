<?php


namespace App\Http\Controllers\Tenant\Counter;

use App\Http\Controllers\Controller;
use App\Repositories\Core\Status\StatusRepository;


class CounterStatusController extends Controller
{
    public function update($id)
    {

        $counter = Counter::findOrFail($id);
        $method = $counter->isOpen() ? 'counterClose' : 'counterOpen';
        $status_id = resolve(StatusRepository::class)->$method();
        $counter->update([
            'status_id' => $status_id
        ]);

        return updated_responses('counter', ['counter' => $counter]);
    }
}