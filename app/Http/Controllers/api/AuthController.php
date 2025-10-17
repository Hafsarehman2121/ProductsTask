<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequests\LoginRequest;
use App\Http\Requests\AuthRequests\RegisterRequest;
use App\Http\Requests\AuthRequests\VerifyOtpRequest;
use App\Http\Repositories\AuthRepositories\AuthRepositoryInterfaces\LoginRepoInterface;
use App\Http\Repositories\AuthRepositories\AuthRepositoryInterfaces\LogoutRepoInterface;
use App\Http\Repositories\AuthRepositories\AuthRepositoryInterfaces\RegisterRepoInterface;
use Illuminate\Validation\ValidationException;
use Exception;

class AuthController extends Controller
{
    protected $registerRepo;
    protected $loginRepo;
    protected $logoutRepo;

    /**
     * Inject repository interfaces for register, login, and logout.
     */
    public function __construct(
        RegisterRepoInterface $registerRepo,
        LoginRepoInterface $loginRepo,
        LogoutRepoInterface $logoutRepo
    ) {
        $this->registerRepo = $registerRepo;
        $this->loginRepo = $loginRepo;
        $this->logoutRepo = $logoutRepo;
    }

    /**
     * Register a new user.
     */
    public function register(RegisterRequest $request)
    {
        try {
            $response = $this->registerRepo->register($request->validated());

            return response()->json($response, $response['status'] ? 201 : 400);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error registering user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Authenticate a user and generate token.
     */
    public function login(LoginRequest $request)
    {
        try {
            $data = $this->loginRepo->login($request->validated());

            return response()->json($data, $data['status'] ? 200 : 400);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error during login.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout the authenticated user.
     */
    public function logout(Request $request)
    {
        try {
            $data = $this->logoutRepo->logout($request);

            return response()->json($data, $data['status'] ? 200 : 400);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error during logout.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify OTP for registration process.
     */
    public function verifyOtp(VerifyOtpRequest $request, $id)
    {
        try {
            $response = $this->registerRepo->verifyOtp($request->validated(), $id);

            return response()->json($response, $response['status'] ? 200 : 400);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error verifying registration OTP.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify OTP for login process.
     */
    public function verifyLoginOtp(VerifyOtpRequest $request, $id)
    {
        try {
            $response = $this->loginRepo->verifyOtp($request->validated(), $id);

            return response()->json($response, $response['status'] ? 200 : 400);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error verifying login OTP.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
