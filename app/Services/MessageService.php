<?php

namespace App\Services;

use App\Mail\GenericMail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Service for sending messages, including SMS and email, depending on the environment and provided contact information.
 */
class MessageService
{
    /**
     * Sends an SMS message to the specified recipient.
     *
     * @param string $to The recipient's phone number.
     * @param string $message The message content.
     * @return void Logs the message in non-production environments or sends an SMS in production.
     */
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

    /**
     * Sends an email to the specified recipient.
     *
     * @param string $to The recipient's email address.
     * @param string $message The message content.
     * @param string $subject The subject of the email.
     * @return void Logs the message in non-production environments or sends an email in production.
     */
    public function sendEmail($to, $message, $subject)
    {
        if (app()->environment(['local', 'staging', 'testing'])) {
            Log::channel('stack')->info($message);

            return;
        }

        Mail::send(new GenericMail($to, $message, $subject));
    }

    /**
     * Sends a message either via SMS or email based on the provided contact information.
     *
     * @param string|null $phone The recipient's phone number for SMS.
     * @param string|null $email The recipient's email address for email.
     * @param string $message The message content.
     * @param string $subject The subject of the email (optional, defaults to 'New Message').
     * @return void Directs to either sendSms or sendEmail methods based on provided information.
     */
    public function send($phone, $email, $message, $subject = 'New Message')
    {
        if (!is_null($phone)) {
            $this->sendSms($phone, $message);
        }

        if (!is_null($email)) {
            $this->sendEmail($email, $message, $subject);
        }
    }

    /**
     * Formats a given phone number to an international format.
     *
     * @param string $number The phone number to format.
     * @return string The formatted phone number.
     */
    private function formatPhone($number)
    {
        if (substr($number, 0, 1) === '0') {
            $number = '+233' . substr($number, 1);
        }

        return $number;
    }
}
