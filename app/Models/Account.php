<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    protected $guarded = [];

    const RECEIVABLE_ACCOUNTS_PARENT_ID = 21;

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function child_accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'account_id')
            ->with('child_accounts');
    }

    public function voucher_details(): HasMany
    {
        return $this->hasMany(VoucherDetail::class);
    }

    public function getBalanceAttribute()
    {
        // Get all descendant accounts including itself
        $accounts = [];
        switch ($this->level) {

            case 0:
                foreach ($this->child_accounts as $level1Account) {
                    foreach ($level1Account->child_accounts as $level2Account) {
                        foreach ($level2Account->child_accounts as $level3Account) {
                            $accounts[] = $level3Account->id;
                        }
                    }
                }
                break;

            case 1:
                foreach ($this->child_accounts as $level2Account) {
                    foreach ($level2Account->child_accounts as $level3Account) {
                        $accounts[] = $level3Account->id;
                    }
                }
                break;

            case 2:
                foreach ($this->child_accounts as $level3Account) {
                    $accounts[] = $level3Account->id;
                }
                break;

            case 3:
                $accounts[] = $this->id;
                break;

        }

        // Calculate sum of debit and credit for the account and its descendants
        $balance = VoucherDetail::whereIn('account_id', $accounts)
            ->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
            ->first();

            return $this->type = 'debit' ? $balance->total_debit - $balance->total_credit : $balance->total_credit -  $balance->total_debit ;
    }

    public function getNameAttribute()
    {
        if (app()->getLocale() == 'ar') {
            return $this->name_ar ?? $this->name_en;
        } else {
            return $this->name_en ?? $this->name_ar;
        }
    }

    // Formatters

    public function getFormatedDebitSumAttribute()
    {
        return $this->voucher_details->sum('debit') == 0 ? '-' : number_format($this->voucher_details->sum('debit'), 3);
    }

    public function getFormatedCreditSumAttribute()
    {
        return $this->voucher_details->sum('credit') == 0 ? '-' : number_format($this->voucher_details->sum('credit'), 3);
    }
}
