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

namespace App\Services;

use ADOConnection;
use App\Core\Service;
use App\Core\Request;
use App\Core\Session;
use App\Core\Model;
use App\Core\Connection;
use App\Models\UserModel;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService extends Service {
    function __construct(protected ?ADOConnection $connection = null, protected ?Request $request = null, protected ?Session $session = null, protected ?Model $model = null, protected ?ValidatorInterface $validator = null) {
        parent::__construct($connection, $request, $session, $model, $validator);
        $this->model = new UserModel($this->connection);
    }

    function register() {
    }
}