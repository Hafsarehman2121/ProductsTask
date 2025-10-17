<?php


namespace App\Http\Repositories\AuthRepositories;

use Exception;
use Carbon\Carbon;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use App\Http\Repositories\AuthRepositories\AuthRepositoryInterfaces\RegisterRepoInterface;


class RegisterRepo implements RegisterRepoInterface{

    use HasApiTokens;

    /**
     * Handle registration process.
     * If OTP is not provided, send one to the user’s email.
     *
     * @param  array  $data
     * @return array|false
     */
    public function register(array $data)
    {
        if (!isset($data['otp'])) {
            return $this->sendOtp($data);
        }
  
    }
      
    /**
     * Generate and send OTP for registration.
     *
     * @param  array  $data
     * @return array|false
     */
   public function sendOtp(array $data)
    {
       try{
            if (User::where('email', $data['email'])->exists()) {
                return ['status' => false, 'message' => 'Email already registered.'];
            }

            $otp = rand(100000, 999999);

            $otpRecord = Otp::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    'otpCode' => $otp,
                    'otp_expires_at' => now()->addMinutes(5),
                ]
            );

            Mail::raw("Your OTP for registration is: {$otp}", function ($message) use ($data) {
                $message->to($data['email'])->subject('Your Registration OTP');
            });

            return[
                'status' => true,
                'message' => 'OTP sent successfully to your email.',
                'verify_otp_endpoint' => route('verify.otp', ['id' => $otpRecord->id]),
            ];
        }catch (\ErrorException $e) {
                throw new \ErrorException($e->getMessage());
                return false;
        }

        
    }

    /**
     * Verify user OTP and complete registration.
     *
     * @param  array|string|int  $otp
     * @param  int  $id
     * @return array|false
    */
    public function verifyOtp( $otp, $id)
    {
        try{
            $user = Otp::find($id);
            $otpRecord = Otp::where('otpCode', $otp)
            ->where('email', $user->email)
            ->first();

            if (! $otpRecord) {
                return ['status' => false, 'message' => 'Invalid OTP.'];
            }

            if (now()->greaterThan($otpRecord->otp_expires_at)) {
                return ['status' => false, 'message' => 'OTP expired. Please register again.'];
            }

            $user = User::create([
                'name' => $otpRecord->name,
                'email' => $otpRecord->email,
                'password' => $otpRecord->password,
            ]);

            $otpRecord->delete();
            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'status' => true,
                'message' => 'User registered successfully.',
                'user' => $user,
                'token' => $token,
            ];
        }catch (Exception $e) {
            Log::error('Error in register method: ' . $e->getMessage());
            return false;
        }

    }

    
}