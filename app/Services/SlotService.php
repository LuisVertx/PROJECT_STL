<?php

namespace App\Services;

use App\Models\Hold;
use App\Models\Slot;


/**
 * Class SlotService.
 */
class SlotService
{
    public function availability()
    {
        return Slot::query()->select([
            'id as slot_id',
            'capacity',
            'remaining',
        ])->get();
    }
}
