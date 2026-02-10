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
use Exception;
use Closure;


use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class Service {

    protected string $secretKey;
    protected int $tokenExpiration = 3600; // 1 hora


    function __construct(
        protected ?ADOConnection $connection = null, 
        protected ?Request $request = null, 
        protected ?Session $session = null,
        protected ?Model $model = null,
        protected ?ValidatorInterface $validator = null
    ) {
        $this->validator = Validation::createValidator();
        
        $this->secretKey = trim(file_get_contents(getenv('API_KEY_FILE')));
    }


    function __destruct() {
        $this->connection = null;
        $this->request = null;
        $this->session = null;
        $this->model = null;
        $this->validator = null;
    }
   
    function transaction(?Closure $callback) : mixed {

        $this->connection->StartTrans();
        try{
            $result = $callback();
            $this->connection->CompleteTrans();
            return $result;
        }catch(\Exception $e){
            $this->connection->FailTrans();
            $this->connection->RollbackTrans();
            throw $e;
        }
    }    

    
    public function refreshToken(string $token): ?string {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            return $this->generateToken($decoded->user_id);
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function generateToken(int $userId): string {
        $issuedAt = time();
        $expire = $issuedAt + $this->tokenExpiration;

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expire,
            'user_id' => $userId
        ];

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    public function validateToken(string $token): bool {
        try {
            JWT::decode($token, new Key($this->secretKey, 'HS256'));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

}