<?php

namespace App\Services;

use App\Models\ApiResponse;
use App\Models\Client;
use App\Models\Equipment;
use App\Models\EquipmentStock;
use App\Models\Invoice;
use App\Models\Lease;

class AnalyticsService
{
    public function getStockStats(?int $branchId = null)
    {
        $stocks = EquipmentStock::ofBranchLocal($branchId);

        $data = [
            'total' => $stocks->count(),
            'sold' => $stocks->whereStatus('Sold')->count(),
            'leased' => $stocks->whereStatus('Leased')->count(),
        ];

        return new ApiResponse(
            'Stock stats',
            200,
            $data
        );
    }

    public function getWeeklyMonthlyStats(?int $branchId = null)
    {
        $zero = ['week' => ['value' => 0, 'change' => 0], 'month' => ['value' => 0, 'change' => 0]];

        $invoice = Invoice::ofBranch($branchId);
        $outstandingLeases = Lease::where('ends_at', '<', now()->toDateString());
        $clientsAcquired = Client::query();
        // @phpstan-ignore-next-line
        $noStockEquipment = Equipment::whereDoesntHave('stocks', function ($query) {
            $query->where('status', 'Available');
        });

        $data = [
            'total_revenue' => $this->query($invoice->ofStatus('Paid'), 'total'),
            'total_expenses' => $zero,
            'outstanding_leases' => $this->query($outstandingLeases),
            'clients_acquired' => $this->query($clientsAcquired),
            'renewed_leases' => $zero,
            'support_tickets' => $zero,
            'out_of_stock_equipment' => $this->query($noStockEquipment),
            'pending_supply_order' => $zero,
            'delayed_supply_order' => $zero,
        ];

        return new ApiResponse(
            'Weekly/Monthly stats',
            200,
            $data
        );
    }

    public function getQuarterRevenueExpenseStats(?int $branchId = null)
    {
        $data = [];
        $invoice = Invoice::ofBranch($branchId);

        foreach (range(1, 4) as $i) {
            $data[] = [
                'month' => $i,
                'sales' => $this->quarterQuery($invoice->ofStatus('Paid'), $i),
                'expenses' => 0,
            ];
        }

        return new ApiResponse(
            'Quarter revenue expense stats',
            200,
            $data
        );
    }

    private function quarterQuery($query, int $quarter = 1)
    {
        $start = now()->firstOfQuarter()->addMonths($quarter * 3);
        $end = $start->copy()->addMonths(2);

        return $query->whereBetween('created_at', [$start, $end])->sum('total');
    }

    private function query($query, ?string $sumKey = null)
    {
        $currentDate = now();
        $previousWeekStartDate = $currentDate->copy()->subWeek()->startOfWeek();
        $previousWeekEndDate = $currentDate->copy()->subWeek()->endOfWeek();
        $previousMonthStartDate = $currentDate->copy()->subMonth()->startOfMonth();
        $previousMonthEndDate = $currentDate->copy()->subMonth()->endOfMonth();

        $startOfWeek = $currentDate->copy()->startOfWeek();
        $endOfWeek = $currentDate->copy()->endOfWeek();
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();

        $weekQuery = clone $query;
        $weekValue = $weekQuery->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
        $weekValue = is_null($sumKey) ? $weekValue->count() : $weekValue->sum($sumKey);

        $monthQuery = clone $query;
        $monthValue = $monthQuery->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
        $monthValue = is_null($sumKey) ? $monthValue->count() : $monthValue->sum($sumKey);

        $prevWeekQuery = clone $query;
        $previousWeekValue = $prevWeekQuery->whereBetween('created_at', [$previousWeekStartDate, $previousWeekEndDate]);
        $previousWeekValue = is_null($sumKey) ? $previousWeekValue->count() : $previousWeekValue->sum($sumKey);

        $prevMonthQuery = clone $query;
        $previousMonthValue = $prevMonthQuery->whereBetween('created_at', [$previousMonthStartDate, $previousMonthEndDate]);
        $previousMonthValue = is_null($sumKey) ? $previousMonthValue->count() : $previousMonthValue->sum($sumKey);

        $weekChangePercentage = $previousWeekValue ? ($weekValue - $previousWeekValue) / $previousWeekValue * 100 : 0;
        $monthChangePercentage = $previousMonthValue ? ($monthValue - $previousMonthValue) / $previousMonthValue * 100 : 0;

        return [
            'week' => [
                'value' => (int) $weekValue,
                'change' => $weekChangePercentage,
            ],
            'month' => [
                'value' => (int) $monthValue,
                'change' => $monthChangePercentage,
            ],
        ];
    }
}
