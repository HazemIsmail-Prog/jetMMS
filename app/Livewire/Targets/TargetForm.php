<?php

namespace App\Livewire\Targets;

use App\Models\Department;
use App\Models\Target;
use App\Models\Title;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TargetForm extends Component
{

    public $department_id = [];
    public $month;
    public $year;
    public $targets = [];

    public function rules()
    {
        return [
            'targets.*.invoices_target' => 'required_with:targets.*.amount_target',
            'targets.*.amount_target' => 'required_with:targets.*.invoices_target',
        ];
    }

    public function mount()
    {
        $this->month = now()->format('m');
        $this->year = now()->format('Y');
        $this->fetchTargets();
    }

    public function fetchTargets()
    {
        $this->targets = $this->technicians()->mapWithKeys(function ($technician) {
            return [
                $technician->id => [
                    'user_id' => $technician->id,
                    'month' => $this->month,
                    'year' => $this->year,
                    'invoices_target' => $technician->targets->first()?->invoices_target,
                    'amount_target' => $technician->targets->first()?->amount_target,
                ],
            ];
        });
    }

    #[Computed()]
    public function departments()
    {
        return Department::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->whereNotNull('income_account_id')
            ->orderBy('name')
            ->get();
    }

    public function updatedMonth()
    {
        $this->fetchTargets();
    }

    public function updatedYear()
    {
        $this->fetchTargets();
    }

    #[Computed()]
    public function technicians()
    {
        return User::query()
            ->select('id', 'title_id', 'department_id', 'active', 'name_' . app()->getLocale())
            ->whereIn('title_id', Title::TECHNICIANS_GROUP)
            ->whereActive(1)
            ->when($this->department_id, function ($q) {
                $q->whereIn('department_id', $this->department_id);
            })
            ->with('department:id,name_ar,name_en')
            ->with('title:id,name_ar,name_en')
            ->with('targets', function ($q) {
                $q->where('month', $this->month);
                $q->where('year', $this->year);
            })
            ->orderBy('department_id')
            ->orderBy('active', 'desc')
            ->orderBy('title_id')
            ->get();
    }

    public function save()
    {

        $this->validate();

        // Convert targets to a collection if it isn't one
        $targetsCollection = collect($this->targets);

        $filteredTargets = $targetsCollection->filter(function ($target) {
            return (!is_null($target['invoices_target']) && $target['invoices_target'] !== '') || 
                   (!is_null($target['amount_target']) && $target['amount_target'] !== '');
        });

        foreach ($filteredTargets as $target) {

            Target::updateOrCreate([
                'user_id' => $target['user_id'],
                'month' => $target['month'],
                'year' => $target['year'],
            ], [
                'invoices_target' => $target['invoices_target'],
                'amount_target' => $target['amount_target'],
            ]);
        }

        $this->fetchTargets();
    }
    public function render()
    {
        return view('livewire.targets.target-form');
    }
}
