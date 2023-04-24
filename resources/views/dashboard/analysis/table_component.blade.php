@if(isset($headers))
  <div class="table-responsive">
                    
      <table class="table table-striped analysis-table jambo_table">
         <thead>
            <tr class="headings">
				@foreach($headers as $header)
              	<th class="column-title">{{$header['title']}}</th>
              	@endforeach
            </tr>
         </thead>
         <tbody>
         </tbody>
      </table>
 </div>
 @endif