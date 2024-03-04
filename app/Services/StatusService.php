<?php

namespace App\Services;

/**
 * Provides services related to managing statuses for products and payments.
 */
class StatusService
{
    /**
     * Retrieves possible product statuses and their allowed transitions.
     *
     * @return array An associative array of product statuses and their transitions.
     */
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

    /**
     * Retrieves possible payment statuses and their allowed transitions.
     *
     * @return array An associative array of payment statuses and their transitions.
     */
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
