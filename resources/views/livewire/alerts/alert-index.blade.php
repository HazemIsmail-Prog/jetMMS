<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">

            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.alerts') }}
            </h2>

        </div>
    </x-slot>

    @livewire('attachments.attachment-form')


    <div class="grid grid-cols-1 xl:grid-cols-2 gap-3">
        <div>
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight mb-3">
                {{ __('messages.upcommingExpiredAttachments') }}
            </h2>
            <div class=" h-96 overflow-y-auto">
                <x-table>
                    <x-thead>
                        <tr>
                            <x-th>{{ __('messages.description') }}</x-th>
                            <x-th>{{ __('messages.name') }}</x-th>
                            <x-th>{{ __('messages.remaining_days') }}</x-th>
                            <x-th>{{ __('messages.expirationDate') }}</x-th>
                            <x-th></x-th>
                        </tr>
                    </x-thead>
                    <tbody>
                        @foreach ($this->upcommingExpiredAttachments as $attachment)
                        <x-tr>
                            <x-td class="!text-start !whitespace-normal">{{ $attachment->description }}</x-td>
                            <x-td class="!text-start !whitespace-normal">
                                @switch($attachment->attachable_type)
                                    @case('App\Models\Car')
                                        {{$attachment->attachable->code}}
                                        @break
                                    @case('App\Models\Company')
                                        {{$attachment->attachable->name}}
                                        @break
                                    @case('App\Models\Employee')
                                        {{$attachment->attachable->user->name}}
                                        @break
                                    @default
                                @endswitch
                            </x-td>
                            <x-td class="!text-start !whitespace-normal">{{$attachment->date_difference}}</x-td>
                            <x-td class="!text-start">{{ $attachment->expirationDate->format('d-m-Y') }}</x-td>
                            <x-td>
                                <div class=" flex items-center justify-end gap-2">
                                    <a target="__blank" href="{{ $attachment->full_path }}">
                                        <x-badgeWithCounter title="{{ __('messages.view') }}">
                                            <x-svgs.view class="w-4 h-4" />
                                        </x-badgeWithCounter>
                                    </a>
                                    @can('update',$attachment->attachable)
                                        <x-badgeWithCounter
                                            wire:click="$dispatch('showAttachmentFormModal',{attachment:{{ $attachment }}})"
                                            title="{{ __('messages.edit') }}">
                                            <x-svgs.edit class="w-4 h-4" />
                                        </x-badgeWithCounter>
                                    @endcan
                                </div>
                            </x-td>
                        </x-tr>
                        @endforeach
                    </tbody>
                </x-table>
            </div>
        </div>
        <div>
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight mb-3">
                {{ __('messages.expiredAttachments') }}
            </h2>
            <div class=" h-96 overflow-y-auto">
                <x-table>
                    <x-thead>
                        <tr>
                            <x-th>{{ __('messages.description') }}</x-th>
                            <x-th>{{ __('messages.name') }}</x-th>
                            <x-th>{{ __('messages.expirationDate') }}</x-th>
                            <x-th></x-th>
                        </tr>
                    </x-thead>
                    <tbody>
                        @foreach ($this->expiredAttachments as $attachment)
                        <x-tr>
                            <x-td class="!text-start !whitespace-normal">{{ $attachment->description }}</x-td>
                            <x-td class="!text-start !whitespace-normal">
                                @switch($attachment->attachable_type)
                                    @case('App\Models\Car')
                                        {{$attachment->attachable->code}}
                                        @break
                                    @case('App\Models\Company')
                                        {{$attachment->attachable->name}}
                                        @break
                                    @case('App\Models\Employee')
                                        {{$attachment->attachable->user->name}}
                                        @break
                                    @default
                                @endswitch
                            </x-td>
                            <x-td class="!text-start">{{ $attachment->expirationDate->format('d-m-Y') }}</x-td>
                            <x-td>
                                <div class=" flex items-center justify-end gap-2">
                                    <a target="__blank" href="{{ $attachment->full_path }}">
                                        <x-badgeWithCounter title="{{ __('messages.view') }}">
                                            <x-svgs.view class="w-4 h-4" />
                                        </x-badgeWithCounter>
                                    </a>
                                    @can('update',$attachment->attachable)
                                        <x-badgeWithCounter
                                            wire:click="$dispatch('showAttachmentFormModal',{attachment:{{ $attachment }}})"
                                            title="{{ __('messages.edit') }}">
                                            <x-svgs.edit class="w-4 h-4" />
                                        </x-badgeWithCounter>
                                    @endcan
                                </div>
                            </x-td>
                        </x-tr>
                        @endforeach
                    </tbody>
                </x-table>
            </div>
        </div>

    </div>
</div>