<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\VoucherDetail;
use App\Models\Account;
use App\Models\CostCenter;
use App\Models\User;
use App\Http\Resources\VoucherResource;
use App\Http\Resources\VoucherDetailResource;
use App\Http\Resources\AccountResource;
use App\Http\Resources\CostCenterResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use App\Services\ActionsLog;

class VoucherController extends Controller
{
    public function index(Request $request)
    {

        abort_if(!auth()->user()->hasPermission('journal_vouchers_view'), 403);

        if($request->wantsJson()) {
            $vouchers = Voucher::query()
                ->with('user')
                ->withSum('voucherDetails', 'debit')
                ->withCount('attachments')
                ->when(auth()->id() != 1, function (Builder $q) {
                    $q->where('type', 'jv');
                })
                ->when($request->search, function (Builder $q) use ($request) {
                    $q->where(function (Builder $q) use ($request) {
                        $q->where('id', $request->search);
                        $q->orWhere('manual_id', $request->search);
                        $q->orWhere('notes', 'like', '%' . $request->search . '%');
                        $q->orWhereRelation('voucherDetails', 'narration', 'like', '%' . $request->search . '%');
                    });
                })
                ->when($request->start_date, function (Builder $q) use ($request) {
                    $q->whereDate('date', '>=', $request->start_date);
                })
                ->when($request->end_date, function (Builder $q) use ($request) {
                    $q->whereDate('date', '<=', $request->end_date);
                })
                ->orderBy('id', 'desc')
                ->paginate(15);

                // dd($vouchers);
            return VoucherResource::collection($vouchers);
        }
        $cost_centers = CostCenter::all();
        $accounts = Account::where('level', 3)->get();
        $users = User::all();
        return view('pages.vouchers.index',[
            'accounts' => AccountResource::collection($accounts), 
            'cost_centers' => CostCenterResource::collection($cost_centers),
            'users' => UserResource::collection($users)
        ]);
    }

