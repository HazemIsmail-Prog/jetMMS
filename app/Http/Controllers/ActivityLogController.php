<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ActionsLog;

class ActivityLogController extends Controller
{
    public function __invoke(Request $request)
    {
        abort_if(auth()->id() != 1, 403);
        if(request()->wantsJson()){
            $logs = ActionsLog::getLogs($request->file);
            return response()->json($logs);
        }
        $files = glob(storage_path('logs/actions-*.log'));
        return view('pages.logs.index',compact('files'));
    }
}
