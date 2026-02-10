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

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use App\Exceptions\Authentication\RegistrationFailedException;
use App\Exceptions\User\UserNotFoundException;
use App\Exceptions\Authentication\LoginFailedException;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserService extends Service {
    
    function __construct(protected ?ADOConnection $connection = null, protected ?Request $request = null, protected ?Session $session = null, protected ?Model $model = null, protected ?ValidatorInterface $validator = null) {
        parent::__construct($connection, $request, $session, $model, $validator);
        $this->model = new UserModel($this->connection);
    }

    function register() : object {

        $assertCollection = new Assert\Collection([
            'fields' => [
                'name' => new Assert\NotBlank(),
                'email' => new Assert\NotBlank(),
                'password' => new Assert\NotBlank(),
                'password_confirmation' => new Assert\NotBlank(),
            ],
            'allowExtraFields' => true,
            'allowMissingFields' => false
        ]);

        $postInfo = $this->request->all()->body;

  
        $callback = new Assert\Callback(function ($value, ExecutionContextInterface $context) use ($postInfo) {
            if ($postInfo->password !== $postInfo->password_confirmation) {
                $context->buildViolation('As senhas devem ser iguais.')->atPath('password_confirmation')->addViolation();
            }
        });

        $hasError = $this->validator->validate((array)$postInfo, [$assertCollection, $callback]);

        if (count($hasError) > 0) {
            throw new RegistrationFailedException(message: 'Exception was thrown in validation, check your request', code: 400, previous: null);
        }

        $response = $this->transaction(function () use ($postInfo) {
            return $this->model->store((object)$postInfo);
        });

        if($response === null && $response < 1){
            throw new RegistrationFailedException(message: 'Registration failed with unknown error', code: 400, previous: null);
        }

        return object(success:true, code: 201, message: 'Registration successful');
    }

    function login() : object {
        $assertCollection = new Assert\Collection([
            'fields' => [
                'username' => new Assert\NotBlank(),
                'password' => new Assert\NotBlank(),
            ],
            'allowExtraFields' => true,
            'allowMissingFields' => false
        ]);

        $postInfo = $this->request->all()->body;

        $hasError = $this->validator->validate((array)$postInfo, [$assertCollection]);

        if (count($hasError) > 0) {
            throw new LoginFailedException(message: 'Exception was thrown in validation, check your request', code: 400, previous: null);
        }

        $user = $this->model->login((object)$postInfo);
        
        if($user === null && $user->id < 1){
            throw new UserNotFoundException(message: 'User not found!', code: 404, previous: null);
        }

        $token = $this->generateToken($user->id);

        return object(code: 200, message: 'Login successful', data: object(token: $token, user: $user, success: true));
    }

    public function getUserByToken(string $token): ?object {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            $request = object(id: $decoded->user_id);
            $user = $this->model->find($request);
            if ($user) {
                return $user;
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

}