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

namespace App\Interfaces;

use App\Types\ResponseType;

interface ControllerResponseInterface {
    public function response(?object $data, ?int $statusCode = 200, ?array $headers = [],?ResponseType $format = ResponseType::JSON): void;
}