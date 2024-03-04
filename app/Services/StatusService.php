<?php

namespace App\Services;

class StatusService
{
    public function getProductStatuses()
    {
        return [
            'Available' => [
                'Sold',
                'Out of Stock',
                'Discontinued',
                'Reserved',
            ],
            'Sold' => [],
            'On Order' => [
                'Available',
            ],
            'Out of Stock' => [
                'On Order',
                'Discontinued',
            ],
            'Discontinued' => [
                'Available',
            ],
        ];
    }

    public function getPaymentStatuses()
    {
        return [
            'Pending' => [
                'Paid',
                'Failed',
                'Partially Paid',
            ],
            'Paid' => [
                'Refunded',
                'Voided',
                'Disputed',
                'Settled',
            ],
            'Failed' => [
                'Voided',
                'Disputed',
            ],
            'Refunded' => [
                'Disputed',
            ],
            'Partially Paid' => [
                'Paid',
                'Voided',
                'Disputed',
            ],
            'Overdue' => [
                'Partially Paid',
                'Paid',
            ],
            'Voided' => [
                'Disputed',
            ],
            'Disputed' => [
                'Voided',
                'Partially Paid',
                'Paid',
                'Refunded',
                'Failed',
            ],
            'Settled' => [
                'Paid',
            ],
        ];
    }
}
