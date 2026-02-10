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

namespace App\Core;

use ADOConnection;
use App\Core\Request;
use App\Core\Session;
use App\Interfaces\ControllerResponseInterface;
use App\Interfaces\DataReturnType;
use App\Interfaces\ResponseTypes;
use App\Types\Methods;
use App\Types\ResponseType;

#[\AllowDynamicProperties]
class Controller implements ControllerResponseInterface {
    function __construct(protected ?ADOConnection $connection = null, private ?Request $request = null, private ?Session $session = null, private ?Service $service = null, $view = null) {
        $this->connection = Connection::instance();
        $this->session = Session::instance();
        $this->session->set('csrf_token', bin2hex(random_bytes(32)));
    }

    function getCsrfToken(): string {
        return $this->session->get('csrf_token');
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __isset($name) {
        return isset($this->$name);
    }

    public function __unset($name) {
        unset($this->$name);
    }


    public function response(?object $data, ?int $statusCode = 200, ?array $headers = [], ?ResponseType $format = ResponseType::JSON): void {
        header("HTTP/1.1 $statusCode");
        foreach($headers as $header => $value) {
            header("$header: $value");
        }
        header("Content-Type: $format->value");
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}