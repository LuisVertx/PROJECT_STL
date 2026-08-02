<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\SlotService;
use App\Models\Slot;
use App\Models\Hold;
use App\Http\Requests\StoreHoldRequest;

class HoldController extends Controller
{
    public function __construct(
        private SlotService $slotService
    ) { }
    //_____________________________________________
    public function store(Request $request, Slot $slot): JsonResponse
    {

        

        try {
            $hold = $this->slotService->createHold($slot, $request->header('Idempotency-Key'));
            return response()->json($hold, 201);
        } catch (\RuntimeException $e) {
            return response()->json([
                'message' => $e->getMessage(),], 409);
        }

    }

    //________________________________________________________________

    public function confirm(Hold $hold): JsonResponse
    {
        try {
            $hold = $this->slotService->confirmHold($hold);
            return response()->json($hold);
        } catch (\RuntimeException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 409);
        }


    }


    public function destroy(Hold $hold): JsonResponse
    {
        try {
            $hold = $this->slotService->cancelHold($hold);
            return response()->json([
                'message' => 'Hold cancelled',
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 409);
        }


    }



    }
