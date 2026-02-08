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

require_once __DIR__ . '/../app/bootstrap.php';

use PHPUnit\Framework\TestCase;
use App\Core\Connection;
use ADOConnection;

class ConnectionTest extends TestCase 
{
    /**
     * Tests if the connection is working and if the connection is pooled or not
     */
    public function testConnection() {
        $connection = Connection::instance();
        $this->assertInstanceOf(ADOConnection::class, $connection);
        $this->assertTrue((bool)Connection::getConnectionInfo()->persistent, 'Connection is pooled');
    }

}