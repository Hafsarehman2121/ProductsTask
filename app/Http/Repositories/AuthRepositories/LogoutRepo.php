<?php

namespace App\Http\Repositories\AuthRepositories;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Repositories\AuthRepositories\AuthRepositoryInterfaces\LogoutRepoInterface;

class LogoutRepo implements LogoutRepoInterface
{
    use HasApiTokens;

    /**
     * Logout the authenticated user by revoking their current access token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|false
     */
    public function logout(Request $request)
    {
        try {
            if ($request->user() && $request->user()->currentAccessToken()) {
                $request->user()->currentAccessToken()->delete();

                return [
                    'status' => true,
                    'message' => 'Logged out successfully.',
                ];
            }

            return [
                'status' => false,
                'message' => 'No active session or token found.',
            ];
        } catch (Exception $e) {
            Log::error('Error during logout: ' . $e->getMessage());
            return false;
        }
    }
}
