<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ActionsLog;

class ActivityLogController extends Controller
{
    public function __invoke()
    {
        abort_if(auth()->id() != 1, 403);
        $logs = ActionsLog::getLogs();
        return view('pages.logs.index',compact('logs'));
    }
}
