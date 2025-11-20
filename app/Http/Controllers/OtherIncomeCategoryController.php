<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OtherIncomeCategory;
use App\Models\Account;
use App\Http\Resources\AccountResource;
class OtherIncomeCategoryController extends Controller
{
    public function index()
    {

        abort_if(!auth()->user()->hasPermission('other_income_categories_menu'), 403);

        if(request()->wantsJson()) {
            $otherIncomeCategories = OtherIncomeCategory::orderBy('id')->paginate();
            return response()->json([
                'data' => $otherIncomeCategories->items(),
                'meta' => [
                    'current_page' => $otherIncomeCategories->currentPage(),
                    'last_page' => $otherIncomeCategories->lastPage(),
                    'per_page' => $otherIncomeCategories->perPage(),
                    'total' => $otherIncomeCategories->total(),
                ],
            ]);
        }
        $accounts = AccountResource::collection(Account::query()
            ->select('id', 'name_ar', 'name_en')
            ->where('level', 3)
            ->orderBy(app()->getLocale() == 'ar' ? 'name_ar' : 'name')
            ->get());
        return view('pages.other-income-categories.index', compact('accounts'));
    }
    public function store(Request $request)
    {

        if(!auth()->user()->hasPermission('other_income_categories_create')) {
            return response()->json(['message' => 'You are not authorized to create other income categories'], 403);
        }
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'income_account_id' => 'required|exists:accounts,id',
            'expense_account_id' => 'required|exists:accounts,id',
            'cash_account_id' => 'required|exists:accounts,id',
            'knet_account_id' => 'required|exists:accounts,id',
            'bank_charges_account_id' => 'required|exists:accounts,id',
        ]);

        $validatedData['created_by'] = auth()->id();

        $otherIncomeCategory = OtherIncomeCategory::create($validatedData);
        return response()->json(['data' => $otherIncomeCategory]);
    }

    public function update(Request $request, OtherIncomeCategory $otherIncomeCategory)
    {
        if(!auth()->user()->hasPermission('other_income_categories_edit')) {
            return response()->json(['message' => 'You are not authorized to edit other income categories'], 403);
        }
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'income_account_id' => 'required|exists:accounts,id',
            'expense_account_id' => 'required|exists:accounts,id',
            'cash_account_id' => 'required|exists:accounts,id',
            'knet_account_id' => 'required|exists:accounts,id',
            'bank_charges_account_id' => 'required|exists:accounts,id',
        ]);
        $otherIncomeCategory->update($validatedData);
        return response()->json(['data' => $otherIncomeCategory]);
    }

    public function destroy(OtherIncomeCategory $otherIncomeCategory)
    {
        if(!auth()->user()->hasPermission('other_income_categories_delete')) {
            return response()->json(['message' => 'You are not authorized to delete other income categories'], 403);
        }
        $otherIncomeCategory->delete();
        return response()->json(['message' => 'Other income category deleted successfully']);
    }

}
