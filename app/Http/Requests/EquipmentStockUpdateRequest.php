<?php

namespace App\Http\Requests;

use App\Models\EquipmentStock;
use App\Rules\CheckStatusTransition;
use App\Services\StatusService;
use Illuminate\Foundation\Http\FormRequest;

class EquipmentStockUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $transitions = (new StatusService())->getEquipmentStockStatuses();
        $currentStatus = EquipmentStock::find($this->equipment_stock)->status ?? null;

        return [
            'quantity' => 'nullable|integer|min:1',
            'status' => ['required', 'string', new CheckStatusTransition($transitions, $currentStatus)],
        ];
    }
}
