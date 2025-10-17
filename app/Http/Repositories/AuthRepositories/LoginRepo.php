<?php

namespace App\Http\Repositories\AuthRepositories;

use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Repositories\AuthRepositories\AuthRepositoryInterfaces\LoginRepoInterface;

class LoginRepo implements LoginRepoInterface
{
    use HasApiTokens;

    /**
     * Handle user login and trigger OTP sending.
     *
     * @param  array  $data
     * @return array|false
     * @throws ValidationException
     */
    public function login(array $data)
    {
        try {
            $user = User::where('email', $data['email'])->first();

            if (!$user || !Hash::check($data['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['Invalid credentials.'],
                ]);
            }

            return $this->sendOtp($data);

        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::error('Error during login: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate and send OTP via email.
     *
     * @param  array  $data
     * @return array|false
     */
    public function sendOtp(array $data)
    {
        try {
            $otp = rand(100000, 999999);

            $otpRecord = Otp::updateOrCreate(
                ['email' => $data['email']],
                [
                    'password' => Hash::make($data['password']),
                    'otpCode' => $otp,
                    'otp_expires_at' => now()->addMinutes(5),
                ]
            );

            Mail::raw("Your OTP for login is: {$otp}", function ($message) use ($data) {
                $message->to($data['email'])->subject('Your Login OTP');
            });

            return [
                'status' => true,
                'message' => 'OTP sent successfully to your email. Please verify to complete login.',
                'verify_otp_endpoint' => route('verify.login.otp', ['id' => $otpRecord->id]),
            ];
        } catch (Exception $e) {
            Log::error('Error sending OTP: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify OTP and issue API token.
     *
     * @param  array  $data  // expects ['otp' => '123456']
     * @param  int  $id
     * @return array|false
     */
    public function verifyOtp($data, $id)
    {
        try {
            $otpValue = $data['otp'] ?? null;
            $otpRecord = Otp::find($id);

            if (!$otpRecord) {
                return ['status' => false, 'message' => 'Invalid OTP request.'];
            }

            if ($otpRecord->otpCode != $otpValue) {
                return ['status' => false, 'message' => 'Invalid OTP.'];
            }

            if (now()->greaterThan($otpRecord->otp_expires_at)) {
                return ['status' => false, 'message' => 'OTP expired. Please log in again.'];
            }

            $user = User::where('email', $otpRecord->email)->first();
            $otpRecord->delete();

            if (!$user) {
                return ['status' => false, 'message' => 'User not found.'];
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'status' => true,
                'message' => 'Login successful.',
                'user' => $user,
                'token' => $token,
            ];
        } catch (Exception $e) {
            Log::error('Error verifying OTP: ' . $e->getMessage());
            return false;
        }
    }
}
