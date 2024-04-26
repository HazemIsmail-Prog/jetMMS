<?php

namespace App\Livewire\Settings;

use App\Livewire\Forms\SettingForm;
use App\Models\Account;
use App\Models\Setting;
use App\Services\S3;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class SettingsForm extends Component
{
    use WithFileUploads;

    public SettingForm $form;
    public Setting $setting;

    #[Computed()]
    public function accounts()
    {
        return Account::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name_' . app()->getLocale())
            ->where('level', 3)
            ->get();
    }

    public function mount()
    {
        $this->setting = Setting::find(1);
        $this->form->fill($this->setting);
    }

    public function save()
    {
        $validated = $this->form->validate();

        $validated['logo'] = S3::saveToS3($validated['logo'],$this->setting,$this->setting->logo);
        $validated['favicon'] = S3::saveToS3($validated['favicon'],$this->setting,$this->setting->favicon);
        
        $this->setting->update($validated);

        $this->reset();

        return redirect()->route('settings.form');
    }

    public function render()
    {
        return view('livewire.settings.settings-form')->title(__('messages.settings'));
    }
}
