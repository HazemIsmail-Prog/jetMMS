<?php

namespace App\Livewire\Fleet;

use App\Models\Car;
use App\Models\CarAction;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class ActionModal extends Component
{

    public $showModal = false;
    public $modalTitle = '';
    public Car $car;
    public $date;
    public $time;
    public $users = [];
    public $driver_id = '';
    public $kilos = '';
    public $fuel = '2';
    public $notes = '';
    public $type = '';

    #[On('showActionFormModal')]
    public function show($car_id)
    {
        $this->reset();
        $this->car = Car::find($car_id);
        $this->modalTitle = $this->car->driver_id ? __('messages.unassign') : __('messages.assign');
        $this->type = $this->car->driver_id ? 'unassign' : 'assign';
        $this->showModal = true;
        $this->date = today()->format('Y-m-d');
        $this->time = now()->format('H:i');
        $this->users = User::select('id', 'name_en', 'name_ar')->get();
    }

    public function rules()
    {
        return [
            'type' => 'required',
            'date' => 'required',
            'time' => 'required',
            'driver_id' => 'required_if:type,==,assign',
            'kilos' => 'required',
            'fuel' => 'required',
            'notes' => 'nullable',
        ];
    }

    public function save()
    {
        //validate
        $validated = $this->validate();


        //set missing fields
        $validated['car_id'] = $this->car->id;
        $validated['created_by'] = auth()->id();
        $validated['driver_id'] = $this->type == 'assign' ? $this->driver_id : $this->car->driver_id;;

        //create or update the record
        CarAction::create($validated);

        //update Car Record
        $driver_id = $this->driver_id == '' ? null : $this->driver_id;
        $this->car->driver_id = $driver_id;
        $this->car->technician_id = null;
        $this->car->save();

        //Reset form to avoid multipule clicks
        $this->reset();

        //session flash
        session()->flash('success', 'Action saved successfully.');

        //redirect to index page
        $this->redirect(CarIndex::class, navigate: true);
    }

    public function render()
    {
        return view('livewire.fleet.action-modal');
    }
}
