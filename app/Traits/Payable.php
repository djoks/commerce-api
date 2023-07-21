<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Http;

trait Payable
{
    protected $secretKey;

    protected $baseUrl;

    public function __construct()
    {
        $this->secretKey = config('paystack.secret_key');
        $this->baseUrl = 'https://api.paystack.co';
        // https://paystack.com/docs/api/transaction/#verify
    }

    public function payWithMomo($invoice, $phone, $provider)
    {
        // $amount = $invoice->total;

        $payload = [
            'amount' => 10, // $amount,
            'email' => $invoice->client->email,
            'currency' => 'GHS',
            'mobile_money' => [
                'phone' => $phone,
                'provider' => $provider,
            ],
        ];

        $response = Http::withToken($this->secretKey)->withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/charge', $payload);

        if ($response->successful()) {
            $data = $response->object();
            if ($data->status) {
                // AirtelTigo and MTN will return status pay_offline
                // Vodafone will return status send_otp

                $status = $data->data->status; // success, pay_offline, send_otp
                $reference = $data->data->reference;
                $displayText = optional($data->data)->display_text;

                $invoice->payments()->create([
                    'transaction_id' => $reference,
                    'type' => 'momo',
                    'metadata' => [
                        'phone' => $phone,
                        'network' => $provider,
                        'status' => $status,
                        'display_text' => $displayText,
                    ],
                ]);
            }
        } else {
            throw new Exception($response->object()->message);
        }
    }

    public function submitOtp(mixed $params)
    {
        $reference = $params->reference;
        $otp = $params->otp;

        $payload = [
            'reference' => $reference,
            'otp' => $otp,
        ];

        $response = Http::withToken($this->secretKey)->withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/charge/submit_otp', $payload);

        logger($response);

        if ($response->ok()) {
            $data = $response->object();
            if ($data->status) {
                $status = $data->data->status;
                $message = optional($data)->message ?? 'New charge attempted';
                $displayText = optional($data->data)->display_text;

                return (object) [
                    'status' => $status,
                    'display_text' => $displayText ?? $message,
                ];
            }
            throw new Exception('Sorry, Payment failed!');
        } else {
            throw new Exception($response->object()->message);
            // throw new \Exception("Sorry, something went wrong");
        }
    }

    public function payViaLink($invoice)
    {
        $amount = $invoice->total;

        $payload = [
            'amount' => $amount,
            'email' => $invoice->client->email,
            'currency' => 'GHS',
            'channels' => ['mobile_money'],
        ];

        $response = Http::withToken($this->secretKey)->withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/transaction/initialize', $payload);

        if ($response->successful()) {
            $data = $response->object();
            if ($data->status) {
                $authorizationUrl = $data->data->authorization_url;
                $reference = $data->data->reference;
                $accessCode = $data->data->access_code;

                $invoice->payments()->create([
                    'transaction_id' => $reference,
                    'type' => 'momo',
                    'metadata' => [
                        'access_code' => $accessCode,
                        'url' => $authorizationUrl,
                    ],
                ]);
            }
        } else {
            throw new Exception($response->object()->message);
        }
    }

    public function payWithCash($invoice)
    {
        $payment = $invoice->payments()->create([
            'transaction_id' => null,
            'type' => 'cash',
        ]);
        $payment->status()->transitionTo('Paid');
    }

    public function checkStatus($invoice)
    {
        $payments = $invoice->payments;

        foreach ($payments as $payment) {
            if ($payment->type === 'momo') {
                if ($payment->status === 'Pending') {
                    $reference = $payment->transaction_id;

                    $response = Http::withToken($this->secretKey)->withHeaders([
                        'Content-Type' => 'application/json',
                    ])->get($this->baseUrl . '/charge/' . $reference);

                    // $this->baseUrl . '/transaction/verify/' . $reference

                    if ($response->successful()) {
                        $data = $response->object();
                        if ($data->status) {
                            $status = $data->data->status;

                            if ($status === 'success') {
                                $payment->status()->transitionTo('Paid');
                            }
                        }
                    } else {
                        throw new Exception($response->object()->message);
                    }
                }
            }
        }
    }
}
