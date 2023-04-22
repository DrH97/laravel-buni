<?php

use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

//TODO: Add tests for all these helpers
//    e.g. if logging channels doesn't exist, we shouldn't throw error
if (!function_exists('shouldBuniLog')) {
    function shouldBuniLog(): bool
    {
        return config('buni.logging.enabled') == true;
    }
}

if (!function_exists('getBuniLogger')) {
    function getBuniLogger(): LoggerInterface
    {
        if (shouldBuniLog()) {
            $channels = [];

            foreach (config('buni.logging.channels') as $rawChannel) {
                if (is_string($rawChannel)) {
                    $channels[] = $rawChannel;
                } elseif (is_array($rawChannel)) {
                    $channels[] = Log::build($rawChannel);
                }
            }

            return Log::stack($channels);
        }

        return Log::build([
            'driver' => 'single',
            'path' => '/dev/null',
        ]);
    }
}

if (!function_exists('buniLog')) {
    function buniLog(string $level, string $message, array $context = []): void
    {
        $message = '[LIB - BUNI]: ' . $message;
        getBuniLogger()->log($level, $message, $context);
    }
}

if (!function_exists('buniLogError')) {
    function buniLogError(string $message, array $context = []): void
    {
        $message = '[LIB - BUNI]: ' . $message;
        getBuniLogger()->error($message, $context);
    }
}

if (!function_exists('buniLogInfo')) {
    function buniLogInfo(string $message, array $context = []): void
    {
        $message = '[LIB - BUNI]: ' . $message;
        getBuniLogger()->info($message, $context);
    }
}
