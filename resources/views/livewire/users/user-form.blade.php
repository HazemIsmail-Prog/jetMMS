<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $form->id ? __('messages.edit_user') : __('messages.add_user') }}
            </h2>
        </div>
    </x-slot>


    <form wire:submit="save" class=" space-y-4 max-w-md mx-auto">





        <div class="flex flex-col">
            <x-label for="name_ar">{{ __('messages.name_ar') }}</x-label>
            <x-input type="text" wire:model="form.name_ar" id="name_ar" dir="rtl" />
            <x-input-error for="form.name_ar" />
        </div>
        <div class="flex flex-col">
            <x-label for="name_en">{{ __('messages.name_en') }}</x-label>
            <x-input type="text" wire:model="form.name_en" id="name_en" dir="ltr" />
            <x-input-error for="form.name_en" />
        </div>
        <div class="flex flex-col">
            <x-label for="username">{{ __('messages.username') }}</x-label>
            <x-input type="text" wire:model="form.username" id="username" dir="ltr" />
            <x-input-error for="form.username" />
        </div>
        <div class="flex flex-col">
            <x-label for="password">{{ __('messages.password') }}</x-label>
            <x-input type="password" wire:model="form.password" id="password" dir="ltr" />
            <x-input-error for="form.password" />
        </div>

        <div class="flex flex-col">
            <x-label for="title_id">{{ __('messages.title') }}</x-label>
            <x-select wire:model="form.title_id" id="title_id">
                <option value="">---</option>
                @foreach ($titles as $title)
                    <option value="{{ $title->id }}">{{ $title->name }}</option>
                @endforeach
            </x-select>
            <x-input-error for="form.title_id" />
        </div>

        <div class="flex flex-col">
            <x-label for="department_id">{{ __('messages.department') }}</x-label>
            <x-select wire:model="form.department_id" id="department_id">
                <option value="">---</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name_ar }}</option>
                @endforeach
            </x-select>
            <x-input-error for="form.department_id" />
        </div>

        <div class="flex flex-col">
            <x-label for="shift_id">{{ __('messages.shift') }}</x-label>
            <x-select wire:model="form.shift_id" id="shift_id">
                <option value="">---</option>
                @foreach ($shifts as $shift)
                    <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                @endforeach
            </x-select>
            <x-input-error for="form.shift_id" />
        </div>

        <div class=" col-span-full">
            <x-label for="active" class="flex items-center">
                <x-checkbox wire:model="form.active" id="active" />
                <span class="ms-2 ">{{ __('messages.active') }}</span>
            </x-label>
        </div>


        <div class=" col-span-full">
            @foreach ($roles as $i => $role)
                <x-label for="role-{{ $role->id }}" class="flex items-center">
                    <x-checkbox value="{{ $role->id }}" wire:model.live="form.roles"
                        id="role-{{ $role->id }}" />
                    <span class="ms-2 ">{{ $role->name }}</span>
                </x-label>
            @endforeach
            <x-input-error for="form.roles" />

        </div>



        <div class="text-end">
            <x-secondary-anchor href="{{ route('user.index') }}">{{ __('messages.back') }}</x-secondary-anchor>
            <x-button>{{ __('messages.save') }}</x-button>
        </div>

    </form>
</div>
