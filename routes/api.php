<?php

use App\Enums\DataType;
use App\Http\Controllers\DeviceController;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('device.token')->group(function () {
    Route::get('/get/devices', function (Request $request) {
        $deviceToken = $request->attributes->get('deviceToken');

        $device = $deviceToken->device()->with('sensors')->firstOrFail();

        return response()->json($device);
    });

    Route::post('set/data', [DeviceController::class, 'update']);
});
