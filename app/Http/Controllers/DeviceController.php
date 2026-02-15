<?php

namespace App\Http\Controllers;

use App\Enums\DataType;
use App\Models\Measurement;
use App\Services\DeviceLatestService;
use App\Services\MeasurementService;
use App\Services\MeasurementValueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeviceController extends Controller
{
    public function update(
        Request                 $request,
        MeasurementService      $measurementService,
        MeasurementValueService $measurementValueService,
        DeviceLatestService     $deviceLatestStateService
    ): JsonResponse
    {
        $deviceToken = $request->attributes->get('deviceToken');

        $device = $deviceToken->device()->firstOrFail();

        $sensors = $device->sensors()->get();

        $dataToValidate = [];

        foreach ($sensors as $sensor) {
            $rules = [];

            // required or nullable
            if ($sensor->required) {
                $rules[] = 'required';
            }
            else {
                $rules[] = 'nullable';
            }

            $rules[] = DataType::from($sensor->data_type)->getValidationRule();

            // min / max
            if (!is_null($sensor->min_value)) {
                $rules[] = 'min:' . $sensor->min_value;
            }

            if (!is_null($sensor->max_value)) {
                $rules[] = 'max:' . $sensor->max_value;
            }

            $dataToValidate[$sensor->key] = $rules;
        }

        $validator = Validator::make($request->all(), $dataToValidate);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validatedRequest = $validator->validated();

        $measurement = $measurementService->storeFromDevicePayload($device, $validatedRequest);

        $measurementValueService->createFromPayload($measurement, $sensors, $validatedRequest);

        $deviceLatestStateService->createOrUpdate($device, $measurement);

        return response()->json([
            ...$validatedRequest,
            'Message' => 'Data received successfully',
        ]);
    }
}
