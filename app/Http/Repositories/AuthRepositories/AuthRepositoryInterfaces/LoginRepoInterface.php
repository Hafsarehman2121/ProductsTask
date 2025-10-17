<?php

namespace App\Http\Repositories\AuthRepositories\AuthRepositoryInterfaces;


use Illuminate\Http\Request;

interface LoginRepoInterface{

    public function login(array $credentials);
    public function sendOtp(array $data);
    public function verifyOtp($otp, $id);
     
    
}