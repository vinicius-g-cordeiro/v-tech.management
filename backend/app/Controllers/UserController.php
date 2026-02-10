<?php

declare(strict_types=1);

/**
 * @file UserController.php
 * @package 
 * @author Vinícius Gonçalves Cordeiro <vinicordeirogo@gmail.com>
 * @link https://github.com/vinicius-g-cordeiro/
 * @version 0.0.1
 * @copyright 2026 Vinicius Gonçalves Cordeiro
 */

namespace App\Controllers;

use ADOConnection;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Service;
use App\Services\UserService;
use App\Core\Connection;
use App\Models\UserModel;

use Exception;

class UserController extends Controller {

    function __construct(?ADOConnection $connection = null, ?Request $request = null, ?Session $session = null, ?Service $service = null, $view = null) {
        parent::__construct($connection, $request, $session, $service, $view);
        $this->connection = Connection::instance();
        $this->service = new UserService($this->connection, $this->request, $this->session, $this->model, null, new UserModel($this->connection));
    }

    function index() : void { }

    function show() : string {
        $response = null;
        try {
            $response = $this->service->getUserByToken($this->request->get('token'));
        } catch (Exception $e) {
            $response = object(success: false, message: $e->getMessage(), code: $e->getCode(), data: null);
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    function me() : string {
        $response = null;
        try {
            $user = $this->service->getUserByToken($this->request->getBearerToken());
            $response = object(success: true, message: 'Usuário encontrado', code: 200, data: object(user: $user, token: $this->request->getBearerToken()));
        } catch (Exception $e) {
            $response = object(success: false, message: $e->getMessage(), code: $e->getCode(), data: null);
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}