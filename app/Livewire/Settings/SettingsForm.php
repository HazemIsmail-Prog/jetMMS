<?php

namespace App\Livewire\Settings;

use App\Livewire\Forms\SettingForm;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class SettingsForm extends Component
{
    use WithFileUploads;

    public SettingForm $form;
    public Setting $setting;

    public function mount()
    {
        $this->setting = Setting::find(1);
        $this->form->fill($this->setting);
    }

    public function save()
    {
        $validated = $this->form->validate();
        
        
        if ($validated['logo'] !== $this->setting->logo) {
            Storage::disk('s3')->delete($this->setting->logo);
            $validated['logo'] = $this->saveToS3($validated['logo']);
        }
        if ($validated['favicon'] !== $this->setting->favicon) {
            Storage::disk('s3')->delete($this->setting->favicon);
            $validated['favicon'] = $this->saveToS3($validated['favicon']);
        }
        $this->setting->update($validated);

        $this->reset();

        return redirect()->route('settings.form');
    }

    public function saveToS3($file)
    {
        $storeFolder = 'Assets';
        $path = $file->storePublicly($storeFolder, 's3');
        return $path;
    }

    public function render()
    {
        return view('livewire.settings.settings-form');
    }
}
