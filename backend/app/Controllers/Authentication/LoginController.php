<?php

declare(strict_types=1);

/**
 * @file LoginController.php
 * @package 
 * @author Vinícius Gonçalves Cordeiro <vinicordeirogo@gmail.com>
 * @link https://github.com/vinicius-g-cordeiro/
 * @version 0.0.1
 * @copyright 2026 Vinicius Gonçalves Cordeiro
 */

namespace App\Controllers\Authentication;

use ADOConnection;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Service;
use App\Services\UserService;
use App\Models\UserModel;
use App\Types\Methods;
use App\Core\Connection;

use App\Exceptions\User\UserNotFoundException;
use App\Exceptions\Authentication\LoginFailedException;
use Exception;

class LoginController extends Controller {
    protected ?UserService $userService = null;
    function __construct(?ADOConnection $connection = null, ?Request $request = null, ?Session $session = null, ?Service $service = null, $view = null){
        parent::__construct($connection, $request, $session, $service, $view);
        $this->connection = Connection::instance();
        $this->service = new UserService($this->connection, $this->request, $this->session, $this->model, null, new UserModel($this->connection));
    }

    function index() : void { }

    function login() : string {
        header("Content-Type: application/json");
        try{
            $response = $this->service->login();
            http_response_code(200);
        }catch(UserNotFoundException $e){
            $response = object(success: false, message: $e->getMessage(), code: $e->getCode(), data: null);
            http_response_code($e->getCode());
        }catch(LoginFailedException $e){
            $response = object(success: false, message: $e->getMessage(), code: $e->getCode(), data: null);
            http_response_code($e->getCode());
        }catch(Exception $e){
            $response = object(success: false, message: $e->getMessage(), code: $e->getCode(), data: null);
            http_response_code($e->getCode());
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    function logout() : string {
        header("Content-Type: application/json");
        try{
            $response = $this->service->logout();
            http_response_code(200);
        }catch(Exception $e){
            $response = object(success: false, message: $e->getMessage(), code: $e->getCode(), data: null);
            http_response_code($e->getCode());
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}