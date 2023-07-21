<?php

namespace App\Traits;

use App\Models\Order;
use Illuminate\Support\Carbon;
use App\Models\OtpVerification;

trait Utils
{
    public function getRandomNumbers(int $count): string
    {
        $numbers = range(0, 9);
        shuffle($numbers);

        return implode('', array_slice($numbers, 0, $count));
    }

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

    public function generateGhanaianPhoneNumber(): string
    {
        // Generate a random Ghanaian phone number
        $prefixes = ['024', '054', '055', '059'];
        $prefix = $prefixes[array_rand($prefixes)];
        $number = mt_rand(1000000, 9999999);

        return $prefix . $number;
    }

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
