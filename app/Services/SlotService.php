<?php

namespace App\Services;

use App\Models\Hold;
use App\Models\Slot;
use Illuminate\Support\Facades\Cache;
use RuntimeException;
use Illuminate\Support\Facades\DB;


/**
 * Class SlotService.
 */
class SlotService
{
    //_______________________________________________________
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
    //___________________________________________________________

public function createHold(Slot $slot, string $idempotencyKey): Hold
{
    return DB::transaction(function () use ($slot, $idempotencyKey) {
        $existingHold = Hold::where('idempotency_key', $idempotencyKey)->first();
        if ($existingHold) {
            return $existingHold;
        }

        $slot = Slot::where('id', $slot->id)->lockForUpdate()->firstOrFail();

        if ($slot->remaining <= 0) {
            throw new RuntimeException('Слот недоступен');
        }

        return Hold::create([
            'slot_id' => $slot->id,
            'idempotency_key' => $idempotencyKey,
            'status' => 'held',
            'expires_at' => now()->addMinutes(5),
        ]);
    });
}


//____________________________________________________________

public function confirmHold(Hold $hold): Hold
{

    return DB::transaction(function () use ($hold) {

        $hold = Hold::where('id', $hold->id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($hold->status !== 'held') {
            throw new RuntimeException('hold already updated');
        }

        if ($hold->expires_at->isPast()) {
            throw new RuntimeException('Hold already exists');
        }

        $slot = Slot::where('id', $hold->slot_id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($slot->remaining <= 0) {
            throw new RuntimeException('Not available holds');
        }

        $slot->decrement('remaining');

        $hold->update([
            'status' => 'confirmed',
        ]);

        Cache::forget('slots.availability');

        return $hold->fresh();
    });
}


//_________________________________________________________________________


public function cancelHold(Hold $hold): void
{

    DB::transaction(function () use ($hold) {

        $hold = Hold::where('id', $hold->id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($hold->status === 'cancelled') {
            throw new RuntimeException('hold already cancelled');
        }



        $slot = Slot::where('id', $hold->slot_id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($hold->status === 'cancelled') {
            $slot->increment('remaining');
        }

        

        $hold->update([
            'status' => 'cancelled',
        ]);

        Cache::forget('slots.availability');


    });
}






}
