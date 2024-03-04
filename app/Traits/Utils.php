<?php

namespace App\Traits;

use App\Models\Order;
use Illuminate\Support\Carbon;
use App\Models\OtpVerification;

/**
 * Provides utility methods for various operations like generating random numbers, order numbers, 
 * Ghanaian phone numbers, and OTPs.
 */
trait Utils
{
    /**
     * Generates a string of random numbers of a specified length.
     *
     * @param int $count The length of the random number string to generate.
     * @return string A string of random numbers.
     */
    public function getRandomNumbers(int $count): string
    {
        $numbers = range(0, 9);
        shuffle($numbers);

        return implode('', array_slice($numbers, 0, $count));
    }

    /**
     * Generates a unique order number based on the latest order and the current year.
     *
     * @return string A unique order number in the format INV-YYYY-XXXX.
     */
    public function generateOrderNo(): string
    {
        // Format: INV-2021-0001
        $year = date('Y');
        $lastOrder = Order::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
        $lastOrderNo = $lastOrder ? $lastOrder->code : 'INV-' . $year . '-0000';
        $lastOrderNo = explode('-', $lastOrderNo);
        $lastOrderNo = intval(end($lastOrderNo));
        $lastOrderNo++;
        $lastOrderNo = str_pad('' . $lastOrderNo, 4, '0', STR_PAD_LEFT);

        return 'INV-' . $year . '-' . $lastOrderNo;
    }

    /**
     * Generates a random Ghanaian phone number using common prefixes.
     *
     * @return string A random Ghanaian phone number.
     */
    public function generateGhanaianPhoneNumber(): string
    {
        // Generate a random Ghanaian phone number
        $prefixes = ['024', '054', '055', '059'];
        $prefix = $prefixes[array_rand($prefixes)];
        $number = mt_rand(1000000, 9999999);

        return $prefix . $number;
    }

    /**
     * Generates a One-Time Password (OTP) of a specified length, saves it with an expiration time, and returns it.
     *
     * @param string $email The email address to associate with the OTP.
     * @param int $length The length of the OTP. This parameter is set but not used in the current implementation.
     * @return string A 6-digit OTP.
     */
    public function generateOtp(string $email, int $length): string
    {
        // Generate a 6-digit random OTP
        $otp = str_pad(strval(mt_rand(100000, 999999)), 6, '0', STR_PAD_LEFT);

        // Save the OTP in the database
        OtpVerification::create([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(30), // OTP will expire after 10 minutes
        ]);


        return $otp;
    }
}
