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

/**
 * Provides authentication services including registration, verification, login, and logout functionalities.
 */
class AuthService
{
    /**
     * Registers a new user with provided credentials.
     *
     * @param mixed $payload Data necessary for registering a new user.
     * @return ApiResponse Returns ApiResponse with user details on success or error message on failure.
     */
    public function register(mixed $payload)
    {
        try {
            $payload->password = Hash::make($payload->password);

            $user = User::create((array) $payload);
            $user->assignRole('customer');
            $user->notify(new AccountCreated($user));

            return new ApiResponse('Your account has been created successfully. An OTP has been sent to your mail, please complete the verification process.', 200, [
                'user' => UserResource::make($user)
            ]);
        } catch (Exception $e) {
            logger($e->getMessage());
            return new ApiResponse('Sorry, unable to create a new account.', 500);
        }
    }

    /**
     * Verifies a user's account using an OTP sent to their email.
     *
     * @param mixed $payload Data including the email and OTP for account verification.
     * @return ApiResponse Returns ApiResponse on success or failure of account verification.
     */
    public function verify(mixed $payload)
    {
        try {
            $otp = OtpVerification::where('email', $payload->email)
                ->where('otp', $payload->otp)
                ->where('expires_at', '>', now())
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

    /**
     * Authenticates a user and generates an access token.
     *
     * @param object $payload Login credentials including email/phone and password.
     * @return ApiResponse Returns ApiResponse with login token and user details on success, or error message on failure.
     */
    public function login(object $payload)
    {
        try {
            $emailOrPhone = $payload->email ?? $payload->phone;
            $user = User::where('email', $emailOrPhone)
                ->orWhere('phone', $emailOrPhone)
                ->first();

            if (!$user) {
                // User not found?
                return new ApiResponse('Sorry, the login credentials are invalid.', 404);
            }

            if (!Hash::check($payload->password, $user->password)) {
                // Incorrect password
                return new ApiResponse('Sorry, the login credentials are invalid.', 401);
            }

            $token = $user->createToken($user->email)->plainTextToken;
            $data = [
                'token' => $token,
                'user' => UserResource::make($user),
            ];

            return new ApiResponse('You logged in successfully.', 200, $data);
        } catch (Exception $e) {
            return new ApiResponse($e->getMessage(), 500);
        }
    }

    /**
     * Logs out the current authenticated user by revoking their access token.
     *
     * @return ApiResponse Returns ApiResponse indicating the success of the logout operation.
     */
    public function logout()
    {
        try {
            // @phpstan-ignore-next-line
            auth()->user()->currentAccessToken()->delete();

            return new ApiResponse('You logged out successfully.', 200);
        } catch (Exception $e) {
            return new ApiResponse($e->getMessage(), $e->getCode());
        }
    }
}
