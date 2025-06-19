<table>
    <thead>
        <tr>
            <th>@lang('messages.order_number')        </th>
            <th>@lang('messages.customer_name')       </th>
            <th>@lang('messages.customer_phone')      </th>
            <th>@lang('messages.address')             </th>
            <th>@lang('messages.creator')             </th>
            <th>@lang('messages.technician')          </th>
            <th>@lang('messages.status')              </th>
            <th>@lang('messages.department')          </th>
            <th>@lang('messages.estimated_start_date')</th>
            <th>@lang('messages.notes')               </th>
            <th>@lang('messages.order_description')   </th>
            <th>@lang('messages.completed_at')        </th>
            <th>@lang('messages.cancelled_at')        </th>
            <th>@lang('messages.cancel_reason')       </th>
            <th>@lang('messages.created_at')          </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
        <tr>
            <td>{{ $row->id }}                                                       </td>
            <td>{{ $row->customer->name }}                                           </td>
            <td>{{ $row->phone->number }}                                            </td>
            <td>{{ $row->address->full_address() }}                                  </td>
            <td>{{ $row->creator->name }}                                            </td>
            <td>{{ @$row->technician->name }}                                        </td>
            <td>{{ $row->status->name }}                                             </td>
            <td>{{ $row->department->name }}                                         </td>
            <td>{{ $row->estimated_start_date->format('d-m-Y') }}                    </td>
            <td>{{ $row->notes }}                                                    </td>
            <td>{{ $row->order_description }}                                        </td>
            <td>{{ $row->completed_at ? $row->completed_at->format('d-m-Y H:i'):'' }}</td>
            <td>{{ $row->cancelled_at ? $row->cancelled_at->format('d-m-Y H:i'):'' }}</td>
            <td>{{ @$row->reason }}                                                  </td>
            <td>{{ $row->created_at->format('d-m-Y H:i') }}                          </td>
        </tr>
        @endforeach
    </tbody>
</table>