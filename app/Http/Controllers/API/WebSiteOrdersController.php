<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebSiteOrdersController extends Controller
{
    public function store(Request $request)
    {

        Log::info($request->all());
        return response('OK');
    }
}
