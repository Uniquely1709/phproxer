<?php

namespace App\Repositories;

use App\Models\LogEntry;
use Illuminate\Support\Facades\Log;

class Logger
{
    /**
     * @param string $message
     * @return void
     */
    public static function debug(string $message): void
    {
        self::writeDB($message, 'debug');
        Log::debug("Logger || ".$message);
    }

    /**
     * @param string $message
     * @return void
     */
    public static function info(string $message): void
    {
        self::writeDB($message, 'info');
        Log::info("Logger || ".$message);
    }

    /**
     * @param string $message
     * @return void
     */
    public static function warning(string $message): void
    {
        self::writeDB($message, 'warning');
        Log::warning("Logger || ".$message);
    }

    /**
     * @param string $message
     * @return void
     */
    public static function error(string $message):void
    {
        self::writeDB($message, "error");
        Log::error("Logger || ".$message);
    }

    /**
     * @param string $message
     * @param string $level
     * @return void
     */
    private static function writeDB(string $message, string $level):void
    {
        LogEntry::create([
            'User'=>'TMP',
            'Entry'=>$message,
            'Level'=>$level,
        ]);
    }
}
