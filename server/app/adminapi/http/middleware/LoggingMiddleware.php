<?php

declare (strict_types=1);

namespace app\adminapi\http\middleware;

class LoggingMiddleware
{
    public function handle($request, \Closure $next)
    {
        $start = microtime(true);
        $response = $next($request);
        $end = microtime(true);

        $logMessage = 'Request: ' . $request->method() . ' ' . $request->url() . ' ';
        $logMessage .= 'Headers: ' . json_encode($request->header()) . ' ';
        $logMessage .= 'Parameters: ' . json_encode($request->param()) . ' ';
        $logMessage .= 'Client IP: ' . $request->ip() . ' ';
        $logMessage .= 'Time: ' . round($end - $start, 6) . 's';
        app('log')->info($logMessage);

        $logMessage = 'Response: ' . $response->getCode() . ' ' . $response->getContent();
        app('log')->info($logMessage);

        return $response;
    }
}
