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
use App\Core\Model;
// Symfony Components
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class Service {
    function __construct(
        protected ?ADOConnection $connection = null, 
        protected ?Request $request = null, 
        protected ?Session $session = null,
        protected ?Model $model = null,
        protected ?ValidatorInterface $validator = null
    ) {
        $this->validator = Validation::createValidator();
    }


    function __destruct() {
        $this->connection = null;
        $this->request = null;
        $this->session = null;
        $this->model = null;
        $this->validator = null;
    }
    
}