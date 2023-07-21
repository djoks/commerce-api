<?php

namespace App\Services;

class StatusService
{
    public function getEquipmentStockStatuses()
    {
        return [
            'Available' => [
                'Leased',
                'Sold',
                'Under Maintenance',
                'Out of Stock',
                'Discontinued',
                'Faulty',
                'In Transit',
                'Reserved',
            ],
            'Leased' => [
                'Under Maintenance',
                'Faulty',
                'In Transit',
                'Available',
            ],
            'Sold' => [
                'Under Maintenance',
                'Faulty',
            ],
            'On Order' => [
                'In Transit',
                'Available',
            ],
            'Under Maintenance' => [
                'Available',
            ],
            'Out of Stock' => [
                'On Order',
                'Discontinued',
            ],
            'Discontinued' => [
                'Available',
            ],
            'In Transit' => [
                'Available',
            ],
            'Reserved' => [
                'Available',
                'Out Of Stock',
            ],
            'Faulty' => [
                'Under Maintenance',
                'Discontinued',
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

    public function getEquipmentTransferStatuses()
    {
        return [
            'Requested' => [
                'Approved',
                'On Hold',
                'Canceled',
            ],
            'Approved' => [
                'In Transit',
            ],
            'In Transit' => [
                'Delivered',
                'Rejected',
                'Failed',
            ],
            'Delivered' => [
                'Completed',
            ],
            'Rejected' => [
                'Failed',
            ],
            'Pending Confirmation' => [
                'Delivered',
                'Rejected',
            ],
            'Completed' => [],
            'Canceled' => [],
            'On Hold' => [
                'In Transit',
                'Rejected',
            ],
            'Failed' => [],
        ];
    }

    public function getLeaseStatuses()
    {
        return [
            'Active' => [
                'Terminated',
                'Renewed',
                'Suspended',
                'Transferred',
            ],
            'Expired' => [
                'Cancelled',
                'Terminated',
            ],
            'Terminated' => [
                'Active',
            ],
            'Renewed' => [
                'Active',
            ],
            'Pending Approval' => [
                'Active',
                'Renewed',
            ],
            'Draft' => [
                'Pending Approval',
            ],
            'Suspended' => [
                'Renewed',
                'Active',
                'Cancelled',
                'Terminated',
            ],
            'Cancelled' => [],
            'Transferred' => [
                'Active',
                'Renewed',
            ],

        ];
    }
}
