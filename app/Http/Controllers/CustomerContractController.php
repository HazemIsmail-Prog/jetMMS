<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\Customer;
use App\Http\Resources\CustomerContractResource;
use App\Services\ActionsLog;

class CustomerContractController extends Controller
{
    public function createCustomerContract(Request $request, Customer $customer)
    {
        // check if user has permission to create customer contract
        if(!auth()->user()->hasPermission('contracts_create')) {
            return response()->json(['error' => __('messages.you_dont_have_permission_to_create_customer_contract')], 403);
        }

        $validatedContractData = $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'contract_type' => 'required|string|in:subscription,construction',
            'contract_date' => 'required|date',
            'contract_duration' => 'required|integer',
            'contract_value' => 'required|numeric',
            'contract_number' => 'required|string|max:255',
            'building_type' => 'required|string|in:residential,commercial',
            'units_count' => 'nullable|integer',
            'central_count' => 'nullable|integer',
            'collected_amount' => 'nullable|numeric',
            'notes' => 'nullable|string|max:255',
            'sp_included' => 'nullable|boolean',
            'active' => 'nullable|boolean',
            'contract_expiration_date' => 'nullable|date',
        ]);

        $validatedContractData['user_id'] = auth()->id();
        $validatedContractData['customer_id'] = $customer->id;


        $contract = Contract::create($validatedContractData);
        ActionsLog::logAction('Customer', 'Contract Created', $customer->id, 'Customer contract created successfully', $contract->toArray());
        return new CustomerContractResource($contract);

    }
}
