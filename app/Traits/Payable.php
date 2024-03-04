<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Http;

/**
 * Provides functionality for integrating various payment methods 
 * into a Laravel application, primarily using the Paystack API.
 *
 * @package App\Traits
 */
trait Payable
{
    /**
     * Paystack secret key, retrieved from configuration.
     *
     * @var string
     */
    protected $secretKey;

    /**
     * The base URL for the Paystack API endpoints.
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * Initializes the trait by setting the Paystack secret key
     * and the base URL for API interactions.
     */
    public function __construct()
    {
        $this->secretKey = config('paystack.secret_key');
        $this->baseUrl = 'https://api.paystack.co';
        // https://paystack.com/docs/api/transaction/#verify
    }

    /**
     * Initiates a mobile money payment using Paystack.
     *
     * @param  \App\Models\Order $order  The order object.
     * @param  string $phone             The customer's phone number.
     * @param  string $provider          The mobile money provider (e.g., 'mtn', 'vodafone').
     * @return void 
     * @throws \Exception                If the payment process fails.
     */
    public function payWithMomo($order, $phone, $provider)
    {
        $amount = $order->total;

        $payload = [
            'amount' => $amount,
            'email' => $order->customer->email,
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

                $order->payment()->create([
                    'reference' => $reference,
                    'type' => 'mobile_money',
                    'metadata' => [
                        'phone' => $phone,
                        'msisdn' => $provider,
                        'status' => $status,
                        'display_text' => $displayText,
                    ],
                ]);
            }
        } else {
            throw new Exception($response->object()->message);
        }
    }

    /**
     * Submits an OTP for a Paystack mobile money transaction.
     *
     * @param  mixed $params             An object containing the reference and OTP. 
     * @return object                    An object with 'status' and 'display_text' properties.
     * @throws \Exception                If the OTP verification fails.
     */
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

    /**
     * Generates a payment link for a mobile money transaction using Paystack.
     *
     * @param \App\Models\Order $order The order object.
     * @return void
     * @throws \Exception               If the payment link generation fails.
     */
    public function payViaLink($order)
    {
        $amount = $order->total;

        $payload = [
            'amount' => $amount,
            'email' => $order->customer->email,
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

                $order->payment()->create([
                    'reference' => $reference,
                    'type' => 'mobile_money',
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

    /**
     * Processes a cash payment. (Note: Consider adding more details here if the logic is more complex)
     *
     * @param \App\Models\Order $order  The order object.
     * @return void
     */
    public function payWithCash($order)
    {
        $payment = $order->payment()->create([
            'reference' => null,
            'type' => 'cash',
        ]);

        $payment->status()->transitionTo('Paid');
    }

    /**
     * Checks the status of a payment (especially useful for mobile money payments).
     *
     * @param \App\Models\Order $order  The order object.
     * @return void
     * @throws \Exception    If the status check fails.
     */
    public function checkStatus($order)
    {
        $payment = $order->payment;

        if ($payment->type === 'mobile_money') {
            if ($payment->status === 'Pending') {
                $reference = $payment->reference;

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
