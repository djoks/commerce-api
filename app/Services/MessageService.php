<?php

namespace App\Services;

use App\Mail\GenericMail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MessageService
{
    public function sendSms($to, $message)
    {
        if (app()->environment(['local', 'staging', 'testing'])) {
            Log::channel('stack')->info($message);

            return;
        }

        $url = 'https://sms.textcus.com/api/send';
        $queryParams = [
            'apikey' => config('textcus.api_key'),
            'dlr' => '0',
            'type' => '0',
            'destination' => $this->formatPhone($to),
            'source' => 'BreakInvent',
            'message' => $message,
        ];

        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->get($url, $queryParams);

        if ($response->ok()) {
            logger('SMS sent successfully.');
        } else {
            logger('SMS failed to send.' . $response->body());
        }
    }

    public function sendEmail($to, $message, $subject)
    {
        if (app()->environment(['local', 'staging', 'testing'])) {
            Log::channel('stack')->info($message);

            return;
        }

        Mail::send(new GenericMail($to, $message, $subject));
    }

    public function send($phone, $email, $message, $subject = 'New Message')
    {
        if (!is_null($phone)) {
            $this->sendSms($phone, $message);
        }

        if (!is_null($email)) {
            $this->sendEmail($email, $message, $subject);
        }
    }

    private function formatPhone($number)
    {
        if (substr($number, 0, 1) === '0') {
            $number = '+233' . substr($number, 1);
        }

        return $number;
    }
}
