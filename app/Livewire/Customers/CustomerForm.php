<?php

namespace App\Livewire\Customers;

use App\Livewire\Forms\CustomerForm as FormsCustomerForm;
use App\Models\Area;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class CustomerForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public Customer $customer;

    public FormsCustomerForm $form;

    #[On('showCustomerFormModal')]
    public function show(Customer $customer)
    {
        $this->form->reset();
        $this->showModal = true;
        $this->customer = $customer;

        $this->form->fill($this->customer);
        $this->modalTitle = $this->customer->id ? __('messages.edit_customer') . ' ' . $this->customer->name : __('messages.add_customer');

        if ($this->customer->id) {
            $this->form->phones = collect($this->customer->phones)->toArray();
            $this->form->addresses = collect($this->customer->addresses)->toArray();
        } else {
            $this->add_row('phone');
            $this->add_row('address');
        }

        $this->js("
        setTimeout(function() { 
            document.getElementById('name').focus();
         }, 100);
        ");
    }

    #[Computed()]
    public function areas()
    {
        return Area::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    public function add_row($type)
    {
        if ($type == 'address') {
            $this->form->addresses[] = [
                'id' => null,
                'customer_id' => $this->customer->id ?? null,
                'area_id' => '',
                'block' => null,
                'street' => null,
                'jadda' => null,
                'building' => null,
                'floor' => null,
                'apartment' => null,
                'notes' => null,
            ];
        }
        if ($type == 'phone') {
            $this->form->phones[] = [
                'id' => null,
                'type' => 'mobile',
                'number' => null,
            ];
        }
    }

    public function delete_row($type, $index)
    {
        if ($type == 'address') {
            unset($this->form->addresses[$index]);
            $this->form->addresses = array_values($this->form->addresses);
        }
        if ($type == 'phone') {
            unset($this->form->phones[$index]);
            $this->form->phones = array_values($this->form->phones);
        }
    }

    public function save()
    {
        $this->validate();
        $data = [
            'name' => $this->form->name,
            'notes' => $this->form->notes,
            'cid' => $this->form->cid,
            'active' => 1,
            'created_by' => $this->customer->created_by ?? auth()->id(),
            'updated_by' => auth()->id(),
        ];
        $addresses = [];
        foreach ($this->form->addresses as $row) {
            $addresses[] = [
                'id' => $row['id'],
                'area_id' => $row['area_id'],
                'block' => $row['block'],
                'street' => $row['street'],
                'jadda' => $row['jadda'],
                'building' => $row['building'],
                'floor' => $row['floor'],
                'apartment' => $row['apartment'],
                'notes' => $row['notes'],
            ];
        }

        if (!$this->customer->id) {
            //create
            DB::beginTransaction();
            try {
                $customer = Customer::create($data);
                $customer->phones()->createMany($this->form->phones);
                $customer->addresses()->createMany($addresses);
                $this->customer = $customer;
                DB::commit();
                $this->form->reset();
                $this->showModal = false;
                $this->dispatch('customersUpdated');
            } catch (\Exception $e) {
                DB::rollback();
                dd($e);
                throw ValidationException::withMessages(['error' => __('messages.something went wrong ' . '(' . $e->getMessage() . ')')]);
            }
        } else {
            //edit
            DB::beginTransaction();
            try {
                $this->customer->update($data);
                $this->customer->phones()->doesntHave('orders')->delete();
                $this->customer->addresses()->whereDoesntHave('orders')->whereDoesntHave('contracts')->delete();
                foreach ($this->form->phones as $phone) {
                    $this->customer
                        ->phones()
                        ->updateOrCreate(
                            [
                                'id' => $phone['id'],
                            ],
                            [
                                'type' => $phone['type'],
                                'number' => $phone['number'],
                            ]
                        );
                }
                foreach ($this->form->addresses as $address) {
                    $this->customer
                        ->addresses()
                        ->updateOrCreate(
                            [
                                'id' => $address['id'],
                            ],
                            [
                                'area_id' => $address['area_id'],
                                'block' => $address['block'],
                                'street' => $address['street'],
                                'jadda' => $address['jadda'],
                                'building' => $address['building'],
                                'floor' => $address['floor'],
                                'apartment' => $address['apartment'],
                                'notes' => $address['notes'],
                            ]
                        );
                }
                DB::commit();
                $this->form->reset();
                $this->showModal = false;
                $this->dispatch('customersUpdated');
            } catch (\Exception $e) {
                DB::rollback();
                dd($e);
                throw ValidationException::withMessages(['error' => __('messages.something went wrong ' . '(' . $e->getMessage() . ')')]);
            }
        }
    }

    public function render()
    {
        return view('livewire.customers.customer-form');
    }
}
