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

namespace App\Tests;

use PHPUnit\Framework\TestCase;

use App\Controllers\HomeController;
use App\Core\Connection;

class Test extends TestCase
{
    public function testCrfsToken() {
        $homeController = new HomeController(Connection::instance());
        $this->assertTrue(strlen($homeController->getCsrfToken()) == 32);
    }
}