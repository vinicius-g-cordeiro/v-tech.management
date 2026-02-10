<?php

declare(strict_types=1);

/**
 * @file RegisterController.php
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
use App\Core\Connection;

use App\Exceptions\Authentication\RegistrationFailedException;
use Exception;

class RegisterController extends Controller {
    function __construct(protected ?ADOConnection $connection = null,?Request $request = null,?Session $session = null,?Service $service = null,protected $view = null){
        parent::__construct(null, $request, $session, $service, $view);
        $this->connection = Connection::instance();
        $this->service = new UserService($this->connection, $this->request, $this->session, $this->model, null, new UserModel($this->connection));
    }

    function index() : void { }

    function store() : string {
        try{
            $response = $this->service->register();
        }catch(RegistrationFailedException $e){
            $response = object(success: false, message: $e->getMessage(), code: $e->getCode());
        }catch(Exception $e){
            $response = object(success: false, message: $e->getMessage(), code: $e->getCode());
        }
        return json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}