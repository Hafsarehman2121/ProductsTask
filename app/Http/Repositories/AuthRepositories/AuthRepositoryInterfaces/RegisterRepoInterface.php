<?php

namespace App\Http\Repositories\AuthRepositories\AuthRepositoryInterfaces;


use Illuminate\Http\Request;

interface RegisterRepoInterface{
    public function register(array $data);
    public function sendOtp(array $data);
    public function verifyOtp($otp, $id);
   
    
}