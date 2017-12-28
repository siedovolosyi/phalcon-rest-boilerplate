<?php

namespace App\Controllers;

use PhalconRest\Mvc\Controllers\CrudResourceController;

class UserController extends CrudResourceController
{
    public function me()
    {
        return $this->createResourceResponse($this->userService->getDetails());
    }

    public function authenticate()
    {
        $request = $this->request->getJsonRawBody();
        $username = $request->username;
        $password = $request->password;

        $session = $this->authManager->loginWithUsernamePassword(\App\Auth\UsernameAccountType::NAME, $username,
            $password);

        $transformer = new \App\Transformers\UserTransformer;
        $transformer->setModelClass('App\Model\User');

        $user = $this->createItemResponse(\App\Model\User::findFirst($session->getIdentity()), $transformer);

        $response = [
            'token' => $session->getToken(),
            'expires' => $session->getExpirationTime(),
            'user' => $user
        ];

        return $this->createArrayResponse($response, 'data');
    }

    public function register()
    {
        $request = $this->request->getJsonRawBody();
        $username = $request->username;
        $password = $request->password;
        $passwordRepeat = $request->password_repeat;
        $name = $this->request->getPost('name', ['string', 'striptags']);

    }

    public function whitelist()
    {
        return [
            'firstName',
            'lastName',
            'password'
        ];
    }
}
