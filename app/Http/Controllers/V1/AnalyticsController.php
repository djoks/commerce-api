<?php

namespace App\Http\Controllers\V1;

use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class AnalyticsController extends BaseController
{
    protected $service;

    public function __construct(AnalyticsService $service)
    {
        $this->service = $service;
    }

    public function stockStats(Request $request)
    {
        $response = $this->service->getStockStats($request->_branch_id);

        return $this->apiResponse($response);
    }

    public function weeklyMonthlyStats(Request $request)
    {
        $response = $this->service->getWeeklyMonthlyStats($request->_branch_id);

        return $this->apiResponse($response);
    }

    public function quarterRevenueExpenseStats(Request $request)
    {
        $response = $this->service->getQuarterRevenueExpenseStats($request->_branch_id);

        return $this->apiResponse($response);
    }
}
