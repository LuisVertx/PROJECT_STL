<?php

namespace App\Services;

use App\Models\Hold;
use App\Models\Slot;
use Illuminate\Support\Facades\Cache;

/**
 * Class SlotService.
 */
class SlotService
{
    public function availability()
    {
         return Cache::remember(
            'slots.availability',
            now()->addSeconds(10),
            function () {
                return Slot::query()->select([
                'id as slot_id',
                'capacity',
                'remaining',
            ])->get();
            }
         );
            
    }
}
