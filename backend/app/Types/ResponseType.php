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

namespace App\Types;

enum ResponseType : string {
    case JSON = 'application/json';
    case XML = 'application/xml';
    case HTML = 'text/html';
    case TEXT = 'text/plain';
};
