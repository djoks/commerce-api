<?php

namespace App\Http\Controllers\V1;

use App\Services\OrderService;

/**
 * Handles order-related operations within the e-commerce API.
 * Provides functionality to list all orders, view specific orders by ID,
 * and list orders belonging to the currently authenticated user.
 */
class OrderController extends BaseController
{
    /**
     * @var OrderService The service responsible for handling order operations.
     */
    protected OrderService $service;

    /**
     * Initializes a new instance of the OrderController class.
     *
     * @param OrderService $service Injected service for managing orders.
     */
    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    /**
     * Retrieves a list of all orders.
     *
     * @return \Illuminate\Http\Response Returns the API response with a list of all orders.
     */
    public function index()
    {
        return $this->service->get();
    }

    /**
     * Displays details of a specific order identified by its unique ID.
     *
     * @param string $id The unique identifier of the order.
     * @return \Illuminate\Http\Response Returns the API response with the details of the specified order.
     */
    public function show(string $id)
    {
        // Check payment status before displaying the order
        $this->service->checkPaymentStatus($id);

        $response = $this->service->findOne($id);

        return $this->apiResponse($response);
    }

    /**
     * Retrieves orders belonging to the currently authenticated user.
     *
     * @return \Illuminate\Http\Response Returns the API response with orders of the currently authenticated user.
     */
    public function myOrders()
    {
        return $this->service->getMyOrders(request()->for);
    }
}
