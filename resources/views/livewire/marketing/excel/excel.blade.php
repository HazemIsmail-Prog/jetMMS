<table>
    <thead>
        <tr>
            <th>@lang('messages.created_at')          </th>
            <th>@lang('messages.creator')             </th>
            <th>@lang('messages.customer_name')       </th>
            <th>@lang('messages.customer_phone')      </th>
            <th>@lang('messages.address')             </th>
            <th>@lang('messages.notes')               </th>
            <th>@lang('messages.type')                </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
        <tr>
            <td>{{ $row->created_at->format('Y/m/d') }}                              </td>
            <td>{{ $row->creator->name }}                                            </td>
            <td>{{ $row->name }}                                                     </td>
            <td>{{ $row->phone }}                                                    </td>
            <td>{{ $row->address }}                                                  </td>
            <td>{{ $row->notes }}                                                    </td>
            <td>{{ __('messages.' . $row->type) }}                                   </td>
        </tr>
        @endforeach
    </tbody>
</table>