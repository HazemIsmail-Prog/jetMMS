<div>
    <x-dialog-modal maxWidth="md" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ $form->id ? __('messages.edit_user') : __('messages.add_user') }}</div>
            <x-section-border />
        </x-slot>

        <x-slot name="content">
            @if ($showModal)
                <form wire:submit="save">
                    <div class=" space-y-3">
                        <div>
                            <x-label for="name_ar">{{ __('messages.name_ar') }}</x-label>
                            <x-input class="w-full" type="text" wire:model="form.name_ar" id="name_ar"
                                dir="rtl" />
                            <x-input-error for="form.name_ar" />
                        </div>
                        <div>
                            <x-label for="name_en">{{ __('messages.name_en') }}</x-label>
                            <x-input class="w-full" type="text" wire:model="form.name_en" id="name_en"
                                dir="ltr" />
                            <x-input-error for="form.name_en" />
                        </div>

                        <div>
                            <x-label for="username">{{ __('messages.username') }}</x-label>
                            <x-input class="w-full" type="text" wire:model="form.username"
                                placeholder="{{ __('messages.expected') . ' = ' . $newExpectedUsername }}" id="username"
                                autocomplete="new-username" dir="ltr" />
                            <x-input-error for="form.username" />
                        </div>
                        <div>
                            <x-label for="password">{{ __('messages.password') }}</x-label>
                            <x-input class="w-full" type="password" wire:model="form.password" id="password"
                                autocomplete="new-password" dir="ltr" />
                            <x-input-error for="form.password" />
                        </div>
                        <div>
                            <x-label for="title_id">{{ __('messages.title') }}</x-label>
                            <x-select class="w-full" wire:model="form.title_id" id="title_id">
                                <option value="">---</option>
                                @foreach ($this->titles as $title)
                                    <option value="{{ $title->id }}">{{ $title->name }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error for="form.title_id" />
                        </div>
                        <div>
                            <x-label for="department_id">{{ __('messages.department') }}</x-label>
                            <x-select class="w-full" wire:model="form.department_id" id="department_id">
                                <option value="">---</option>
                                @foreach ($this->departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name_ar }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error for="form.department_id" />
                        </div>
                        <div>
                            <x-label for="shift_id">{{ __('messages.shift') }}</x-label>
                            <x-select class="w-full" wire:model="form.shift_id" id="shift_id">
                                <option value="">---</option>
                                @foreach ($this->shifts as $shift)
                                    <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error for="form.shift_id" />
                        </div>
                        <div>
                            <x-label for="roles">{{ __('messages.roles') }}</x-label>
                            <div class="p-2 border dark:border-gray-700 rounded-lg">
                                @foreach ($this->roles as $i => $role)
                                    <x-label for="role-{{ $role->id }}" class="flex items-center">
                                        <x-checkbox value="{{ $role->id }}" wire:model="form.roles"
                                            id="role-{{ $role->id }}" />
                                        <span class="ms-2 ">{{ $role->name }}</span>
                                    </x-label>
                                @endforeach
                            </div>
                            <x-input-error for="form.roles" />
                        </div>
                        <div>
                            <x-label for="active" class="flex items-center">
                                <x-checkbox wire:model="form.active" id="active" />
                                <span class="ms-2 ">{{ __('messages.active') }}</span>
                            </x-label>
                        </div>
                        {{-- <div>
                            <x-label for="cost_account_id">{{ __('messages.cost_account_id') }}</x-label>
                            <x-searchable-select position="relative" :list="$this->accounts" model="form.cost_account_id" />
                            <x-input-error for="form.cost_account_id" />
                        </div> --}}
                    </div>
                    <div class="mt-3">
                        <x-button>{{ __('messages.save') }}</x-button>
                    </div>
                </form>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
