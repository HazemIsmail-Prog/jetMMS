<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $form->id ? __('messages.edit_employee_details') : __('messages.add_employee_details') }}
            </h2>
        </div>
    </x-slot>


    <form wire:submit="save" class=" space-y-4">

        <div class="flex gap-3">
            {{-- User --}}

            <div class=" border rounded-lg p-2">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('messages.user_details') }}
                </h2>
                <x-section-border />
                <div class="flex flex-col gap-4">
                    @if (!$employee->id)
                        <div class="flex flex-col">
                            <x-label for="user_id">{{ __('messages.user') }}</x-label>
                            <x-select required wire:model.live="form.user_id" id="user_id">
                                <option value="">---</option>
                                @foreach ($this->users->sortBy('name') as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error for="form.user_id" />
                        </div>
                    @endif
                    @if ($selectedUser)
                        <table>
                            <tr>
                                <th class=" text-start p-2">{{ __('messages.name_ar') }}</th>
                                <td class="p-2">{{ $selectedUser->name_ar }}</td>
                            </tr>
                            <tr>
                                <th class=" text-start p-2">{{ __('messages.name_en') }}</th>
                                <td class="p-2">{{ $selectedUser->name_en }}</td>
                            </tr>
                            <tr>
                                <th class=" text-start p-2">{{ __('messages.department') }}</th>
                                <td class="p-2">{{ $selectedUser->department->name }}</td>
                            </tr>
                            <tr>
                                <th class=" text-start p-2">{{ __('messages.title') }}</th>
                                <td class="p-2">{{ $selectedUser->title->name }}</td>
                            </tr>
                            <tr>
                                <th class=" text-start p-2">{{ __('messages.shift') }}</th>
                                <td class="p-2">{{ $selectedUser->shift->name ?? '-' }}</td>
                            </tr>
                        </table>
                    @endif
                </div>
            </div>

            {{-- Employee Details --}}
            <div class=" border rounded-lg p-2 flex-1">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('messages.employee_details') }}
                </h2>
                <x-section-border />
                <div class=" grid sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-4 gap-3">


                    <div class="flex flex-col">
                        <x-label for="cid">{{ __('messages.cid') }}</x-label>
                        <x-input required type="number" wire:model="form.cid" id="cid" dir="ltr" />
                        <x-input-error for="form.cid" />
                    </div>



                    <div class="flex flex-col">
                        <x-label for="passport_no">{{ __('messages.passport_no') }}</x-label>
                        <x-input required type="text" wire:model="form.passport_no" id="passport_no" />
                        <x-input-error for="form.passport_no" />
                    </div>

                    <div class="flex flex-col">
                        <x-label for="startingSalary">{{ __('messages.startingSalary') }}</x-label>
                        <x-input required type="number" wire:model="form.startingSalary" id="startingSalary" />
                        <x-input-error for="form.startingSalary" />
                    </div>


                    <div class="flex flex-col">
                        <x-label for="startingLeaveBalance">{{ __('messages.startingLeaveBalance') }}</x-label>
                        <x-input required type="number" wire:model="form.startingLeaveBalance"
                            id="startingLeaveBalance" />
                        <x-input-error for="form.startingLeaveBalance" />
                    </div>




                    <div class="flex flex-col">
                        <x-label for="joinDate">{{ __('messages.joinDate') }}</x-label>
                        <x-input required type="date" wire:model="form.joinDate" id="joinDate" />
                        <x-input-error for="form.joinDate" />
                    </div>
                    <div class="flex flex-col">
                        <x-label for="recidencyExpirationDate">{{ __('messages.recidencyExpirationDate') }}</x-label>
                        <x-input required type="date" wire:model="form.recidencyExpirationDate"
                            id="recidencyExpirationDate" />
                        <x-input-error for="form.recidencyExpirationDate" />
                    </div>
                    <div class="flex flex-col">
                        <x-label for="passportIssueDate">{{ __('messages.passportIssueDate') }}</x-label>
                        <x-input required type="date" wire:model="form.passportIssueDate" id="passportIssueDate" />
                        <x-input-error for="form.passportIssueDate" />
                    </div>
                    <div class="flex flex-col">
                        <x-label for="passportExpirationDate">{{ __('messages.passportExpirationDate') }}</x-label>
                        <x-input required type="date" wire:model="form.passportExpirationDate"
                            id="passportExpirationDate" />
                        <x-input-error for="form.passportExpirationDate" />
                    </div>





                    <div class="flex flex-col">
                        <x-label for="company_id">{{ __('messages.company') }}</x-label>
                        <x-select wire:model="form.company_id" id="company_id">
                            <option value="">---</option>
                            @foreach ($this->companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error for="form.company_id" />
                    </div>




                    <div class="flex flex-col">
                        <x-label for="status">{{ __('messages.status') }}</x-label>
                        <x-select required wire:model.live="form.status" id="status">
                            <option value="">---</option>
                            @foreach (App\Enums\EmployeeStatusEnum::cases() as $status)
                                <option value="{{ $status->value }}">{{ $status->title() }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error for="form.status" />
                    </div>

                    @if (in_array($form->status, ['resigned', 'terminated']))
                        <div class="flex flex-col">
                            <x-label for="lastWorkingDate">{{ __('messages.lastWorkingDate') }}</x-label>
                            <x-input required type="date" wire:model="form.lastWorkingDate" id="lastWorkingDate" />
                            <x-input-error for="form.lastWorkingDate" />
                        </div>
                    @endif

                </div>

            </div>
        </div>




        {{-- <div class="flex flex-col">
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

        </div> --}}



        <div class="text-end">
            <x-secondary-anchor href="{{ route('employee.index') }}">{{ __('messages.back') }}</x-secondary-anchor>
            <x-button>{{ __('messages.save') }}</x-button>
        </div>

    </form>
</div>
