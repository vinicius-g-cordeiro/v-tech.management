<?php

declare(strict_types=1);

/**
 * @file 
 * @package 
 * @author Vinícius Gonçalves Cordeiro <vinicordeirogo@gmail.com>
 * @link https://github.com/vinicius-g-cordeiro/
 * @version 0.0.1
 * @copyright 2026 Vinicius Gonçalves Cordeiro
 */

require __DIR__ . '/../app/bootstrap.php';

use App\Types\Methods;
use App\Exceptions\RouteNotFoundException;

$method = Methods::{$_SERVER['REQUEST_METHOD']};
$uri = $_SERVER['REQUEST_URI'];
try {
    require_once __DIR__ . '/../routes/web.php';
    $router->run($method, $uri);
}catch(Exception $err){
    // clear headers
    header_remove();
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json');
    http_response_code($err->getCode());

    $errorLog = json_encode(object(error: object(
        code: $err->getCode(),
        message: $err->getMessage(),
        file: $err->getFile(),
        line: $err->getLine(),
        trace: $err->getTraceAsString(),
        previous: $err->getPrevious(),
        date: date('Y-m-d H:i:s'),
        ip: $_SERVER['REMOTE_ADDR'],
        user_agent: $_SERVER['HTTP_USER_AGENT'],
    )), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    // write error log, adding a new line 
    $filename = __DIR__ . '/../storage/logs/error/error-' . date('Ymd') . '.log';
    $file = fopen($filename, 'a');
    fwrite($file, $errorLog . PHP_EOL );
    fclose($file);

    echo json_encode(object(error: object(
        code: $err->getCode(),
        message: $err->getMessage(),
        file: $err->getFile(),
        line: $err->getLine(),
        trace: $err->getTraceAsString(),
        previous: $err->getPrevious(),
        date: date('Y-m-d H:i:s'),
        ip: $_SERVER['REMOTE_ADDR'],
        user_agent: $_SERVER['HTTP_USER_AGENT'],
    )), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}
