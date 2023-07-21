<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Models\ApiResponse;
use App\Models\OtpVerification;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Notifications\AccountCreated;
use App\Notifications\AccountVerified;

class AuthService
{
    public function register(mixed $payload)
    {
        try {
            $payload->password = Hash::make($payload->password);

            $user = User::create((array) $payload);
            $user->assignRole('customer');
            $user->notify(new AccountCreated($user));

            return new ApiResponse('Your account has been created successfully.', 200, [
                'user' => UserResource::make($user)
            ]);
        } catch (Exception $e) {
            logger($e->getMessage());
            return new ApiResponse('Sorry, unable to create a new account.', 500);
        }
    }

    public function verify(mixed $payload)
    {
        try {
            $otp = OtpVerification::where('email', $payload->email)
                ->where('otp', $payload->otp)
                ->where('expires_at', '<', now())
                ->first();

            if (!$otp) {
                return new ApiResponse('Your OTP is invalid.  Please try again.', 422);
            }

            $user = User::where('email', $otp->email)->first();
            $user->email_verified_at = now();
            $user->save();

            $otp->delete();
            $user->notify(new AccountVerified($user));

            return new ApiResponse('Your account has been verified successfully.', 200, [
                'user' => UserResource::make($user)
            ]);
        } catch (Exception $e) {
            logger($e->getMessage());
            return new ApiResponse('Sorry, unable to verify your account.', 500);
        }
    }

    public function login(object $payload)
    {
        try {
            $emailOrPhone = $payload->user;
            $user = User::where('email', $emailOrPhone)
                ->orWhere('phone', $emailOrPhone)
                ->first();

            if (!$user) {
                // User not found?
                return new ApiResponse('Sorry, this user is invalid', 404);
            }
            if (!Hash::check($payload->password, $user->password)) {
                // Incorrect password
                return new ApiResponse('Sorry, the credentials is invalid', 401);
            }
            $token = $user->createToken($user->email)->plainTextToken;
            $data = [
                'token' => $token,
                'user' => UserResource::make($user),
            ];

            return new ApiResponse('Login success', 200, $data);
        } catch (Exception $e) {
            return new ApiResponse($e->getMessage(), $e->getCode());
        }
    }

    public function logout()
    {
        try {
            // @phpstan-ignore-next-line
            auth()->user()->currentAccessToken()->delete();

            return new ApiResponse('Logout success', 200);
        } catch (Exception $e) {
            return new ApiResponse($e->getMessage(), $e->getCode());
        }
    }
}
