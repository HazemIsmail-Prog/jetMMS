<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ActionsLog
{

    public static function cleanData($data)
    {
        if (is_array($data)) {
            return array_map(function($item) {
                return self::cleanData($item);
            }, $data);
        }
        if (is_string($data) && str_contains($data, "\n")) {
            return str_replace(["\r\n", "\r", "\n"], " ", $data);
        }
        return $data;   
    }

    public static function logAction($model, $action,$id, $message, $new_data = [], $old_data = [])
    {
        $user = auth()->user();
        $name = $user->name;
        
        // Convert name to UTF-8 if it contains Arabic characters
        if (preg_match('/\p{Arabic}/u', $name)) {
            $name = mb_convert_encoding($name, 'UTF-8', 'auto');
        }

        Log::channel('actions')->info(json_encode([
            'model' => $model,
            'user_id' => auth()->id(),
            'user_name' => $name,
            'action' => $action,
            'id' => $id,
            'message' => $message,
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'new_data' => self::cleanData($new_data),
            'old_data' => self::cleanData($old_data),
        ], JSON_UNESCAPED_UNICODE));
    }

    public static function getLogs($file = null)
    {
        // Get all logs from the actions log file
        // get each file starts with actions-
        
        // $files = glob(storage_path('logs/actions*.log'));
        $logs = file_get_contents($file);
        $logs = array_map(function($line) {
            // Extract JSON from log line by removing timestamp and log level
            if (preg_match('/\[.*?\]\s+\w+\.\w+:\s+(.*)/', $line, $matches)) {
                return json_decode($matches[1], true);
            }
            return null;
        }, array_filter(explode("\n", $logs)));
        return $logs;
    }

}