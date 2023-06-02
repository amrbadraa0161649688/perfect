<?php

namespace App\Repositories\Interfaces;


interface AuthRepositoryInterface
{
    public function login(array $data);

    public function register(array $data);

    public function logout($request);

    public function generateRandomString($length);
}
