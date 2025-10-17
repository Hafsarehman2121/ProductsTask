<?php

namespace App\Http\Repositories\AuthRepositories\AuthRepositoryInterfaces;


use Illuminate\Http\Request;

interface LogoutRepoInterface{
  public function logout(Request $request);
    
}