    public function store(Request $request)
    {

        // check if auth user has permission to create voucher
        abort_if(!auth()->user()->hasPermission('journal_vouchers_create'), 403);

        $validated_voucher_data = $request->validate([
            'manual_id' => 'nullable|string|max:255',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated_voucher_data['created_by'] = auth()->user()->id;
        $validated_voucher_data['type'] = 'jv';

        $validated_voucher_details_data = $request->validate([
            'details' => 'required|array',
            'details.*.account_id' => 'required|exists:accounts,id',
            'details.*.cost_center_id' => 'nullable|exists:cost_centers,id',
            'details.*.user_id' => 'nullable|exists:users,id',
            'details.*.debit' => 'required|numeric|min:0',
            'details.*.credit' => 'required|numeric|min:0',
            'details.*.narration' => 'nullable|string',
        ]);

        // get total debit and credit
        $total_debit = 0;
        $total_credit = 0;

        foreach($validated_voucher_details_data['details'] as $detail) {

            // check that only one of debit or credit is greater than 0
            if($detail['debit'] == 0 && $detail['credit'] == 0) {
                return response()->json(['error' => 'one of debit or credit must be greater than 0'], 400);
            }
            // check that only one of debit or credit is greater than 0
            if($detail['debit'] > 0 && $detail['credit'] > 0) {
                return response()->json(['error' => 'one of debit or credit must be greater than 0'], 400);
            }

            // add to total debit and credit
            $total_debit += (float)$detail['debit'];
            $total_credit += (float)$detail['credit'];
        }

        // check if total debit and credit are not 0
        if($total_debit == 0 || $total_credit == 0) {
            return response()->json(['error' => 'total debit and credit must be greater than 0'], 400);
        }

        // Format numbers to 3 decimal places for comparison
        $total_debit = round($total_debit, 3);
        $total_credit = round($total_credit, 3);

        // check if total debit is not equal to total credit
        if(abs($total_debit - $total_credit) > 0.001) {
            return response()->json(['error' => 'total debit must be equal to total credit'], 400);
        }


        DB::beginTransaction();
        try {
            $voucher = Voucher::create($validated_voucher_data);
            $voucher->voucherDetails()->createMany($validated_voucher_details_data['details']);
            DB::commit();
            ActionsLog::logAction('Voucher', 'Create', $voucher->id, 'Voucher created successfully', $voucher->load('voucherDetails')->toArray());
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return new VoucherResource($voucher->loadSum('voucherDetails', 'debit')->load('user'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        // check if auth user has permission to update voucher
        abort_if(!auth()->user()->hasPermission('journal_vouchers_edit'), 403);

        $validated_voucher_data = $request->validate([
            'manual_id' => 'nullable|string|max:255',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated_voucher_details_data = $request->validate([
            'details' => 'required|array',
            'details.*.account_id' => 'required|exists:accounts,id',
            'details.*.cost_center_id' => 'nullable|exists:cost_centers,id',
            'details.*.user_id' => 'nullable|exists:users,id',
            'details.*.debit' => 'required|numeric|min:0',
            'details.*.credit' => 'required|numeric|min:0',
            'details.*.narration' => 'nullable|string',
        ]);

        // get total debit and credit
        $total_debit = 0;
        $total_credit = 0;

        foreach($validated_voucher_details_data['details'] as $detail) {

            // check that only one of debit or credit is greater than 0
            if($detail['debit'] == 0 && $detail['credit'] == 0) {
                return response()->json(['error' => 'one of debit or credit must be greater than 0'], 400);
            }
            // check that only one of debit or credit is greater than 0
            if($detail['debit'] > 0 && $detail['credit'] > 0) {
                return response()->json(['error' => 'one of debit or credit must be greater than 0'], 400);
            }

            // add to total debit and credit
            $total_debit += (float)$detail['debit'];
            $total_credit += (float)$detail['credit'];
        }

        // check if total debit and credit are not 0
        if($total_debit == 0 || $total_credit == 0) {
            return response()->json(['error' => 'total debit and credit must be greater than 0'], 400);
        }

        // Format numbers to 3 decimal places for comparison
        $total_debit = round($total_debit, 3);
        $total_credit = round($total_credit, 3);

        // check if total debit is not equal to total credit
        if(abs($total_debit - $total_credit) > 0.001) {
            return response()->json(['error' => 'total debit must be equal to total credit'], 400);
        }

        // save voucher and voucher details before update
        $old_voucher = $voucher->load('voucherDetails')->toArray();
        DB::beginTransaction();
        try {
            $voucher->update($validated_voucher_data);
            $voucher->voucherDetails()->forceDelete();
            $voucher->voucherDetails()->createMany($validated_voucher_details_data['details']);
            DB::commit();
            ActionsLog::logAction('Voucher', 'Update', $voucher->id, 'Voucher updated successfully', $voucher->load('voucherDetails')->toArray(), $old_voucher);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return new VoucherResource($voucher->loadSum('voucherDetails', 'debit')->load('user')->loadCount('attachments'));
    }

    public function getVoucherDetails(Voucher $voucher)
    {
        $voucher_details = VoucherDetail::query()
            ->where('voucher_id', $voucher->id)
            ->get();
        return VoucherDetailResource::collection($voucher_details);
    }

    public function destroy(Voucher $voucher)
    {
        // check if auth user has permission to delete voucher
        if(!auth()->user()->hasPermission('journal_vouchers_delete')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // check if voucher type is jv
        if($voucher->type->value != 'jv') {
            return response()->json(['error' => 'Journal vouchers can only be deleted via journal'], 400);
        }

        // check if voucher has attachments
        if($voucher->attachments->count() > 0) {
            return response()->json(['error' => 'Voucher has attachments'], 400);
        }

        $old_voucher = $voucher->load('voucherDetails')->toArray();
        DB::beginTransaction();
        try {
            $voucher->voucherDetails()->forceDelete();
            $voucher->attachments()->forceDelete();
            $voucher->forceDelete();
            DB::commit();
            ActionsLog::logAction('Voucher', 'Delete', $old_voucher['id'], 'Voucher deleted successfully',[], $old_voucher);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json(['message' => 'Voucher deleted successfully']);
    }
}
