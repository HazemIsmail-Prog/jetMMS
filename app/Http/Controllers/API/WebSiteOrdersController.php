<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;

class WebSiteOrdersController extends Controller
{
    public function store()
    {
        
        $areas = Area::query()->select('id','name_en','name_ar')->get();
        return $areas;
        }
}
