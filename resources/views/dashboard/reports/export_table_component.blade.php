<table>
    <thead>
    <tr>
      <th>{{$title}}</th>
    </tr>
    </thead>
</table>

<table>

    <thead></thead>
    <tbody>
        @if ($related_area != null)
        <tr>
            <th>Related Area</th>
            <td>{{$related_area}}</td>
        </tr>
        @else
        <tr>
            <th></th>
            <td></td>
        </tr>
        @endif
        <tr>
            <th>From</th>
            <td>{{ $filters['date_from'] ??'' }}</td>
        </tr>
        <tr>
            <th>To</th>
            <td>{{ $filters['date_to'] ?? ''}}</td>
        </tr>
        <tr>
            <th>Generated On</th>
            <td>{{ DateHelper::getDate()}}</td>
        </tr>
    </tbody>

    {{-- <thead>
    <tr>
      <th>From</th>
      <th>To</th>
      @if ($related_area != null) <th>Related Area</th> @endif
      <th>Generated On</th>
    </tr>
    </thead>
    <tbody>
    	<tr>
    	<td>{{ $filters['date_from'] ??'' }}</td>
    	<td>{{ $filters['date_to'] ?? ''}}</td>
    	@if ($related_area != null) <td>{{$related_area}}</td> @endif
    	<td>{{ DateHelper::getDate()}}</td>
    	</tr>
    </tbody> --}}
</table>

<table>
    <thead>
	    <tr>
	      <th>Date</th>
	      <th>Reference_Number</th>
	      {{-- <th>Type</th> --}}
	      <th>Branch_Code/Department</th>
	      @for($i=1;$i<=$category_levels;$i++)
	      	<th>Category Level {{$i}}</th>
	      @endfor
	      <th>Complaint</th>
	      <th>Name_of_the_Complainant</th>
	      <th>Account/Card Number</th>
	      <th>NIC</th>
	      <th>Resolved_By</th>
	      <th>Event_Closed_Date</th>
	      <th>1<sup>st</sup>_Reminder</th>
	      <th>2<sup>nd</sup>_Reminder</th>
	      <th>3<sup>rd</sup>_Reminder</th>
	      <th>Final_Reminder</th>
	    </tr>
    </thead>
    <tbody>
    @foreach($complaints as $complaint)
    	@php $branches =[]; $resolvedUsers = []; @endphp
    	 @foreach ($complaint->complaintUsers as $user)
	        {{-- @if($user->user_role == 'OWNER') --}}
            @if($user->user_role == 'RECPT')
	           @php $branches[] = $user->department->name; @endphp
	        @endif
	     @endforeach
	     @foreach ($complaint->solutions as $solution)
            @if($solution->resolvedBy['primary_owner'] == 1)
                @php $resolvedUsers[] = $solution->resolvedBy['system_role']??''; @endphp
                {{-- @php $resolvedUsers[] = $solution->resolvedByUser->user->first_name??''.' '.$solution->resolvedByUser->user->last_name??''; @endphp --}}
            @endif
	           {{-- @php $resolvedUsers[] = $solution->resolvedBy->user->first_name??''.' '.$solution->resolvedBy->user->last_name??''; @endphp --}}
               {{-- @php $resolvedUsers[] = $solution->resolvedByUser->user->first_name??''.' '.$solution->resolvedByUser->user->last_name??''; @endphp --}}
	     @endforeach 
        <tr>
            <td>{{ \PhpOffice\PhpSpreadsheet\Shared\Date::phpToExcel($complaint->open_date) }}</td>
            <td>{{ $complaint->reference_number }}</td>
            {{-- <td>{{ EnumTextHelper::getEnumText ($complaint->type) }}</td> --}}
            <td>{{ (count($branches) > 0 ? implode(', ',$branches) : '') }}</td>
	      	@for($i=1;$i<=$category_levels;$i++)
	      		@if ($category_levels - $i == 0)
                    <td>{{ $complaint->category->name }}</td>
                @elseif ($category_levels - $i == 1)
                    <td>{{ $complaint->category->parentCategory->name }}</td>
                @elseif ($category_levels - $i == 2)
                    <td>{{ $complaint->category->parentCategory->parentCategory->name }}</td>
                @elseif ($category_levels - $i == 3)
                    <td>{{ $complaint->category->parentCategory->parentCategory->parentCategory->name }}</td>
                @elseif ($category_levels - $i == 4)
                    <td>{{ $complaint->category->parentCategory->parentCategory->parentCategory->parentCategory->name }}</td>
                @elseif ($category_levels - $i == 5)
                    <td>{{ $complaint->category->parentCategory->parentCategory->parentCategory->parentCategory->parentCategory->name }}</td>
                @endif
	      	@endfor
            <td>{!! $complaint->complaint !!}</td>
            <td>{{ isset($complaint->complainant) ? $complaint->complainant->first_name.' '.$complaint->complainant->last_name :'' }}</td>
            <td>{{ $complaint->account_no }}</td>
            <td>{{ isset($complaint->complainant) ? $complaint->complainant->nic : '' }}</td>
            <td>{{ (count($resolvedUsers) > 0 ? implode(', ',$resolvedUsers) : '') }}</td>
            <td>{{ \PhpOffice\PhpSpreadsheet\Shared\Date::phpToExcel($complaint->close_date) }}</td>
            <td>{{ isset($complaint->reminders[0]) && $complaint->reminders[0]->reminder_date? \PhpOffice\PhpSpreadsheet\Shared\Date::phpToExcel($complaint->reminders[0]->reminder_date):'' }}</td>
            <td>{{ isset($complaint->reminders[1]) && $complaint->reminders[1]->reminder_date? \PhpOffice\PhpSpreadsheet\Shared\Date::phpToExcel($complaint->reminders[1]->reminder_date):'' }}</td>
            <td>{{ isset($complaint->reminders[2]) && $complaint->reminders[2]->reminder_date? \PhpOffice\PhpSpreadsheet\Shared\Date::phpToExcel($complaint->reminders[2]->reminder_date):'' }}</td>
            <td>{{ isset($complaint->reminders[3]) && $complaint->reminders[3]->reminder_date? \PhpOffice\PhpSpreadsheet\Shared\Date::phpToExcel($complaint->reminders[3]->reminder_date):'' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>