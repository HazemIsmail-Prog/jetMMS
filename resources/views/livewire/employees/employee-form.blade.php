<div>
    <x-dialog-modal maxWidth="7xl" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ $modalTitle }}</div>
            <x-section-border />
        </x-slot>

        <x-slot name="content">
            @if ($showModal)


                <form wire:submit="save" class=" space-y-4">

                    <div class="flex gap-3">
                        {{-- User --}}

                        <div class=" border dark:border-gray-700 rounded-lg p-2">
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
                                            <th class=" text-start p-2">
                                                <x-label class=" font-normal">{{ __('messages.name_ar') }}</x-label>
                                            </th>
                                            <td class="p-2">
                                                <x-label>{{ $selectedUser->name_ar }}</x-label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class=" text-start p-2">
                                                <x-label class=" font-normal">{{ __('messages.name_en') }}</x-label>
                                            </th>
                                            <td class="p-2">
                                                <x-label>{{ $selectedUser->name_en }}</x-label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class=" text-start p-2">
                                                <x-label class=" font-normal">{{ __('messages.department') }}</x-label>
                                            </th>
                                            <td class="p-2">
                                                <x-label>{{ $selectedUser->department->name }}</x-label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class=" text-start p-2">
                                                <x-label class=" font-normal">{{ __('messages.title') }}</x-label>
                                            </th>
                                            <td class="p-2">
                                                <x-label>{{ $selectedUser->title->name }}</x-label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class=" text-start p-2">
                                                <x-label class=" font-normal">{{ __('messages.shift') }}</x-label>
                                            </th>
                                            <td class="p-2">
                                                <x-label>{{ $selectedUser->shift->name ?? '-' }}</x-label>
                                            </td>
                                        </tr>
                                    </table>
                                @endif
                            </div>
                        </div>

                        {{-- Employee Details --}}
                        <div class=" border dark:border-gray-700 rounded-lg p-2 flex-1">
                            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                                {{ __('messages.employee_details') }}
                            </h2>
                            <x-section-border />
                            <div class=" grid sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-4 gap-3">


                                <div class="flex flex-col">
                                    <x-label for="cid">{{ __('messages.cid') }}</x-label>
                                    <x-input required min="111111111111" max="999999999999" type="number"
                                        wire:model="form.cid" id="cid" dir="ltr" />
                                    <x-input-error for="form.cid" />
                                </div>
                                <div class="flex flex-col">
                                    <x-label for="iban">{{ __('messages.iban') }}</x-label>
                                    <x-input required type="text" wire:model="form.iban" id="iban"
                                        dir="ltr" />
                                    <x-input-error for="form.iban" />
                                </div>



                                <div class="flex flex-col">
                                    <x-label for="passport_no">{{ __('messages.passport_no') }}</x-label>
                                    <x-input required type="text" wire:model="form.passport_no" id="passport_no" />
                                    <x-input-error for="form.passport_no" />
                                </div>

                                <div class="flex flex-col">
                                    <x-label for="startingSalary">{{ __('messages.startingSalary') }}</x-label>
                                    <x-input required type="number" wire:model="form.startingSalary"
                                        id="startingSalary" />
                                    <x-input-error for="form.startingSalary" />
                                </div>


                                <div class="flex flex-col">
                                    <x-label
                                        for="startingLeaveBalance">{{ __('messages.startingLeaveBalance') }}</x-label>
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
                                    <x-label
                                        for="recidencyExpirationDate">{{ __('messages.recidencyExpirationDate') }}</x-label>
                                    <x-input required type="date" wire:model="form.recidencyExpirationDate"
                                        id="recidencyExpirationDate" />
                                    <x-input-error for="form.recidencyExpirationDate" />
                                </div>
                                <div class="flex flex-col">
                                    <x-label for="passportIssueDate">{{ __('messages.passportIssueDate') }}</x-label>
                                    <x-input required type="date" wire:model="form.passportIssueDate"
                                        id="passportIssueDate" />
                                    <x-input-error for="form.passportIssueDate" />
                                </div>
                                <div class="flex flex-col">
                                    <x-label
                                        for="passportExpirationDate">{{ __('messages.passportExpirationDate') }}</x-label>
                                    <x-input required type="date" wire:model="form.passportExpirationDate"
                                        id="passportExpirationDate" />
                                    <x-input-error for="form.passportExpirationDate" />
                                </div>

                                <div class="flex flex-col">
                                    <x-label for="nationality">{{ __('messages.nationality') }}</x-label>
                                    <x-input required type="text" wire:model="form.nationality" id="nationality" />
                                    <x-input-error for="form.nationality" />
                                </div>

                                <div class="flex flex-col">
                                    <x-label for="gender">{{ __('messages.gender') }}</x-label>
                                    <x-select required wire:model="form.gender" id="gender">
                                        <option value="">---</option>
                                        <option value="male">{{ __('messages.male') }}</option>
                                        <option value="female">{{ __('messages.female') }}</option>
                                    </x-select>
                                    <x-input-error for="form.gender" />
                                </div>
                                <div class="flex flex-col">
                                    <x-label for="company_id">{{ __('messages.company') }}</x-label>
                                    <x-select required wire:model="form.company_id" id="company_id">
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
                                        <x-input required type="date" wire:model="form.lastWorkingDate"
                                            id="lastWorkingDate" />
                                        <x-input-error for="form.lastWorkingDate" />
                                    </div>
                                @endif

                            </div>

                        </div>
                    </div>
                    <div class="text-end">
                        <x-button>{{ __('messages.save') }}</x-button>
                    </div>

                </form>

            @endif


        </x-slot>
    </x-dialog-modal>
</div>
