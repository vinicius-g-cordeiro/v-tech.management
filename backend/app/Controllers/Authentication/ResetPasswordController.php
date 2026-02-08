<?php

declare(strict_types=1);

/**
 * @file ResetPasswordController.php
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

class ResetPasswordController extends Controller {
    protected ?UserService $userService = null;
    function __construct(?ADOConnection $connection = null, ?Request $request = null, ?Session $session = null, ?Service $service = null, $view = null){
        parent::__construct($connection, $request, $session, $service, $view);
        $this->userService = new UserService($this->connection, $this->request, $this->session, $this->model, null, new UserModel($this->connection));
    }

    function index() : void { }
}