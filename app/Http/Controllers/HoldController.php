<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\SlotService;
use App\Models\Slot;


class HoldController extends Controller
{
    public function __construct(
        private SlotService $slotService
    ) { }

    public function store(Request $request, Slot $slot): JsonResponse
    {
        return response()->json($slot);
    }
    }
