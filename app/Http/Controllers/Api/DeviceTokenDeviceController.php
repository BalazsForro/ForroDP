<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeviceTokenDeviceController extends Controller
{
    public function index(Request $request)
    {

        return response()->json(['ok' => true]);
    }
}
